<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Form\UserAccountManagementFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Constants\Constants;
use App\Entity\UserDetails;
use App\Entity\UserRoleChoice;

class UserAccountManagementController extends AbstractController
{
	
	#[Route('/admin/user/account/creation', name: 'app_admin_user_account_creation')]
    public function adminUserAccountCreation(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
		//$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		
		$userRoleChoiceRepository = $entityManager->getRepository(UserRoleChoice::class);
		$userRoleChoices = $userRoleChoiceRepository ->findAll();;
		
		//ACCOUNT CREATION SECTION
		$newUserAccount = new User();
		$details = new UserDetails();
		$newUserAccount->setUserDetails($details);
		$details->setUser($newUserAccount);
        $accountCreationForm = $this->createForm(UserAccountManagementFormType::class, $newUserAccount, array(
    		//'action' => $this->generateUrl('some_route'), 
			'translator'=>$translator,
    		'attr' => array(
        		'id' => 'accountCreationForm', 
    		)
		));
        $accountCreationForm->handleRequest($request);

        if ($accountCreationForm->isSubmitted() && $accountCreationForm->isValid()) 
		{
			
			$selectedUserChoice = $request->request->get('user_role_choice');
			
			if($selectedUserChoice === 'USER_ROLE_CHOICE')
			{
				echo 'Choisissez un role pour votre utilisateur.<br>';
			}else
			{
				$roles[] = $selectedUserChoice;
				if(null!==$request->get("editor") && $request->get("editor")=="editor")
				{
					$roles[] = Constants::ROLE_EDITOR;
					//echo 'editor<br>';
				}
				$newUserAccount->setRoles($roles);
				
				$user_status = []; 
				$user_status[] = Constants::USER_STATUS_ACTIVE;
				$user_status[] = Constants::USER_STATUS_ENABLED;
				$newUserAccount->setStatus($user_status);
				
				$newUserAccount->setPassword(
                $userPasswordHasher->hashPassword(
                    $newUserAccount,
                    $accountCreationForm->get('password')->getData()
                	)
            	);
				
				// do anything else you need here, like send an email
				$this->addFlash('info', $translator->trans("Le compte a été créé avec succès"));
				$entityManager->persist($newUserAccount);
            	$entityManager->flush();
				//echo $selectedUserChoice.'<br>';
				//return $this->redirectToRoute('app_user_account_management');
			}
				
        }
		
		return $this->render('security/admin_user_account_creation.html.twig', [
            'userAccountCreationForm' => $accountCreationForm->createView(),
			'userRoleChoices' => $userRoleChoices,
        ]);
    }
	
	#[Route('/admin/user/account/modification/{id}', name: 'app_admin_user_account_modification')]
    public function adminUserAccountModification(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, TranslatorInterface $translator, $id=0): Response
    {
		//$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		
		$userRoleChoiceRepository = $entityManager->getRepository(UserRoleChoice::class);
		$userRoleChoices = $userRoleChoiceRepository ->findAll();;
      	$userRepository = $entityManager->getRepository(User::class);
		$user = $userRepository->find($id);
		$username = $user->getUsername();
		$is_editor = false;
		$registered_role = '';
		
		if(in_array('ROLE_ADMIN', $user->getRoles(), true))
		{
			$registered_role = 'ROLE_ADMIN';
			//$user_role_in_db = $userRoleChoiceRepository->findOneBy(['role' => 'ROLE_ADMIN']);
		}elseif(in_array('ROLE_CLIENT_SERVICE_CUSTOMER', $user->getRoles(), true))
		{
			$registered_role = 'ROLE_CLIENT_SERVICE_CUSTOMER';
			//$user_role_in_db = $userRoleChoiceRepository->findOneBy(['role' => 'ROLE_CLIENT_SERVICE_CUSTOMER']);
		}elseif(in_array('ROLE_CLIENT_SERVICE_PROVIDER', $user->getRoles(), true))
		{
			$registered_role = 'ROLE_CLIENT_SERVICE_PROVIDER';
			//$user_role_in_db = $userRoleChoiceRepository->findOneBy(['role' => 'ROLE_CLIENT_SERVICE_PROVIDER']);
		}elseif(in_array('ROLE_SUPER_ADMIN', $user->getRoles(), true))
		{
			$registered_role = 'ROLE_SUPER_ADMIN';
			//$user_role_in_db = $userRoleChoiceRepository->findOneBy(['role' => 'ROLE_SUPER_ADMIN']);
		}
		
		if(in_array('ROLE_EDITOR', $user->getRoles(), true))
		{
			$is_editor = true;
		}
		

        $accountCreationForm = $this->createForm(UserAccountManagementFormType::class, $user, array(
    		//'action' => $this->generateUrl('some_route'), 
			'translator'=>$translator,
    		'attr' => array(
        		'id' => 'accountCreationForm', 
    		)
		));
        $accountCreationForm->handleRequest($request);

        if ($accountCreationForm->isSubmitted() && $accountCreationForm->isValid()) 
		{
			
			$selectedUserChoice = $request->request->get('user_role_choice');
			
			if($selectedUserChoice === 'USER_ROLE_CHOICE')
			{
				echo 'Choisissez un role pour votre utilisateur.<br>';
			}else
			{
				$roles[] = $selectedUserChoice;
				if(null!==$request->get("editor") && $request->get("editor")=="editor")
				{
					$roles[] = Constants::ROLE_EDITOR;
					//echo 'editor<br>';
				}
				$user->setRoles($roles);
				
				$user_status = []; 
				$user_status[] = Constants::USER_STATUS_ACTIVE;
				$user_status[] = Constants::USER_STATUS_ENABLED;
				$user->setStatus($user_status);
				
				$user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $accountCreationForm->get('password')->getData()
                	)
            	);
				
				// do anything else you need here, like send an email
				//$this->addFlash('info', $translator->trans("Le compte a été créé avec succès"));
            	$entityManager->flush();
				$this->addFlash('info', $translator->trans("Le compte a été modifié avec succès"));
				//echo $selectedUserChoice.'<br>';
				return $this->redirectToRoute('app_admin_user_account_management');
				
			}
		
        }
		
		return $this->render('security/admin_user_account_modification.html.twig', [
            'userAccountCreationForm' => $accountCreationForm->createView(),
			'userRoleChoices' => $userRoleChoices,
			'username' => $username,
			'registeredRole' => $registered_role,
			'isEditor' => $is_editor,
        ]);
    }
	
	#[Route('/deleteUser/{id}', name: 'app_user_account_management_delete')]
	public function deleteUser(EntityManagerInterface $entityManager, $id)
    {
      	$userRepository = $entityManager->getRepository(User::class);
		$user = $userRepository->find($id);
		$details = $user->getUserDetails();
		
      	$entityManager->remove($user);
		$entityManager->remove($details);
      	$entityManager->flush();

      	return $this->redirectToRoute('app_user_account_management');

    }
	
	#[Route('/admin/user/account/management/{page}', name: 'app_admin_user_account_management')]
    public function adaminUserAccountManagement(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, TranslatorInterface $translator, $page = 1): Response
    {
		//$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		
		//ACCOUNT MANAGEMENT SECTION
		//MODIFICATION SECTION
		// get the article repository
    	$userRepository = $entityManager->getRepository(User::class);
    	// build the query for the doctrine paginator
    	$query = $userRepository->createQueryBuilder('u')
              ->orderBy('u.id', 'DESC')
              ->getQuery();
    	//set page size
    	$pageSize = '3';
    	// load doctrine Paginator
    	$paginator = new Paginator($query);
    	// you can get total items
    	$totalUsers = count($paginator);
    	// get total pages
    	$pagesCount = ceil($totalUsers / $pageSize);
    	// now get one page's items:
    	$paginator
        ->getQuery()
        ->setFirstResult($pageSize * ($page-1)) // set the offset
        ->setMaxResults($pageSize); // set the limit
		
    	// return stuff..
		
			return $this->render('security/user_account_management.html.twig', [
			'paginator' => $paginator,
			'totalUsers' => $totalUsers,
			'pagesCount' => $pagesCount,
			'currentPage' => $page,
			'pageSize' => $pageSize,
        	]);
    }

	
}
