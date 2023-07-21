<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\PHPMailerService;
use App\Service\MailContentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

use App\Entity\NewsLetterSubscription;


class NewsletterController extends AbstractController
{
    #[Route('/newsletter', name: 'app_newsletter')]
    public function index(): Response
    {
        return $this->render('newsletter/index.html.twig', [
            'controller_name' => 'NewsletterController',
        ]);
    }
	
	public static function gen_uuid_v4() 
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            random_int( 0, 0xffff ), random_int( 0, 0xffff ),

            // 16 bits for "time_mid"
            random_int( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            random_int( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            random_int( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            random_int( 0, 0xffff ), random_int( 0, 0xffff ), random_int( 0, 0xffff )
        );
    }
	
	#[Route('/newsletter/send/consfirmation/email', name: 'app_send_consfirmation_email')]
    public function sendConsfirmationEmail(PHPMailerService $mailerService, MailContentService $mailContentService, Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
		$uuid = $this->gen_uuid_v4();
		if ($request->request->has('newsletterSubscription')) 
		{
			if(isset($_SERVER['HTTPS']))
			{
        		$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    		}
    		else
			{
        		$protocol = 'http';
    		}
			$host = $protocol . "://" . $_SERVER['HTTP_HOST'];
			//$this->addFlash("notice", $translator->trans('Merci.').' '.$host);
		
			
			$email = $request->request->get('email');
			if($email==='' || $email===null)
			{
				$this->addFlash("notice", $translator->trans('Votre tentative de souscription pour le service newsletter a échoué. Veuillez entrer une adresse mail valide.'));
				return $this->redirectToRoute('app_home');
			}
			$registered_email = $entityManager->getRepository(NewsLetterSubscription::class)->findOneBy(['email' => $email]);			if(!$registered_email)	
			{
				$subscription = new NewsLetterSubscription;
				$subscription->setEmail($email);
    			$subscription->setCode($uuid);
				$subscription->setVerified(false);
				$entityManager->persist($subscription);
				$entityManager->flush();
				
				$content = "Merci de nous avoir fait confiance. Cliquez <a href='".$host."/newsletter/consfirm/email/?confirmationCode=".$uuid."&email=".$email."'>ici</a> pur confirmer votre souscription.";
				$subject = $translator->trans('Confirmation de votre souscription pour Mersy-S.A.S newsletter');
				$mailerService->sendNewsletterConfirmationMail($subject, $content, $email);
		
				$this->addFlash("notice", $translator->trans('Merci. Veuillez vérifier votre email pour confirmer votre souscription!'));
            	return $this->redirectToRoute('app_home'); // redirect when done
			}else
			{
				$this->addFlash("notice", $translator->trans('Cet email est déjà enrégistré dans notre base de données. Veuillez vérifier votre email pour confirmer.'));
				return $this->redirectToRoute('app_home'); // redirect when done
			}
			
		}

       	return $this->redirectToRoute('app_home'); // redirect when done
    }
	
	#[Route('/newsletter/consfirm/email', name: 'app_consfirm_email')]
    public function consfirmEmail(Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
		//$confirmationCode = $request->request->get('confirmationCode');
		//$email = $request->request->get('email');
		//$confirmationCode = $request->query->get('confirmationCode');
		//$email = $request->query->get('email');
		$confirmationCode = $_GET['confirmationCode'];
		$email= $_GET['email'];
		
		$registered_account = $entityManager->getRepository(NewsLetterSubscription::class)->findOneBy(['email' => $email, 'code' => $confirmationCode]);
		
		if($registered_account)
		{
			$registered_account->setVerified(true);
			$entityManager->flush();
			$this->addFlash("notice", $translator->trans('Votre souscription au service newsletter a reussi.'));
			return $this->redirectToRoute('app_home'); 
		}
		
       	return $this->redirectToRoute('app_home'); // redirect when done
    }
}
