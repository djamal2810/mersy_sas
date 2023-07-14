<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Entity\LegalCase;
use App\Entity\LegalCaseDocument;
use App\Entity\IncompleteMessage;
use App\Entity\RejectionMotive;
use App\Entity\LegalCaseCategory;
use App\Form\LegalCaseFormType;
use App\Form\AgentAssignmentFormType;
use App\Form\AgentLegalCaseActionFormType;
use App\Form\AgentLegalCaseIncompleteFormType;
use App\Form\AgentLegalCaseRejectionFormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Service\PHPMailerService;
use App\Service\MailContentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\LegalCaseSequenceGenerator;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Constants\Constants;
use App\Form\LegalCaseFormFlow;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;

class LegalCaseController extends AbstractController
{
    #[Route('/legal/case/{id<[0-9]+>}', name: 'app_legal_case')]
    public function index(PHPMailerService $mailerService, MailContentService $mailContentService, Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, SluggerInterface $slugger, LegalCaseSequenceGenerator $sequenceGenerator, LegalCaseFormFlow $flow, int $id=0): Response
    {
		//$this->denyAccessUnlessGranted('ROLE_EDITOR');
		// On permet de revenir à une étape en un clic
		//$flow->setAllowDynamicStepNavigation(true);
		
		//$flow = $this->get('app.form.flow.corporationFlow'); // must match the flow's service id
		$user = $security->getUser();
		$legalCaseEntityRepository = $entityManager->getRepository(LegalCase::class);
		$listOfDocuments = [];
		$listOfPreferredNames = [];
		
		 // On permet de revenir à une étape en un clic
         $flow->setAllowDynamicStepNavigation(true);
		
		// On recupère le dossier
         if ($id == 0) 
		 {
			 $legalCase = new LegalCase;
         } else 
		 {
             $legalCase = $legalCaseEntityRepository->find($id);
         }
		
		$lastLegalCase = $legalCaseEntityRepository->createQueryBuilder('l')
				->orderBy('l.id', 'DESC')
				->getQuery()
				->setMaxResults(1)
				->getOneOrNullResult();
		
		// On lie le dossier à son flow
        $flow->bind($legalCase);
 
        // formulaire de l'étape en cours
        $form = $submittedForm = $flow->createForm();
		
		if ($flow->isValid($form)) 
		{
			if ($request->request->has('etatEnregistrer')) 
			{
                if ($id == 0) 
				{
                  	// Si c'est une nouvelle formalité
               		// Onattribue une reference à la formalite et on la set à l'état ETAT_ACHEVE_NON_PAYE
           			// Si c'est une nouvelle formalité, on lui atribue un numéro de reférence
             		$legalCase->setCreationDate(new \DateTime('@'.strtotime('now')));
					$legalCase->setModificationDate(new \DateTime('@'.strtotime('now')));
					$legalCase->setCreatedBy($user);
					$legalCase->setReferenceNo($sequenceGenerator->nextReferenceNo($lastLegalCase));
      			} else 
				{
                   	// si c'est une mise à jour de la formalié, on modifie la date de mise à jour et la formalité passe à l'état achevé_non_payé
					$legalCase->setModificationDate(new \DateTime('@'.strtotime('now')));
           		}
				
				if ($flow->getCurrentStepNumber() >= 3)
				{
					$documents = $legalCase->getLegalCaseDocuments();
					if($documents)
					{
						foreach($documents as $legalCaseDocument)
						{
							$file = $legalCaseDocument->getFile();
							if($file)
							{
								$originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
								$preferred_file_name = $originalFilename.'.'.$file->guessExtension();
								$file_directory = $this->getParameter('mersy_customer_upload_directory');
							
								try 
								{
            						//$file->move($this->getParameter('mersy_customer_upload_directory'), $fileName);
									$legalCaseDocument->setPreferredName($preferred_file_name);
									$legalCaseDocument->setPath($file_directory );
        						} catch (Exception $e) 
								{
									//echo $e->message()."<br>";
        						}
							}
						}
						//var_dump($documents);
					}else
					{
						//echo "not found<br>";
					}
				
				}
 
                $entityManager->persist($legalCase);
				$entityManager->flush();
            	$flow->reset(); // remove step data from the session
				$this->addFlash("notice", $translator->trans('Merci de nous avoir fait confiance. A très vite !'));
            	return $this->redirectToRoute('app_home'); // redirect when done
             } else 
			 {
			
				$flow->saveCurrentStepData($form);
        		if ($flow->nextStep()) 
				{
            		// form for the next step
            		$form = $flow->createForm();
        		} else 
				{
					if ($id == 0) 
					{
                  		// Si c'est une nouvelle formalité
               			// Onattribue une reference à la formalite et on la set à l'état ETAT_ACHEVE_NON_PAYE
           				// Si c'est une nouvelle formalité, on lui atribue un numéro de reférence
             			$legalCase->setCreationDate(new \DateTime('@'.strtotime('now')));
						$legalCase->setModificationDate(new \DateTime('@'.strtotime('now')));
						$legalCase->setCreatedBy($user);
						$legalCase->setReferenceNo($sequenceGenerator->nextReferenceNo($lastLegalCase));
						$legalCase->setStatus(Constants::LEGAL_CASE_STATUS_SUBMITTED);
      				} else 
					{
                   		// si c'est une mise à jour de la formalié, on modifie la date de mise à jour et la formalité passe à l'état achevé_non_payé
						$legalCase->setModificationDate(new \DateTime('@'.strtotime('now')));
           			}

					$documents = $legalCase->getLegalCaseDocuments();
					if($documents)
					{
						foreach($documents as $legalCaseDocument)
						{
							$file = $legalCaseDocument->getFile();
							if($file)
							{
								$originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
								$preferred_file_name = $originalFilename.'.'.$file->guessExtension();
								$file_directory = $this->getParameter('mersy_customer_upload_directory');
							
								try 
								{
            						//$file->move($this->getParameter('mersy_customer_upload_directory'), $fileName);
									$legalCaseDocument->setPreferredName($preferred_file_name);
									$legalCaseDocument->setPath($file_directory );
        						} catch (Exception $e) 
								{
									//echo $e->message()."<br>";
        						}
							}
						}
						//var_dump($documents);
					}else
					{
						//echo "not found<br>";
					}
				
					$entityManager->persist($legalCase);
					$entityManager->flush();
            		$flow->reset(); // remove step data from the session
					
					$mailContent = $mailContentService->submittedLegalCaseClient($legalCase, $legalCase->getFirstName()." ".$legalCase->getLastName(), new \DateTime('@'.strtotime('now')));
					$mailSent = $mailerService->sendMailwithPHPMailer($mailContent['subject'], $mailContent['content'], $legalCase->getContact()->getEmail(), $legalCase->getFirstName()." ".$legalCase->getLastName());
					
					if($mailSent)
						$this->addFlash("notice", $translator->trans('Merci de nous avoir fait confiance. A très vite ! Veuillez verifiez votre email pour confirmation!'));
					else
						$this->addFlash("notice", $translator->trans('Merci de nous avoir fait confiance. A très vite !'));
            		return $this->redirectToRoute('app_customer_legal_cases'); // redirect when done
        		}
				 
			 }	
    	}
		 
		return $this->render('legal_case/legal_case_submission.html.twig', [
             'form' => $form->createView(),
             'flow' => $flow,
             'legalCase' => $legalCase,
         ]);
    }
	
	
	#[Route('/customer/legal/cases/{page}', name: 'app_customer_legal_cases')]
    public function customerLegalCases(Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, $page = 1): Response
    {
		//$this->denyAccessUnlessGranted('ROLE_EDITOR');
		
		$securityContext = $this->container->get('security.authorization_checker');
		//if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) 
		//{
    	//	// authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
		//	echo 'authenticated';
		//}
		if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED'))
		{
			$user = $security->getUser();
		
			$statusChecked = null;
			if ($request->request->has('filtrer')) 
			{
				// Si c'est le bouton Filtrer qui est validé, etatChecked prend la valeur de l'état qui est coché dans le formulaire
				$statusChecked = $request->request->get('legalCaseStatus');
				//echo 'status: '.$statusChecked.'<br>';
			} else 
			{
				//  Si ce n'est pas le bouton Filtrer qui est validé, on supprime la clé etat de la requête
				$request->request->remove('legalCaseStatus');
				$statusChecked = null;
			}
		
			$legalCaseEntityRepository = $entityManager->getRepository(LegalCase::class);
    		// build the query for the doctrine paginator
		
			if(null===$statusChecked)
			{
				$query = $legalCaseEntityRepository->createQueryBuilder('l')
					->andWhere('l.createdBy = :val')
					->setParameter('val', $user)
					->orderBy('l.id', 'DESC')
           			->getQuery();
			}else
			{
				$query = $legalCaseEntityRepository->createQueryBuilder('l')
					->andWhere('l.createdBy = :val')
					->andWhere('l.status LIKE :val2')
					->setParameter('val', $user)
					->setParameter('val2', $statusChecked)
					->orderBy('l.id', 'DESC')
           			->getQuery();
			}

    		//set page size
    		$pageSize = '10';
    		// load doctrine Paginator
    		$paginator = new Paginator($query);
    		// you can get total items
    		$totalLegalCases = count($paginator);
    		// get total pages
    		$pagesCount = ceil($totalLegalCases / $pageSize);
    		// now get one page's items:
    		$paginator
        		->getQuery()
        		->setFirstResult($pageSize * ($page-1)) // set the offset
				->setMaxResults($pageSize); // set the limit
		
			$status = 
				[
					'submitted' => Constants::LEGAL_CASE_STATUS_SUBMITTED,
					'categorized' => Constants::LEGAL_CASE_STATUS_CATEGORIZED,
					'inProgress' => Constants::LEGAL_CASE_STATUS_IN_PROGRESS,
					'incomplete' => Constants::LEGAL_CASE_STATUS_INCOMPLETE,
					'assigned' => Constants::LEGAL_CASE_STATUS_ASSIGNED,
					'completed' => Constants::LEGAL_CASE_STATUS_COMPLETED,
					'rejected' => Constants::LEGAL_CASE_STATUS_REJECTED,
				];
		
			return $this->render('legal_case/customer_legal_cases.html.twig', 
				[
					'paginator' => $paginator,
					'totalLegalCases' => $totalLegalCases,
					'pagesCount' => $pagesCount,
					'currentPage' => $page,
					'status' => $status,
					'statusChecked' => $statusChecked,
        		]);
		}else
		{
			$this->addFlash("notice", $translator->trans("Vous devriez vous connecter avant d'avoir acces a ce service."));
			//$session = $request->getSession();
       		//$session->getFlashBag()->add('notice', 'Hello world');
		}
		
		return new Response();
    }
	
	
	#[Route('/customer/legal/case/revision/{id}', name: 'app_customer_legal_case_revision')]
    public function customerLegalCaseRevision(Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, SluggerInterface $slugger, LegalCaseSequenceGenerator $sequenceGenerator, LegalCaseFormFlow $flow, int $id=0): Response
    {
		$this->denyAccessUnlessGranted('ROLE_CLIENT_SERVICE_CUSTOMER');
		if($id!=0)
		{
			$user = $security->getUser();
			$legalCaseEntityRepository = $entityManager->getRepository(LegalCase::class);
			$legalCase = $legalCaseEntityRepository->findOneBy(['id' => $id, 
																'createdBy' => $user]);
			//createdBy
			//$orignalDocuments = $legalCase->getLegalCaseDocuments();
			$orignalDocuments = new ArrayCollection();
			// Create an ArrayCollection of the current Tag objects in the database
        	foreach ($legalCase->getLegalCaseDocuments() as $doc) 
			{
            	$orignalDocuments->add($doc);
        	}
		
		 	// On permet de revenir à une étape en un clic
         	$flow->setAllowDynamicStepNavigation(true);
		
			$lastLegalCase = $legalCaseEntityRepository->createQueryBuilder('l')
				->orderBy('l.id', 'DESC')
				->getQuery()
				->setMaxResults(1)
				->getOneOrNullResult();
		
			// On lie le dossier à son flow
        	$flow->bind($legalCase);
 
        	// formulaire de l'étape en cours
			$form = $submittedForm = $flow->createForm();
		
			if ($flow->isValid($form)) 
			{
				$flow->saveCurrentStepData($form);
        		if ($flow->nextStep()) 
				{
            		// form for the next step
            		$form = $flow->createForm();
				} else 
				{
					
                   	// si c'est une mise à jour de la formalié, on modifie la date de mise à jour et la formalité passe à l'état achevé_non_payé
					$legalCase->setModificationDate(new \DateTime('@'.strtotime('now')));
					
					$documents = $legalCase->getLegalCaseDocuments();
					//Handle the addition of documents
					if($documents)
					{
						foreach($documents as $legalCaseDocument)
						{
							$file = $legalCaseDocument->getFile();
							if($file)
							{
								$originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
								$preferred_file_name = $originalFilename.'.'.$file->guessExtension();
								$file_directory = $this->getParameter('mersy_customer_upload_directory');
							
								try 
								{
            						//$file->move($this->getParameter('mersy_customer_upload_directory'), $fileName);
									$legalCaseDocument->setPreferredName($preferred_file_name);
									$legalCaseDocument->setPath($file_directory );
        						} catch (Exception $e) 
								{
									//echo $e->message()."<br>";
        						}
							}
						}
						//var_dump($documents);
					}else
					{
						//echo "not found<br>";
					}
					
					//Handle the removal of documents
					foreach($orignalDocuments as $originalLegalCaseDocument)
					{
						if (false === $documents->contains($originalLegalCaseDocument)) 
						{
                    			// if you wanted to delete the Tag entirely, you can also do that
                    			$entityManager->remove($originalLegalCaseDocument);
                		}
					}
				
					//$entityManager->persist($legalCase);
					$entityManager->flush();
            		$flow->reset(); // remove step data from the session
            		return $this->redirectToRoute('app_customer_legal_cases'); // redirect when done
        		}
			
    		}
			
			return $this->render('legal_case/legal_case_customer_modification.html.twig', [
             'form' => $form->createView(),
             'flow' => $flow,
             'legalCase' => $legalCase,
			 //'documents' => $documents,
         ]);
		}
			
		return new Response();
    }
	
	#[Route('/customer/legal/case/delete/{id}', name: 'app_customer_legal_case_delete')]
    public function customerLegalCaseDelete(Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, $id = 0): Response
    {
		$this->denyAccessUnlessGranted('ROLE_CLIENT_SERVICE_CUSTOMER');
		if($id!=0)
		{
			$user = $security->getUser();
			$legalCaseEntityRepository = $entityManager->getRepository(LegalCase::class);
			$legalCase = $legalCaseEntityRepository->findOneBy(['id' => $id, 
																'createdBy' => $user]);

			$legalCaseDocuments = $legalCase->getLegalCaseDocuments();
		
			if(null !== $legalCaseDocuments)
			{
				foreach($legalCaseDocuments as $document)
				{
					$entityManager->remove($document);
				}
			}
			$entityManager->remove($legalCase);
      		$entityManager->flush();
		}
      	return $this->redirectToRoute('app_customer_legal_cases');
    }
	
	
	#[Route('/admin/legal/cases/{page}', name: 'app_admin_legal_cases')]
    public function adminLegalCases(PHPMailerService $mailerService, MailContentService $mailContentService, Request $request, Security $security, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager, TranslatorInterface $translator, $page = 1): Response
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		$user = $security->getUser();
		
		//Get the list of unassigned legal cases
		$legalCasesRepository = $entityManager->getRepository(LegalCase::class);
		
		$legalCaseCetegories = $entityManager->getRepository(LegalCaseCategory::class)->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC')
           	->getQuery()
           	->getResult();
		
		$arrayOfAgents = $entityManager->getRepository(User::class)->createQueryBuilder('u')
			->where('u.roles LIKE :roles')
        	->setParameter('roles', '%"'.Constants::ROLE_CLIENT_SERVICE_PROVIDER.'"%')
			->orderBy('u.id', 'ASC')
           	->getQuery()
           	->getResult();
		
		$statusChecked = null;
		if ($request->request->has('filtrer')) {
			// Si c'est le bouton Filtrer qui est validé, etatChecked prend la valeur de l'état qui est coché dans le formulaire
			$statusChecked = $request->request->get('legalCaseStatus');
		} else {
			//  Si ce n'est pas le bouton Filtrer qui est validé, on supprime la clé etat de la requête
			$request->request->remove('legalCaseStatus');
		}
		
		$legalCaseEntityRepository = $entityManager->getRepository(LegalCase::class);
    	// build the query for the doctrine paginator
		
		if(null===$statusChecked)
		{
			$query = $legalCaseEntityRepository->createQueryBuilder('l')
				->orderBy('l.id', 'DESC')
           		->getQuery();
		}else
		{
			$query = $legalCaseEntityRepository->createQueryBuilder('l')
				->andWhere('l.status LIKE :val2')
				->setParameter('val2', $statusChecked)
				->orderBy('l.id', 'DESC')
           		->getQuery();
		}
		
    	//set page size
    	$pageSize = '10';
    	// load doctrine Paginator
    	$paginator = new Paginator($query);
    	// you can get total items
    	$totalLegalCases = count($paginator);
    	// get total pages
    	$pagesCount = ceil($totalLegalCases / $pageSize);
    	// now get one page's items:
    	$paginator
        ->getQuery()
        ->setFirstResult($pageSize * ($page-1)) // set the offset
        ->setMaxResults($pageSize); // set the limit
		
			$status = [
				'submitted' => Constants::LEGAL_CASE_STATUS_SUBMITTED,
				'categorized' => Constants::LEGAL_CASE_STATUS_CATEGORIZED,
				'inProgress' => Constants::LEGAL_CASE_STATUS_IN_PROGRESS,
				'incomplete' => Constants::LEGAL_CASE_STATUS_INCOMPLETE,
				'assigned' => Constants::LEGAL_CASE_STATUS_ASSIGNED,
				'completed' => Constants::LEGAL_CASE_STATUS_COMPLETED,
				'rejected' => Constants::LEGAL_CASE_STATUS_REJECTED,
			];
		
			return $this->render('legal_case/admin_legal_cases.html.twig', [
			'paginator' => $paginator,
			'totalLegalCases' => $totalLegalCases,
			'pagesCount' => $pagesCount,
			'currentPage' => $page,
			'status' => $status,
			'statusChecked' => $statusChecked,
			'legalCaseCetegories' => $legalCaseCetegories,
			'arrayOfAgents' => $arrayOfAgents,
        	]);
    }
	
	#[Route('/admin/legal/case/categorize/action/{id}', name: 'app_admin_legal_case_categorize_action')]
    public function adminLegalCaseCategorizeAction(PHPMailerService $mailerService, MailContentService $mailContentService, Request $request, Security $security, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager, TranslatorInterface $translator, $id = 1): Response
    {
		//$this->denyAccessUnlessGranted('ROLE_EDITOR');
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		if ($request->request->has('categorize')) 
		{
			//echo "Hello ".$id."<br>";
			//$request->query->get('id');
			$category = $request->request->get('legal_case_category');
			//echo "Hello ".$category."<br>";
			if($category !== 'Catégorie')
			{
				$legalCaseRepository = $entityManager->getRepository(LegalCase::class);
				$legalCase = $legalCaseRepository->findOneBy(['id' => $id]);
				$legalCaseCategoryRepository = $entityManager->getRepository(LegalCaseCategory::class);
				$categoryEntity = $legalCaseCategoryRepository->findOneBy([
    				'category' => $category,
				]);
				if(null!==$legalCase->getCategory())
				{
					$oldCategory = $legalCase->getCategory();
					$oldCategory->removeLegalCase($legalCase);
				}
				$legalCase->setCategory($categoryEntity);  
				$legalCase->setStatus(Constants::LEGAL_CASE_STATUS_CATEGORIZED);
				$categoryEntity->addLegalCase($legalCase);
				//Update the database
				$entityManager->flush();
				
				//Redirect to the list of legal cases
				return $this->redirectToRoute('app_admin_legal_cases');
			}
		}
		
		return new Response();
    }
	
	#[Route('/admin/legal/case/Assignment/action/{id}', name: 'app_admin_legal_case_assignment_action')]
    public function adminLegalCaseAssignmentAction(PHPMailerService $mailerService, MailContentService $mailContentService, Request $request, Security $security, FormFactoryInterface $formFactory, EntityManagerInterface $entityManager, TranslatorInterface $translator, $id = 1): Response
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		$user = $security->getUser();
		
		//$this->denyAccessUnlessGranted('ROLE_EDITOR');
		if ($request->request->has('assignAgent')) 
		{
			//echo "Hello ".$id."<br>";
			
			$selectedAgentName = $request->request->get('legal_case_agent');
			
			if($selectedAgentName !== 'Consultant')
			{
				//$agentUserName = strtok($selectedAgentName, " ");
				$agentUserName = explode(' ', trim($selectedAgentName))[0]; //First word
				$legalCaseRepository = $entityManager->getRepository(LegalCase::class);
				$legalCase = $legalCaseRepository->findOneBy(['id' => $id]);
				//echo $agentUserName.$id."<br>";
				$agentRepository = $entityManager->getRepository(User::class);
				$agentEntity = $agentRepository->findOneBy([
    				'username' => $agentUserName,
				]);
				
				//Check if the current legal case is assigned to another agent, if so, remove it from that agent's list of legal cases
				$oldAssignedAgent = $legalCase->getAssignedTo();
				if(null!==$oldAssignedAgent)
				{
					$oldAssignedAgent->removeAssignedLegalCase($legalCase);
				}
				//check if the current legal case is assigned by another admin, if so, remove it from that admin' list of legal cases
				$oldAssigningAgent = $legalCase->getAssignedBy();
				if(null!==$oldAssigningAgent)
				{
					$oldAssigningAgent->removeLegalCaseAssignment($legalCase);
				}
				
				//Asign the current legal case to the chosen agent
				$legalCase->setAssignedTo($agentEntity);
				$legalCase->setAssignmentDate(new \DateTime('@'.strtotime('now')));
				//Set the admin who issued the assignment
				$legalCase->setAssignedBy($user);
				//Update the status of the legal case
				$legalCase->setStatus(Constants::LEGAL_CASE_STATUS_ASSIGNED);
						
				//Update the database
				$entityManager->flush();
						
				$clientMailContent = $mailContentService->assignedLegalCaseClient($legalCase, $legalCase->getFirstName()." ".$legalCase->getLastName(), new \DateTime('@'.strtotime('now')));
				$mailerService->sendMailwithPHPMailer($clientMailContent['subject'], $clientMailContent['content'], $legalCase->getContact()->getEmail(), $legalCase->getFirstName()." ".$legalCase->getLastName());
				
				$consultantMailContent = $mailContentService->assignedLegalCaseConsultant($legalCase, $legalCase->getFirstName()." ".$legalCase->getLastName(), new \DateTime('@'.strtotime('now')));
				$mailerService->sendMailwithPHPMailer($consultantMailContent['subject'], $consultantMailContent['content'], $legalCase->getAssignedTo()->getUserDetails()->getEmail(), $legalCase->getFirstName()." ".$legalCase->getLastName());
				
				//$mailContent = $mailContentService->assignedLegalCaseClientAndConsultant($legalCase, new \DateTime('@'.strtotime('now')));
				//$mailerService->sendMailwithPHPMailer2($mailContent['subject'], $mailContent['content'], $legalCase->getContact()->getEmail(), $legalCase->getAssignedTo()->getUserDetails()->getEmail());

				//Redirect to the list of legal cases
				return $this->redirectToRoute('app_admin_legal_cases');
			}
			
			//return $this->redirectToRoute('app_admin_legal_cases');
		}
		
		return new Response();
    }
	
	#[Route('/admin/legal/case/{id}', name: 'app_admin_legal_case')]
    public function adminLegalCase(PHPMailerService $mailerService, MailContentService $mailContentService, Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, $id = 0): Response
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		//$this->denyAccessUnlessGranted('ROLE_EDITOR');
		$user = $security->getUser();
		
		$legalCaseEntityRepository = $entityManager->getRepository(LegalCase::class);
		$legalCase = $legalCaseEntityRepository->findOneBy(['id' => $id]);
		
		return $this->render('legal_case/admin_legal_case.html.twig', [
			'legalCase' => $legalCase,
        ]);
    }
	
	
	#[Route('/admin/legal/case/revision/{id}', name: 'app_admin_legal_case_revision')]
    public function adminLegalCaseRevision(Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, SluggerInterface $slugger, LegalCaseSequenceGenerator $sequenceGenerator, LegalCaseFormFlow $flow, int $id=0): Response
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		if($id!=0)
		{
			$user = $security->getUser();
			$legalCaseEntityRepository = $entityManager->getRepository(LegalCase::class);
			$legalCase = $legalCaseEntityRepository->findOneBy(['id' => $id]);
			//createdBy
			//$orignalDocuments = $legalCase->getLegalCaseDocuments();
			$orignalDocuments = new ArrayCollection();
			// Create an ArrayCollection of the current Tag objects in the database
        	foreach ($legalCase->getLegalCaseDocuments() as $doc) 
			{
            	$orignalDocuments->add($doc);
        	}
		
		 	// On permet de revenir à une étape en un clic
         	$flow->setAllowDynamicStepNavigation(true);
		
			$lastLegalCase = $legalCaseEntityRepository->createQueryBuilder('l')
				->orderBy('l.id', 'DESC')
				->getQuery()
				->setMaxResults(1)
				->getOneOrNullResult();
		
			// On lie le dossier à son flow
        	$flow->bind($legalCase);
 
        	// formulaire de l'étape en cours
			$form = $submittedForm = $flow->createForm();
		
			if ($flow->isValid($form)) 
			{
				$flow->saveCurrentStepData($form);
        		if ($flow->nextStep()) 
				{
            		// form for the next step
            		$form = $flow->createForm();
				} else 
				{
					
                   	// si c'est une mise à jour de la formalié, on modifie la date de mise à jour et la formalité passe à l'état achevé_non_payé
					$legalCase->setModificationDate(new \DateTime('@'.strtotime('now')));
					
					$documents = $legalCase->getLegalCaseDocuments();
					//Handle the addition of documents
					if($documents)
					{
						foreach($documents as $legalCaseDocument)
						{
							$file = $legalCaseDocument->getFile();
							if($file)
							{
								$originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
								$preferred_file_name = $originalFilename.'.'.$file->guessExtension();
								$file_directory = $this->getParameter('mersy_customer_upload_directory');
							
								try 
								{
            						//$file->move($this->getParameter('mersy_customer_upload_directory'), $fileName);
									$legalCaseDocument->setPreferredName($preferred_file_name);
									$legalCaseDocument->setPath($file_directory );
        						} catch (Exception $e) 
								{
									//echo $e->message()."<br>";
        						}
							}
						}
						//var_dump($documents);
					}else
					{
						//echo "not found<br>";
					}
					
					//Handle the removal of documents
					foreach($orignalDocuments as $originalLegalCaseDocument)
					{
						if (false === $documents->contains($originalLegalCaseDocument)) 
						{
                    			// if you wanted to delete the Tag entirely, you can also do that
                    			$entityManager->remove($originalLegalCaseDocument);
                		}
					}
				
					//$entityManager->persist($legalCase);
					$entityManager->flush();
            		$flow->reset(); // remove step data from the session
            		return $this->redirectToRoute('app_admin_legal_cases'); // redirect when done
        		}
			
    		}
			
			return $this->render('legal_case/legal_case_customer_modification.html.twig', [
             'form' => $form->createView(),
             'flow' => $flow,
             'legalCase' => $legalCase,
			 //'documents' => $documents,
         ]);
		}
			
		return new Response();
    }
	
	#[Route('/admin/download/legal/case/document/{id}', name: 'app_admin_download_legal_case_document')]
    public function adminDownloadLegalCaseDocument(Security $security, EntityManagerInterface $entityManager, $id=0): Response
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		//$this->denyAccessUnlessGranted('ROLE_EDITOR');
		$user = $security->getUser();
		
		$documentRepository = $entityManager->getRepository(LegalCaseDocument::class);
		$legalCaseDocument = $documentRepository->findOneBy(['id' => $id]);
		
		$full_path = $legalCaseDocument->getPath().'/'.$legalCaseDocument->getFileName();
		
		$fileExtension = '';
		$result;
		$tmp_file_name =  $legalCaseDocument->getFileName();
   		preg_match('/[^ .]*$/', $tmp_file_name, $result);
   		$last_word = $result[0];
		$fileExtension = $last_word;
		
		//echo $fileExtension.'<br>';
		
		if($fileExtension==='pdf')
		{
			$file = $full_path;

			if(!file_exists($file))
			{ // file does not exist
    			die('file not found');
			} else 
			{
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
    			header("Content-Disposition: attachment; filename=".$legalCaseDocument->getPreferredName());
    			header("Content-Type: application/zip");
    			header("Content-Transfer-Encoding: binary");
    			// read the file from disk
    			readfile($file);
			
				/*
				header('Content-type: application/octet-stream');
				header("Content-Type: ".mime_content_type($file));
				header("Content-Disposition: attachment; filename=".$legalCaseDocument->getPreferredName());
				while (ob_get_level()) 
				{
					ob_end_clean();
				}
				readfile($file);
				*/
			}
		}else
		{
			echo 'not pdf.';
		}
		//$file = $legalCaseDocument->getFile();
		
		return new Response($full_path);
    }
	
	#[Route('/admin/display/legal/case/document/{id}', name: 'app_admin_display_legal_case_document')]
    public function adminDisplayLegalCaseDocument(Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, $id=0): Response
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		$user = $security->getUser();
		
		$documentRepository = $entityManager->getRepository(LegalCaseDocument::class);
		$legalCaseDocument = $documentRepository->findOneBy(['id' => $id]);
		
		$full_path = $legalCaseDocument->getPath().'/'.$legalCaseDocument->getFileName();
		
		//$file = $legalCaseDocument->getFile();
		$file = $full_path;
		
		$result;
		$tmp_file_name =  $legalCaseDocument->getFileName();
   		preg_match('/[^ .]*$/', $tmp_file_name, $result);
   		$last_word = $result[0];
		$fileExtension = $last_word;

		if($fileExtension==='pdf')
		{
			if(!file_exists($file))
			{ // file does not exist
    			die('file not found');
			} else {	
			
				// Header content type
				//header("Content-type: application/pdf");
  				//header("Content-Length: " . filesize($file));
		
				//while (ob_get_level()) 
				//{
    			//	ob_end_clean();
				//}
 				// Send the file to the browser.
				//readfile($file);
			
				//header('Content-type: application/pdf'); 
				//header('Content-Disposition: inline; filename="' .$file. '"'); 
				//header('Content-Transfer-Encoding: binary'); 
				//header('Accept-Ranges: bytes'); 
				//@readfile($file);  

				header('Content-type: application/pdf');
				header('Content-Disposition: inline; filename="' . $file . '"');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . filesize($file));
				header('Accept-Ranges: bytes');
			
				while (ob_get_level()) 
				{
    				ob_end_clean();
				}
			
				@readfile($file);
			
			}
			
		}else
		{
			echo $fileExtension.' is not pdf';
		}
		
		
		return new Response($full_path);
    }
	
	#[Route('/admin/legal/case/delete/{id}', name: 'app_admin_legal_case_delete')]
    public function adminLegalCaseDelete(Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, $id = 0): Response
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
	    $legalCaseEntityRepository = $entityManager->getRepository(LegalCase::class);
		$legalCase = $legalCaseEntityRepository->find($id);
		$legalCaseDocuments = $legalCase->getLegalCaseDocuments();
		
		if(null !== $legalCaseDocuments)
		{
			foreach($legalCaseDocuments as $document)
			{
				$entityManager->remove($document);
			}
		}
		$entityManager->remove($legalCase);
      	$entityManager->flush();

      	return $this->redirectToRoute('app_admin_legal_cases');
    }
	
	
	#[Route('/agent/legal/cases/{page}', name: 'app_agent_legal_cases')]
    public function agentLegalCases(Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, $page = 1): Response
    {
		//$this->denyAccessUnlessGranted('ROLE_EDITOR');
		$this->denyAccessUnlessGranted('ROLE_CLIENT_SERVICE_PROVIDER');
		$user = $security->getUser();
		
		$statusChecked = null;
		if ($request->request->has('filtrer')) {
			// Si c'est le bouton Filtrer qui est validé, etatChecked prend la valeur de l'état qui est coché dans le formulaire
			$statusChecked = $request->request->get('legalCaseStatus');
		} else {
			//  Si ce n'est pas le bouton Filtrer qui est validé, on supprime la clé etat de la requête
			$request->request->remove('legalCaseStatus');
		}
		
		$legalCaseEntityRepository = $entityManager->getRepository(LegalCase::class);
    	// build the query for the doctrine paginator
		if(null===$statusChecked)
		{
			$query = $legalCaseEntityRepository->createQueryBuilder('l')
				->andWhere('l.assignedTo = :val')
				->setParameter('val', $user)
				->orderBy('l.id', 'DESC')
           		->getQuery();
		}else
		{
			$query = $legalCaseEntityRepository->createQueryBuilder('l')
				->andWhere('l.assignedTo = :val')
				->andWhere('l.status LIKE :val2')
				->setParameter('val', $user)
				->setParameter('val2', $statusChecked)
				->orderBy('l.id', 'DESC')
           		->getQuery();
		}
		
    	//set page size
    	$pageSize = '10';
    	// load doctrine Paginator
    	$paginator = new Paginator($query);
    	// you can get total items
    	$totalLegalCases = count($paginator);
    	// get total pages
    	$pagesCount = ceil($totalLegalCases / $pageSize);
    	// now get one page's items:
    	$paginator
        ->getQuery()
        ->setFirstResult($pageSize * ($page-1)) // set the offset
        ->setMaxResults($pageSize); // set the limit
		
			$status = [
				'submitted' => Constants::LEGAL_CASE_STATUS_SUBMITTED,
				'categorized' => Constants::LEGAL_CASE_STATUS_CATEGORIZED,
				'inProgress' => Constants::LEGAL_CASE_STATUS_IN_PROGRESS,
				'incomplete' => Constants::LEGAL_CASE_STATUS_INCOMPLETE,
				'assigned' => Constants::LEGAL_CASE_STATUS_ASSIGNED,
				'completed' => Constants::LEGAL_CASE_STATUS_COMPLETED,
				'rejected' => Constants::LEGAL_CASE_STATUS_REJECTED,
			];
		
			return $this->render('legal_case/agent_legal_cases.html.twig', [
			'paginator' => $paginator,
			'totalLegalCases' => $totalLegalCases,
			'pagesCount' => $pagesCount,
			'currentPage' => $page,
			'status' => $status,
			'statusChecked' => $statusChecked,
        	]);
    }
	
	
	#[Route('/agent/legal/case/{id}', name: 'app_agent_legal_case')]
    public function agentLegalCase(PHPMailerService $mailerService, MailContentService $mailContentService, Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, $id = 0): Response
    {
		//$this->denyAccessUnlessGranted('ROLE_EDITOR');
		$this->denyAccessUnlessGranted('ROLE_CLIENT_SERVICE_PROVIDER');
		$user = $security->getUser();
		
		$legalCaseEntityRepository = $entityManager->getRepository(LegalCase::class);
		$legalCase = $legalCaseEntityRepository->findOneBy(['id' => $id,
														   'assignedTo' => $user]);
		
		$actionForm = $this->createForm(AgentLegalCaseActionFormType::class, $legalCase, array(
			'translator'=> $translator,
		));
		

		if(null === $legalCase->getIncompleteMessage())
		{
			$incompleteMessage = new IncompleteMessage;
			$incompleteMessage->setLegalCase($legalCase);
			$legalCase->setIncompleteMessage($incompleteMessage);
		}
		$incompleteForm = $this->createForm(AgentLegalCaseIncompleteFormType::class, $legalCase, array(
			'translator'=> $translator,
		));
		
		if(null === $legalCase->getRejectionMotive())
		{
			$rejectionMotive = new RejectionMotive;
			$rejectionMotive->setLegalCase($legalCase);
			$legalCase->setRejectionMotive($rejectionMotive);
		}
		$rejectionForm = $this->createForm(AgentLegalCaseRejectionFormType::class, $legalCase, array(
			'translator'=> $translator,
		));
		
		
		$actionForm->handleRequest($request);
		$incompleteForm->handleRequest($request);
		$rejectionForm->handleRequest($request);
		
		
        if ($actionForm->isSubmitted() && $actionForm->isValid()) 
		{
			  //$entityManager->flush();
			if ($actionForm->get('accept')->isClicked())
			{
				$legalCase->setStatus(Constants::LEGAL_CASE_STATUS_IN_PROGRESS);
				$entityManager->flush();

				$mailContent = $mailContentService->inProgressLegalCaseClient($legalCase, $legalCase->getFirstName()." ".$legalCase->getLastName(), new \DateTime('@'.strtotime('now')));
				$mailerService->sendMailwithPHPMailer($mailContent['subject'], $mailContent['content'], $legalCase->getContact()->getEmail(), $legalCase->getFirstName()." ".$legalCase->getLastName());
				
				return $this->redirectToRoute('app_agent_legal_cases'); 
    			//echo "Accept";
			}elseif($actionForm->get('reject')->isClicked()) 
			{
				//echo "Reject";
			}elseif($actionForm->get('incomplete')->isClicked())
			{
				//echo "Incomplete";
			}
		}
		
		if ($incompleteForm->isSubmitted() && $incompleteForm->isValid()) 
		{
			if ($incompleteForm->get('send')->isClicked())
			{
				$legalCase->setStatus(Constants::LEGAL_CASE_STATUS_INCOMPLETE);
				$entityManager->flush();

				$mailContent = $mailContentService->incompleteLegalCaseClient($legalCase, $legalCase->getFirstName()." ".$legalCase->getLastName(), new \DateTime('@'.strtotime('now')));
				$mailerService->sendMailwithPHPMailer($mailContent['subject'], $mailContent['content'], $legalCase->getContact()->getEmail(), $legalCase->getFirstName()." ".$legalCase->getLastName());
				
				return $this->redirectToRoute('app_agent_legal_cases'); 
    			//echo "Accept";
			}
		}
		
		if ($rejectionForm->isSubmitted() && $rejectionForm->isValid()) 
		{
			if ($rejectionForm->get('send')->isClicked())
			{
				$legalCase->setStatus(Constants::LEGAL_CASE_STATUS_REJECTED);
				$entityManager->flush();

				$mailContent = $mailContentService->rejectedLegalCaseClient($legalCase, $legalCase->getFirstName()." ".$legalCase->getLastName(), new \DateTime('@'.strtotime('now')));
				$mailerService->sendMailwithPHPMailer($mailContent['subject'], $mailContent['content'], $legalCase->getContact()->getEmail(), $legalCase->getFirstName()." ".$legalCase->getLastName());
				
				return $this->redirectToRoute('app_agent_legal_cases'); 
    			//echo "Accept";
			}
		}
		
		return $this->render('legal_case/agent_legal_case.html.twig', [
			'legalCase' => $legalCase,
			'actionForm' => $actionForm->createView(),
			'incompleteForm' => $incompleteForm->createView(),
			'rejectionForm' => $rejectionForm->createView(),
        ]);
    }
	
	#[Route('/agent/download/legal/case/document/{id}', name: 'app_agent_download_legal_case_document')]
    public function agentDownloadLegalCaseDocument(Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, $id=0): Response
    {
		//$this->denyAccessUnlessGranted('ROLE_EDITOR');
		$this->denyAccessUnlessGranted('ROLE_CLIENT_SERVICE_PROVIDER');
		$user = $security->getUser();
		
		$documentRepository = $entityManager->getRepository(LegalCaseDocument::class);
		$legalCaseDocument = $documentRepository->findOneBy(['id' => $id]);
		
		$full_path = $legalCaseDocument->getPath().'/'.$legalCaseDocument->getFileName();
		
		//$file = $legalCaseDocument->getFile();
		$file = $full_path;
		
		$result;
		$tmp_file_name =  $legalCaseDocument->getFileName();
   		preg_match('/[^ .]*$/', $tmp_file_name, $result);
   		$last_word = $result[0];
		$fileExtension = $last_word;

		if($fileExtension==='pdf')
		{
			if(!file_exists($file))
			{ // file does not exist
				die('file not found');
			} else {
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
    			header("Content-Disposition: attachment; filename=".$legalCaseDocument->getPreferredName());
    			header("Content-Type: application/zip");
    			header("Content-Transfer-Encoding: binary");
    			// read the file from disk
    			readfile($file);
			
				/*
				header('Content-type: application/octet-stream');
				header("Content-Type: ".mime_content_type($file));
				header("Content-Disposition: attachment; filename=".$legalCaseDocument->getPreferredName());
				while (ob_get_level()) 
				{
				ob_end_clean();
				}
				readfile($file);
				*/
			}
		}else
		{
			echo $fileExtension.' is not pdf<br>';
		}
		
		return new Response($full_path);
    }
	
	#[Route('/agent/display/legal/case/document/{id}', name: 'app_agent_display_legal_case_document')]
    public function agentDisplayLegalCaseDocument(Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, $id=0): Response
    {
		$this->denyAccessUnlessGranted('ROLE_CLIENT_SERVICE_PROVIDER');
		$user = $security->getUser();
		
		$documentRepository = $entityManager->getRepository(LegalCaseDocument::class);
		$legalCaseDocument = $documentRepository->findOneBy(['id' => $id]);
		
		$full_path = $legalCaseDocument->getPath().'/'.$legalCaseDocument->getFileName();
		
		//$file = $legalCaseDocument->getFile();
		$file = $full_path;
		
		$result;
		$tmp_file_name =  $legalCaseDocument->getFileName();
   		preg_match('/[^ .]*$/', $tmp_file_name, $result);
   		$last_word = $result[0];
		$fileExtension = $last_word;

		if($fileExtension==='pdf')
		{

			if(!file_exists($file))
			{ // file does not exist
    			die('file not found');
			} else {
			
				//header('Content-type: application/pdf');
				//header('Content-Disposition: inline; filename="' . $legalCaseDocument->getPreferredName() . '"');
				//header('Content-Transfer-Encoding: binary');
				//header('Content-Length: ' . filesize($file));
				//header('Accept-Ranges: bytes');
				//@readfile($file);
			
				// Header content type
				header("Content-type: application/pdf");
  				header("Content-Length: " . filesize($file));
 				// Send the file to the browser.
				readfile($file);
			}
		}else
		{
			echo $fileExtension.' is not pdf<br>';
		}
		
		return new Response($full_path);
    }
	
}
