<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Service\Uploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(Request $request, SluggerInterface $slugger, ManagerRegistry $doctrine): Response
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
            // profil picture
            $profilPicture = $form->get('profil_picture')->getData();
            if($profilPicture)
            {
                $filename = $uploaderService->upload($profilPicture);
                $review->setProfilPicture($filename);
            }

            $em = $doctrine->getManager();
            $em->persist($review);
            $em->flush();
            $this->addFlash('success', "Merci d'avoir soumis votre avis, après vérification, il sera publié.");
            return $this->redirectToRoute('app_index');
        }


        return $this->render('main/index.html.twig', [
            'form' => $form->createView(),
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
    function appointment(): Response
    {
        return $this->render('main/appointment.html.twig', array(

        ));
    }

    #[Route('/contact', name: 'app_contact')]
    function contact(): Response
    {
        return $this->render('main/contact.html.twig', [

        ]);
    }

    #[Route('/reviews', name: 'app_reviews')]
    function reviews(): Response
    {
        return $this->render('main/reviews.html.twig', [

        ]);
    }
}
