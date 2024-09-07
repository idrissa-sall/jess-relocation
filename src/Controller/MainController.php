<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            
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
