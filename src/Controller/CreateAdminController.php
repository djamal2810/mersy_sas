<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\UserDetails;
//use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Constants\Constants;

#[Route('/admin/user-management/')]
#[IsGranted('ROLE_ADMINISTRATEUR', message: 'Accès refusé. Espace reservé uniquement aux administrateurs')]
class CreateAdminController extends AbstractController
{
	public function __construct(private EntityManagerInterface $em, private UserPasswordHasherInterface $userPasswordHasher, private TranslatorInterface $translator)
    {
        
    }
	
    #[Route('/create/admin', name: 'app_create_admin')]
    public function index(): Response
    {
		$username = "admin";
		$user_first_name = "Djamalladine";
		$user_last_name = "Mahamat Pierre";
		$user_email = "djamal2810@gmail.com";
		$user_telephone = "63979955";
		$user_password = "mourir";
		$roles = [];
		$roles[] = Constants::ROLE_ADMIN;
		$roles[] = Constants::ROLE_EDITOR;
		$user_status = []; 
		$user_status[] = Constants::USER_STATUS_ACTIVE;
		$user_status[] = Constants::USER_STATUS_ENABLED;
		
		$result = "";
		
		$userRepository = $this->em->getRepository(User::class);
		//$userDetailsRepository = $this->em->getRepository(UserDetails::class);
		
		//Verify that the username is non-existant
		$registeredUserEntity = $userRepository->findOneBy(['username' => $username]);

		if($registeredUserEntity)
		{
			//$userDetailsRepository = $this->em->getRepository(UserDetails::class);
			//$this->addFlash('info', $this->translator->trans("Super administrateur créé avec succès"));
			$this->addFlash('info', $this->translator->trans("Administrateur trouvé avec succès"));
			$result = "Trouvé";
		}else
		{
			
			//$this->addFlash('info', $this->translator->trans("Administrateur non trouvé"));
			$result = "Non Trouvé\n<br>";
			$details = new UserDetails();
			$details->setFirstName($user_first_name);
			$details->setLastName($user_last_name);
			$details->setEmail($user_email );
			$details->setTelephone($user_telephone);
			$this->em->persist($details);
            //$this->em->flush();
			
			$user = new User();
			$user->setUsername($username);
			$user->setRoles($roles);
			$user->setPassword($this->userPasswordHasher->hashPassword($user, $user_password));
			$user->setUserDetails($details);
			$user->setStatus($user_status);
			$this->em->persist($user);
            $this->em->flush();
			$result = "Créé\n<br>";
		}
		/*
		$user = new User;
            $user
                ->setLastName("Kamga")
                ->setFirstName("Joseph")
                ->setPaysOhada($this->paysRepository->find(39))
                ->setEmail("superadmin@gmail.com")
                ->setRoles([ConstantsClass::ROLE_SUPER_ADMINISTRATEUR])
                ->setPassword($this->userPasswordHasher->hashPassword($user, 'super_@dmin_dser_@fric@'))
                ->setVille("Yaoundé")
                ->setRecevoirInfos(true)
                ->setJaccepteConditions(true)
                ->setAPaye(true)
                ->setTelephone("677539012")
                ->setPaysOhada($this->paysOhadaRepository->find(3))
            ;
    
            $this->em->persist($user);
            $this->em->flush();
    
            $this->addFlash('info', $this->translator->trans("Super administrateur créé avec succès"));
		*/
		
		/*
        return $this->render('create_admin/index.html.twig', [
            'controller_name' => 'CreateAdminController',
        ]);
		*/
		
		return new Response($result);
    }
}
