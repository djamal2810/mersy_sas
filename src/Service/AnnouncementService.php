<?php

namespace App\Service;

use App\Entity\Announcement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AnnouncementService
{
	public function annoucementsHTMLContent(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, $page = 1) : array
	{
		$result = [];
		$AnnoucementRepository = $entityManager->getRepository(Announcement::class);
		
		// build the query for the doctrine paginator
    	$query = $AnnoucementRepository->createQueryBuilder('a')
              ->orderBy('a.id', 'DESC')
              ->getQuery();
    	//set page size
    	$pageSize = '3';
    	// load doctrine Paginator
    	$paginator = new Paginator($query);
    	// you can get total items
    	$totalAnnoucements = count($paginator);
    	// get total pages
    	$pagesCount = ceil($totalAnnoucements / $pageSize);
    	// now get one page's items:
    	$paginator
        ->getQuery()
        ->setFirstResult($pageSize * ($page-1)) // set the offset
        ->setMaxResults($pageSize); // set the limit
		foreach($paginator as $annoucement)
		{
			$title = $annoucement->getTitle();
			$abstract = $annoucement->getAbstract();
			$content = $annoucement->getContent();
			$poster = $annoucement->getPoster();
			
			$announcementStr = "<div>";
			$announcementStr += "<h3>"+$title+"</h3>";
			$announcementStr += "</div>";
			
			$announcementStr += "<div>";
			//$announcementStr += "<img src='{{ asset(\'uploads/admin_uploads/images/mersysaslogoopac.png\') }}' alt=''>";
			$announcementStr += "<img src='{{ asset(";
			$announcementStr += "\'"+$poster+"\') }}' alt=''>";
			$announcementStr += "</img>";
			$announcementStr += "</div>";
			
			$announcementStr = "<div>";
			$announcementStr += "<h3>"+$abstract+"</h3>";
			$announcementStr += "</div>";
			
			$result[] = $announcementStr;
			
		}
		
		return result;
	}
}
