<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Entity\Meeting;
use App\Entity\UserActivity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Security;
use App\Service\PHPMailerService;
use App\Service\MailContentService;
use App\Entity\LegalCaseCategory;
use App\Constants\Constants;


class ArrangeMeetingController extends AbstractController
{
    #[Route('/arrange/meeting', name: 'app_arrange_meeting')]
    public function index(PHPMailerService $mailerService, MailContentService $mailContentService, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
		if ($request->request->has('arrangeMeeting')) 
		{
			
			$name = $request->request->get('name');
			$email = $request->request->get('email');
			$service_index = (int)$request->request->get('service');
			$day = $request->request->get('date');
			$time = $request->request->get('time');
			$input = $day.' '.$time;
			$date = strtotime($input);
			//echo date('D/M/Y h:i:s', $date).'<br>';
			//new \DateTime('@'.strtotime('now')
			$datetime = new \DateTime($input);
			//echo date_format($datetime, 'Y-m-d H:i:s').'<br>';
			
			$legalCaseCategory= $entityManager->getRepository(LegalCaseCategory::class)->findOneBy(['id' => $service_index]);
			
			$meeting = new Meeting;
			$meeting->setName($name);
			$meeting->setEmail($email);
    		$meeting->setService($legalCaseCategory->getCategory());
    		$meeting->setDate($datetime);
			
			$entityManager->persist($meeting);
			$entityManager->flush();
			$this->addFlash("notice", $translator->trans('Votre demande est soumise! Verifiez votre email pour confirmer.'));
			//echo date_format($meeting->getDate(), 'Y-m-d H:i:s').'<br>';
			//$this->addFlash("notice", $translator->trans('Votre demande est soumise! Verifiez votre email pour confirmer.'). ' ' .date_format($meeting->getDate(), 'Y-m-d H:i:s'));
            return $this->redirectToRoute('app_home'); // redirect when done
		
		}
		
        return $this->redirectToRoute('app_home'); // redirect when done
    }
	
	#[Route('/admin/meeting/scheduling/{page}', name: 'app_admin_meeting_scheduling')]
    public function adminMeetingScheduling(PHPMailerService $mailerService, MailContentService $mailContentService, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, Security $security, $page = 1): Response
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		
		$meetingsQuery = $entityManager->getRepository(Meeting::class)->createQueryBuilder('m')
					->orderBy('m.id', 'DESC')
           			->getQuery();
    	// build the query for the doctrine paginator
		//set page size
    	$pageSize = '10';
    	// load doctrine Paginator
    	$paginator = new Paginator($meetingsQuery);
    	// you can get total items
    	$totalMeetings = count($paginator);
    	// get total pages
    	$pagesCount = ceil($totalMeetings / $pageSize);
    	// now get one page's items:
    	$paginator
        	->getQuery()
        	->setFirstResult($pageSize * ($page-1)) // set the offset
			->setMaxResults($pageSize); // set the limit
		
		$arrayOfAgents = $entityManager->getRepository(User::class)->createQueryBuilder('u')
			->where('u.roles LIKE :roles')
        	->setParameter('roles', '%"'.Constants::ROLE_CLIENT_SERVICE_PROVIDER.'"%')
			->orderBy('u.id', 'ASC')
           	->getQuery()
           	->getResult();
		
        return $this->render('meeting/admin_meeting_scheduling.html.twig', [
			'totalMeetings' => $totalMeetings,
			'pagesCount' => $pagesCount,
			'currentPage' => $page,
			'paginator' => $paginator,
			'arrayOfAgents' => $arrayOfAgents,
        	]);
    }
	
	#[Route('/admin/meeting/Assignment/action/{id}', name: 'app_admin_meeting_assignment_action')]
    public function meetingAssignmentAction(PHPMailerService $mailerService, MailContentService $mailContentService, Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, $id = 1): Response
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		$user = $security->getUser();
		
		//$this->denyAccessUnlessGranted('ROLE_EDITOR');
		if ($request->request->has('assignAgent')) 
		{
			//echo "Hello ".$id."<br>";
			
			$selectedAgentName = $request->request->get('meeting_agent');
			
			if($selectedAgentName !== 'Consultant')
			{
			
				//$agentUserName = strtok($selectedAgentName, " ");
				$agentUserName = explode(' ', trim($selectedAgentName))[0]; //First word
				$meeting = $entityManager->getRepository(Meeting::class)->findOneBy(['id' => $id]);
				//echo $agentUserName.$id."<br>";
				$agentRepository = $entityManager->getRepository(User::class);
				$agentEntity = $agentRepository->findOneBy([
    				'username' => $agentUserName,
				]);
				
				$oldAssignedAgentActivity = $meeting->getAssignedTo();
				if(null!==$oldAssignedAgentActivity)
				{
					$oldAssignedAgentActivity->removeAssignedToMeeting($meeting);
				}
				
				$oldAssigningAdminActivity = $meeting->getAssignedBy();
				if(null!==$oldAssigningAdminActivity)
				{
					$oldAssigningAdminActivity->removeAssignedByMeeting($meeting);
				}
				
				$assigningAdminActivity = $user->getUserActivity();
				if(!$assigningAdminActivity)
				{
					$assigningAdminActivity = new UserActivity();
					$assigningAdminActivity->setUser($user);
					$user->setUserActivity($assigningAdminActivity);
					$entityManager->persist($assigningAdminActivity);
				}
				
				$assignedAgentActivity = $agentEntity->getUserActivity();
				if(!$assignedAgentActivity)
				{
					$assignedAgentActivity = new UserActivity();
					$assignedAgentActivity->setUser($agentEntity);
					$agentEntity->setUserActivity($assignedAgentActivity);
					$entityManager->persist($assignedAgentActivity);
				}
				
				//Asign the current legal case to the chosen agent
				$meeting->setAssignedTo($assignedAgentActivity);
				$meeting->setAssignmentDate(new \DateTime('@'.strtotime('now')));
				//Set the admin who issued the assignment
				$meeting->setAssignedBy($assigningAdminActivity);
						
				//Update the database
				$entityManager->flush();
									
				$mailContent = $mailContentService->meetingSuggestionToConsultant($meeting);
				$mailerService->sendMeetingSuggestionToConsultantMail($mailContent['subject'], $mailContent['content'], $assignedAgentActivity->getUser()->getUserDetails()->getEmail());

				//Redirect to the list of legal cases
				return $this->redirectToRoute('app_admin_meeting_scheduling');
			}
		}
		return $this->redirectToRoute('app_admin_meeting_scheduling');
	}
}
