<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function index(): Response
    {
		// controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
		
        return $this->render('security/logout.html.twig', [
            'controller_name' => 'LogoutController',
        ]);
    }
}
