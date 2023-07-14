<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Constants\Constants;
use App\Entity\UserDetails;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
		
		//$this->addFlash('info', $translator->trans("Le compte client a été créé avec succès"));
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user, array('translator'=>$translator));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			$details = new UserDetails();
			$details->setFirstName($form->get('firstName')->getData());
			$details->setLastName($form->get('lastName')->getData());
			$details->setEmail($form->get('email')->getData());
			$details->setTelephone($form->get('telephone')->getData());
			$entityManager->persist($details);
			
			$user_status = []; 
			$user_status[] = Constants::USER_STATUS_ACTIVE;
			$user_status[] = Constants::USER_STATUS_ENABLED;
			$user->setStatus($user_status);
			$roles[] = Constants::ROLE_CLIENT_SERVICE_CUSTOMER;
			$user->setRoles($roles);
			// encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
			$user->setUserDetails($details);
            $entityManager->persist($user);
			
			$details->setUser($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
			$this->addFlash('info', $translator->trans("Le compte client a été créé avec succès"));

            //return $this->redirectToRoute('app_home');
			return $this->redirect('home');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
