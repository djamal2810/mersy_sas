<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Fpdf\Fpdf;
use setasign\Fpdi\Fpdi;
use App\Entity\LegalCase;
use App\Entity\LegalCaseDocument;
use App\Entity\FinalReport;
use App\Form\FinalReportFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Security;


class FinalReportController extends AbstractController
{
    #[Route('/final/report/{id<[0-9]+>}', name: 'app_final_report')]
    public function index(Request $request, Security $security, EntityManagerInterface $entityManager, TranslatorInterface $translator, $id=0): Response
    {
		if($id!=0)
		{
			$legalCaseEntityRepository = $entityManager->getRepository(LegalCase::class);
			$legalCase = $legalCaseEntityRepository->findOneBy(['id' => $id]);
			$finalReport = $legalCase->getFinalReport();
			if(!$finalReport)
			{
				$finalReport = new FinalReport;
				//$entityManager->persist($finalReport);
				$finalReport->setLegalCase($legalCase);
				$legalCase->setFinalReport($finalReport);
			}
			
			$form = $this->createForm(FinalReportFormType::class, $finalReport, array(
				'translator'=> $translator,
			));
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid())  
			{
				if ($form->get('save')->isClicked())
				{
					//echo 'save<br>';
					$today = new \DateTime('@'.strtotime('now'));
					
					$finalReport->setDate($today);
					//if($request->request->has('vf'))
					if($form->get('vf')->getData())
					{
						//$finalReport->
						//echo $request->request->get('vf')->getData().'<br>';
					}
					
					//name="legalCaseDocumentPrefferedName_{{legalCaseDocument.id}}"
					//if($form->get('vf')->getData())
					$documents = $legalCase->getLegalCaseDocuments();
					
					foreach($documents as $document)
					{
						$doc_id = $document->getId();
						$input_name = "legalCaseDocumentPrefferedName_".$doc_id;
						//if($form->get($input_name)->getData())
						if($request->request->has($input_name))
						{
							//echo 'hello<br>';
							$new_preferred_name = $request->request->get($input_name);
							$document->setPreferredName($new_preferred_name);
							//echo $new_preferred_name.'<br>';
						}
					}
					
					$entityManager->flush();
					
					
					
					
					$pdf_page_width = 170; //in mm
					$cell_height = 5;
					$A4_max_y = 297;
					$A4_max_x = 210;
					$distance_from_page_bottom = 15;
					$max_top_to_bottom = $A4_max_y-$distance_from_page_bottom;
					$today = $today->format('d-m-Y');
							   
					//ob_start();
					$pdf = new PDF('P','mm','A4');
					$pdf->AliasNbPages();
					//SetAutoPageBreak(boolean auto [, float margin])
					$pdf->SetAutoPageBreak(false);
					$pdf->AddPage();
		
					//$pdf->SetMargins(46, 44, 46);
			
					$pdf->Cell(130, 5, '', 0);
					$pdf->SetFont('Times','B',12);
					$pdf->Cell(10, 5,'Le: ', 1);
					//$pdf->SetX(15);
					$pdf->SetFont('Times','',12);
					$pdf->Cell(30, 5, $today, 1);
								   
					$pdf->Ln(10);					   
					$pdf->SetFont('Times','B',12);
					$pdf->Cell(10,5,'Ref: ', 1);
					//$pdf->SetX(15);
					$pdf->SetFont('Times','',12);
					$pdf->Cell(50,5, $legalCase->getReferenceNo(), 1);
			
					$pdf->SetX(120);
					$pdf->SetFont('Times','B',12);
					$pdf->Cell(10,5,'VF: ', 1);
					//$pdf->SetX(15);
					$pdf->SetFont('Times','',12);
					$pdf->Cell(50,5, '', 1);
			
					$pdf->Ln(10);
					$pdf->SetFont('Times','B',12);
					$pdf->Cell($pdf_page_width,5,utf8_decode('Identité'), 1, 0, 'C');
					
					$pdf->Ln(5);
					$pdf->SetFont('Times','',12);
					$pdf->Cell(15,5,utf8_decode('Nom:'), 1, 0);
					$pdf->Cell(45,5,utf8_decode($legalCase->getFirstName()), 1, 0);
					$pdf->Cell(20,5,utf8_decode('Prénom:'), 1, 0);
					$pdf->Cell(90,5,utf8_decode($legalCase->getLastName()), 1, 0);
					$pdf->Ln(5);
					$pdf->Cell(15,5,utf8_decode('Ville:'), 1, 0);
					$pdf->Cell(35,5,utf8_decode($legalCase->getContact()->getTown()), 1, 0);
					$pdf->Cell(20,5,utf8_decode('Quartier:'), 1, 0);
					$pdf->Cell(35,5,utf8_decode($legalCase->getContact()->getNeighborhood()), 1, 0);
					$pdf->Cell(15,5,utf8_decode('Rue:'), 1, 0);
					$pdf->Cell(50,5,utf8_decode($legalCase->getContact()->getStreet()), 1, 0);
					$pdf->Ln(5);
					$pdf->Cell(10,5,utf8_decode('B/P:'), 1, 0);
					$pdf->Cell(30,5,utf8_decode($legalCase->getContact()->getPostalCode()), 1, 0);
					$pdf->Cell(10,5,utf8_decode('Tel:'), 1, 0);
					$pdf->Cell(40,5,utf8_decode($legalCase->getContact()->getTelephone()), 1, 0);
					$pdf->Cell(15,5,utf8_decode('Email:'), 1, 0);
					$pdf->Cell(65,5,utf8_decode($legalCase->getContact()->getEmail()), 1, 0);
					
					//$pdf->Ln(10);
					//$pdf->SetFont('Times','B',12);
					//$pdf->Cell($pdf_page_width,5,utf8_decode('Objet'), 1, 0, 'C');
					//$pdf->Ln(5);
					//$pdf->SetFont('Times','',12);
					//$subject = $legalCase->getFinalReport()->getSubject();
					//$subject_array = StringHandler::breakTextInLines($subject, 190);
					//$pdf->MultiCell($pdf_page_width, 5, $subject, 1, 'L', "");
					
					$pdf->Ln(10);
					$subject = $legalCase->getFinalReport()->getSubject();
					$subject_array = StringHandler::breakTextInLines($subject, $pdf_page_width);
					$pdf->SetFont('Times','B', 12);
					$pdf->Cell($pdf_page_width,5,utf8_decode('Objet'), 1, 0, 'C');
					$pdf->SetFont('Times','',12);

					foreach($subject_array as $subject)
					{
						$currentX = $pdf->GetX();
						$currentY = $pdf->GetY();
						$height_addition = $currentY+$cell_height;

						if($height_addition > $max_top_to_bottom)
						{
							$pdf->Ln($cell_height);
							$pdf->Cell($pdf_page_width, $cell_height, ' ', 'T', 0, 'L');
							$pdf->AddPage();
							$pdf->SetFont('Times','B',12);
							$pdf->Cell($pdf_page_width,5,utf8_decode('Objet'), 1, 0, 'C');
							$pdf->SetFont('Times','',12);
							$pdf->Ln($cell_height);
							$pdf->Cell($pdf_page_width, $cell_height, utf8_decode($subject), 'L,R,T', 0, 'L');
						}else
						{
							$pdf->Ln($cell_height);
							$pdf->Cell($pdf_page_width, $cell_height, utf8_decode($subject), 'L,R', 0, 'L');
						}
					}
					$pdf->Ln($cell_height);
					$pdf->Cell($pdf_page_width, $cell_height, ' ', 'T', 0, 'L');
					
					
					$pdf->Ln(10);
					$currentX = $pdf->GetX();
					$currentY = $pdf->GetY();
					$height_addition = $currentY+$cell_height;
					if($height_addition > $max_top_to_bottom)
					{
						$pdf->AddPage();
					}
					$pdf->SetFont('Times','B', 12);
					$pdf->Cell($pdf_page_width,5,utf8_decode('Pièces présentées'), 1, 0, 'C');
					$pdf->SetFont('Times','',12);
					$documentCount = 1;

					foreach($legalCase->getLegalCaseDocuments() as $document)
					{
						$currentX = $pdf->GetX();
						$currentY = $pdf->GetY();
						$height_addition = $currentY+$cell_height;

						if($height_addition > $max_top_to_bottom)
						{
							$pdf->Ln($cell_height);
							$pdf->Cell($pdf_page_width, $cell_height, ' ', 'T', 0, 'L');
							$pdf->AddPage();
							$pdf->SetFont('Times','B',12);
							$pdf->Cell($pdf_page_width, $cell_height, utf8_decode('Pièces présentées'), 1, 0, 'C');
							$pdf->SetFont('Times','',12);
							$pdf->Ln($cell_height);
							$pdf->Cell(15,5,utf8_decode(''.$documentCount), 1, 0);
							$pdf->Cell(155,5,utf8_decode($document->getPreferredName()), 1, 0);
						}else
						{
							$pdf->Ln($cell_height);
							$pdf->Cell(30,5,utf8_decode(''.$documentCount), 1, 0);
							$pdf->Cell(140,5,utf8_decode($document->getPreferredName()), 1, 0);
						}
					}
					$pdf->Ln($cell_height);
					$pdf->Cell($pdf_page_width, $cell_height, ' ', 'T', 0, 'L');
					
					
					$pdf->Ln(10);
					$currentX = $pdf->GetX();
					$currentY = $pdf->GetY();
					$height_addition = $currentY+$cell_height;
					if($height_addition > $max_top_to_bottom)
					{
						$pdf->AddPage();
					}
					$legal_analysis = $legalCase->getFinalReport()->getLegalAnalysis();
					$legal_analysis_array = StringHandler::breakTextInLines($legal_analysis, $pdf_page_width);
					$pdf->SetFont('Times','B',12);
					$pdf->Cell($pdf_page_width,5,utf8_decode('Analyse juridique'), 1, 0, 'C');
					$pdf->SetFont('Times','',12);

					foreach($legal_analysis_array as $analysis)
					{
						$currentX = $pdf->GetX();
						$currentY = $pdf->GetY();
						$height_addition = $currentY+$cell_height;

						if($height_addition > $max_top_to_bottom)
						{
							$pdf->Ln($cell_height);
							$pdf->Cell($pdf_page_width, $cell_height, ' ', 'T', 0, 'L');
							$pdf->AddPage();
							$pdf->SetFont('Times','B',12);
							$pdf->Cell($pdf_page_width,5,utf8_decode('Analyse juridique'), 1, 0, 'C');
							$pdf->SetFont('Times','',12);
							$pdf->Ln($cell_height);
							$pdf->Cell($pdf_page_width, $cell_height, utf8_decode($analysis), 'L,R,T', 0, 'L');
						}else
						{
							$pdf->Ln($cell_height);
							$pdf->Cell($pdf_page_width, $cell_height, utf8_decode($analysis), 'L,R', 0, 'L');
						}
					}
					$pdf->Ln($cell_height);
					$pdf->Cell($pdf_page_width, $cell_height, ' ', 'T', 0, 'L');
					
					
					$pdf->Ln(10);
					$currentX = $pdf->GetX();
					$currentY = $pdf->GetY();
					$height_addition = $currentY+$cell_height;
					if($height_addition > $max_top_to_bottom)
					{
						$pdf->AddPage();
					}
					$legal_adive = $legalCase->getFinalReport()->getLegalAdvice();
					$legal_adive_array = StringHandler::breakTextInLines($legal_adive, $pdf_page_width);
					$pdf->SetFont('Times','B',12);
					$pdf->Cell($pdf_page_width,5,utf8_decode('Avis juridique'), 1, 0, 'C');
					$pdf->SetFont('Times','',12);

					foreach($legal_adive_array as $advie)
					{
						$currentX = $pdf->GetX();
						$currentY = $pdf->GetY();
						$height_addition = $currentY+$cell_height;

						if($height_addition > $max_top_to_bottom)
						{
							$pdf->Ln($cell_height);
							$pdf->Cell($pdf_page_width, $cell_height, ' ', 'T', 0, 'L');
							$pdf->AddPage();
							$pdf->SetFont('Times','B',12);
							$pdf->Cell($pdf_page_width,5,utf8_decode('Avis juridique'), 1, 0, 'C');
							$pdf->SetFont('Times','',12);
							$pdf->Ln($cell_height);
							$pdf->Cell($pdf_page_width, $cell_height, utf8_decode($advie), 'L,R,T', 0, 'L');
						}else
						{
							$pdf->Ln($cell_height);
							$pdf->Cell($pdf_page_width, $cell_height, utf8_decode($advie), 'L,R', 0, 'L');
						}
					}
					$pdf->Ln($cell_height);
					$pdf->Cell($pdf_page_width, $cell_height, ' ', 'T', 0, 'L');
					
					$pdf->Ln(4*$cell_height);
					$expected_height_of_signatre_section = 6*$cell_height;
					$currentX = $pdf->GetX();
					$currentY = $pdf->GetY();
					$height_addition = $currentY+$expected_height_of_signatre_section;
					if($height_addition > $max_top_to_bottom)
					{
						$pdf->AddPage();
					}
					
					$pdf->Cell($pdf_page_width/2, $cell_height, utf8_decode('Signature de consultant'), '', 0, 'L');
					$pdf->Cell($pdf_page_width/2, $cell_height, utf8_decode("Signature de l'administrateur"), '', 0, 'R');
					$pdf->Ln(3*$cell_height);
					$currentX = $pdf->GetX();
					$currentY = $pdf->GetY();
					$pdf->Ln($cell_height);
					$pdf->Cell($pdf_page_width/2, $cell_height, utf8_decode($legalCase->getAssignedTo()->getUserDetails()->getFirstName().' '.$legalCase->getAssignedTo()->getUserDetails()->getLastName()), '', 0, 'L');
					$pdf->Cell($pdf_page_width/2, $cell_height, utf8_decode($legalCase->getAssignedBy()->getUserDetails()->getFirstName().' '.$legalCase->getAssignedBy()->getUserDetails()->getLastName()), '', 0, 'R');
					//$pdf->SetFont('Times','',12);
					//$pdf->Cell($pdf_page_width,5,utf8_decode('Hello'), 'L,R', 0, 'L');
					
					
					//$pdf->Output();
					//ob_end_flush();
					//$pdf->Output(str_replace("__NO__",$templateNo,$templatesDocPath),'F');
					//$pdf->Output('test.pdf','I');
					$pdf->Output('test.pdf','F');
			
				}
			}
			
		}
		
		
        return $this->render('final_report/final_report.html.twig', [
            'form' => $form->createView(),
			'finalReport' => $finalReport,
        ]);
    }
	
	
	

}

class StringHandler
{
	public static function breakTextInLines($text, $max_width): array
	{
		//$orignal_lines_array = preg_split("/\r\n|\n|\r/", $text);
		$orignal_lines_array = explode("\n", $text);
		$result = [];
		foreach($orignal_lines_array as $orignal_line)
		{
			if(StringHandler::stringWidthInMillimeters($orignal_line)<$max_width )
			{
				$result[] = $orignal_line;
				//continue;
			}else
			{
				$orignal_line_copy = $orignal_line;
				$tok = strtok($orignal_line_copy, " \t");
				$new_line = '';
				while ($tok !== false) 
				{
					$new_line .= $tok.' ';
					
    				$tok = strtok(" \n\t");
					if($tok !== false)
					{
						if(StringHandler::stringWidthInMillimeters($new_line.$tok.'')>$max_width )
						{
							$result[] = $new_line;
							$new_line = '';
						}
					}else
					{
						break;
					}
				}
				$result[] = $new_line;
			}
		}
		
		return $result;
	}

	public static function stringWidthInMillimeters($text)
	{
		$fontSize = 12; //FONT SIZE
    	$fontFile = $font = dirname(__FILE__) . '/TIMES.ttf';; // PUT THE RELEVANT FONT FILE IN THE SAME DIRECTORY AS THIS SCRIPT
    	$boundingBox = imagettfbbox($fontSize, 0, $fontFile, $text); //RETRIEVE THE BOUNDING BOX OF THE TEXT RENDERED
    	$width_in_pixels = $boundingBox[2] - $boundingBox[0]; //WIDTH IN PIXELS AT 72dpi
		$mmWidth = $width_in_pixels * 0.2645833333;	
		
		return $mmWidth;
	}
}

class PDF extends FPDF
{
	public function __construct($page_orientation, $unit, $paper_format)
	{
		parent::__construct($page_orientation, $unit, $paper_format);
	}
		
	// Page header
	function Header()
	{
		// Left Logo
    	$this->Image('build/custom/images/mersysaslogoopac.png',10,0,30);
    	// Move to the right
		$this->SetX(30);
    	//$this->Cell(31);
			
		// Arial bold 20
    	$this->SetFont('Arial','B',20);
		// Colors
		$this->SetTextColor(0,0,75);
    	// Title
    	$this->Cell(149, 5,'MERSY S.A.S', 0,0,'C');
		
		$this->Ln(5);
		$this->SetX(30);
		// Arial bold 12
    	$this->SetFont('Arial','', 10);
		// Colors
		$this->SetTextColor(0,0,255);
    	// Title
    	$this->Cell(149, 5, utf8_decode('Société par Actions simplifiées, au capital social de 1.000.000 F CFA'), 0,0,'C');
			
		$this->Ln(4);
		$this->SetX(30);
		// Arial bold 12
    	$this->SetFont('Arial','', 10);
		// Colors
		$this->SetTextColor(0,0,255);
    	// Title
    	$this->Cell(149, 5, utf8_decode('RCCM de N’DJAMENA n°TD-NDJ-01-2022-B16-00003 ; NIF :  9033884'), 0,0,'C');
			
		$this->Ln(4);
		$this->SetX(30);
		// Arial bold 12
    	$this->SetFont('Arial','', 10);
		// Colors
		$this->SetTextColor(0,0,255);
    	// Title
    	$this->Cell(149, 5, utf8_decode('CONSEIL JURIDIQUE - INGERNERIE FINANCIERE - FISCALITE - RH'), 0,0,'C');
			
		// Right Logo
    	$this->Image('build/custom/images/mersysaslogoopac.png',170,0,30);
			
		$this->SetLineWidth(1); //1mm
		$this->SetDrawColor(0,0,255);
		$this->Line(0, 30, 210, 30); 
			
    	// Line break
    	$this->Ln(20);
	}
	
	// Page footer
	function Footer()
	{
 		//$this->SetY(-20);
 		//$this->Image('images/pdf-footer.jpg');
		// Position at 1.5 cm from bottom
    	$this->SetY(-15);
    	// Arial italic 8
		$this->SetFont('Arial','I',8);
    	// Page number
    	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
