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

class MainController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(Request $request, SluggerInterface $slugger, ManagerRegistry $doctrine, ReviewRepository $reviewRepository): Response
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
            $this->addFlash('success', "Merci d'avoir soumis votre avis, après vérification, il sera publié.");
            return $this->redirectToRoute('app_index');
        } elseif($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('error', "Votre message n'a pas pu être publié, merci de vérifier votre saisie");
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
    function appointment(Request $request, ManagerRegistry $doctrine, AppointmentRepository $appointmentRepository): Response
    {
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
                $this->addFlash('error', "Un rendez-vous est déjà prevue pour la date et l'heure que vous avez sélectionné, merci de modifier votre saisie.");
                $this->redirectToRoute('app_appointment');
            } else {
                $appointment->setSubmitionDate(new DateTimeImmutable());
                $appointment->setIsDone(false);

                $em = $doctrine->getManager();
                $em->persist($appointment);
                $em->flush();

                $this->addFlash('success', "Votre demande à bien été pris en compte, à bientôt!");
                return $this->redirectToRoute('app_appointment');
            }
        } elseif($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('error', "Votre demande de rendez-vous n'a pas pu aboutir, merci de verifier votre saisie");
        }

        return $this->render('main/appointment.html.twig', array(
            'form'  => $form->createView(),
        ));
    }

    #[Route('/contact', name: 'app_contact')]
    function contact(Request $request, MailerInterface $mailerInterface): Response
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
            $mailerService->sendMail($contact->getName(), $contact->getEmail(), $contact->getSubject(), $contact->getMessage());
            // flash message and redirect
            $this->addFlash('success', "Votre message à bien été transmis à Jess-Relocation, vous aurez une réponse dans les plus brefs délais");
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
}
