<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Contact;
use App\Entity\Review;
use App\Form\AppointmentType;
use App\Form\ContactType;
use App\Form\ReviewType;
use App\Repository\AppointmentRepository;
use App\Repository\ReviewRepository;
use App\Service\Mailer;
use App\Service\Uploader;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(Request $request, SluggerInterface $slugger, ManagerRegistry $doctrine, ReviewRepository $reviewRepository, TranslatorInterface $translator): Response
    {
        // uploader
        $targetDirectory = $this->getParameter('reviewers_pictures_directory');
        $uploaderService = new Uploader($targetDirectory, $slugger);

        // review form
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // submission date = now
            $review->setSubmitionDate(new \DateTimeImmutable());
            // set review unactive before validation
            $review->setIsValid(false);
            // profil picture
            $profilPicture = $form->get('profil_picture')->getData();
            if($profilPicture)
            {
                $fileName = $uploaderService->upload($profilPicture);
                $review->setProfilPicture($fileName);
                // resize picture in directory
                $uploaderService->resize($targetDirectory . '/' . $fileName, 350, 350);
            }

            $em = $doctrine->getManager();
            $em->persist($review);
            $em->flush();

            $message = $translator->trans("Merci d'avoir soumis votre avis, après vérification, il sera publié.");
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_index');
        } elseif($form->isSubmitted() && !$form->isValid())
        {
            $message = $translator->trans("Votre message n'a pas pu être publié, merci de vérifier votre saisie");
            $this->addFlash('error', $message);
        }

        // last 3 reviews
        $reviews = $reviewRepository->findBy(['isValid' => true], ['id' => 'DESC'], 3);

        return $this->render('main/index.html.twig', [
            'form'      => $form->createView(),
            'reviews'   => $reviews,
        ]);
    }


    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('main/about.html.twig', [

        ]);
    }


    #[Route('/services-for-individuals', name: 'app_individuals_services')]
    public function individualsServices(): Response
    {

        return $this->render('main/individuals-services.html.twig', [

        ]);
    }


    #[Route('/services-for-businesses', name: 'app_businesses_services')]
    public function businessServices(): Response
    {

        return $this->render('main/business-services.html.twig', [

        ]);
    }


    #[Route('/appointment', name: 'app_appointment')]
    function appointment(TranslatorInterface $translator, Request $request, ManagerRegistry $doctrine, AppointmentRepository $appointmentRepository, MailerInterface $mailerInterface): Response
    {
        $mailer = new Mailer($mailerInterface);

        // appointment form
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // verify if an appointment exist with same date and same hour
            $existingAppointment = $appointmentRepository->findOneBy(['date_apm' => $appointment->getDateApm(), 'hour' => $appointment->getHour()]);
            if($existingAppointment != null)
            {
                $message = $translator->trans("Un rendez-vous est déjà prevue pour la date et l'heure que vous avez sélectionné, merci de modifier votre saisie.");
                $this->addFlash('error', $message);
                $this->redirectToRoute('app_appointment');
            } else {
                $appointment->setSubmitionDate(new DateTimeImmutable());
                $appointment->setIsDone(false);

                $em = $doctrine->getManager();
                $em->persist($appointment);
                $em->flush();

                $mailer->sendReservationEmail($appointment->getName());

                $message = $translator->trans("Votre demande à bien été pris en compte, à bientôt!");
                $this->addFlash('success', $message);
                return $this->redirectToRoute('app_appointment');
            }
        } elseif($form->isSubmitted() && !$form->isValid())
        {
            $message = $translator->trans("Votre demande de rendez-vous n'a pas pu aboutir, merci de verifier votre saisie");
            $this->addFlash('error', $message);
        }

        return $this->render('main/appointment.html.twig', array(
            'form'  => $form->createView(),
        ));
    }

    #[Route('/contact', name: 'app_contact')]
    function contact(TranslatorInterface $translator, Request $request, MailerInterface $mailerInterface): Response
    {
        // mailer
        $mailerService = new Mailer($mailerInterface);

        // contact form
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // send email
            $mailerService->sendContactMail($contact->getName(), $contact->getEmail(), $contact->getSubject(), $contact->getMessage());
            // flash message and redirect
            $message = $translator->trans("Votre message à bien été transmis à Jess-Relocation, vous aurez une réponse dans les plus brefs délais");
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('main/contact.html.twig', [
            'form'  => $form,
        ]);
    }

    #[Route('/reviews', name: 'app_reviews')]
    function reviews(Request $request, ReviewRepository $reviewRepository, PaginatorInterface $paginator): Response
    {
        $query = $reviewRepository->getReviewsForPagination();
        $reviews = $paginator->paginate($query, $request->query->getInt('page', 1), 9);

        return $this->render('main/reviews.html.twig', [
            'reviews'   => $reviews,
        ]);
    }

    #[Route('/legal-notices', name: 'app_legal_notices')]
    public function legalNotices(): Response
    {

        return $this->render('main/legal-notices.html.twig', []);
    }

    #[Route('/change-locale/{lang}', name: 'app_change_locale')]
    public function changeLocale(string $lang, Request $request): Response
    {
        // get languages in services.yaml and declared in twig.yaml
        $localesLanguages = $this->getParameter('app.locales');
        // verify if our parameter lang is in our local languages table
        if(in_array($lang, $localesLanguages))
        {
            $request->getSession()->set('_locale', $lang);
        }

        // refresh the page
        return $this->redirect($request->headers->get('referer'));
    }
}
