<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\LegalCaseCategory;
use App\Constants\Constants;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
		$legalCaseCategories = $entityManager->getRepository(LegalCaseCategory::class)->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
           	->getQuery()
           	->getResult();
		
		$tramslatedLegalCaseCategories = [];
		
		foreach($legalCaseCategories as $legalCaseCategory)
		//for($i=0; $i<count($legalCaseCategories); $i++)
		{
			$tramslatedLegalCaseCategories[] = $translator->trans($legalCaseCategory->getCategory());
		}
		
        return $this->render('home/home.html.twig', [
			'legalCaseCetegories' => $tramslatedLegalCaseCategories,
        ]);
    }
}
