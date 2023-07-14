<?php 

namespace App\Service;

use App\Entity\User;
use PHPMailer\PHPMailer\PHPMailer;
use App\Entity\LegalCase;

class MailContentService
{
	public function submittedLegalCaseClient(LegalCase $legalCase, string $full_name, \DateTime $date): array
    {
        $subject = utf8_decode("Dossier soumis");
        $content = "Salut M./Mme/Mlle <b>" . $full_name . "</b><br> Votre dossier a été soumis au systeme de gestion de Mersy SAS avec le numéro de référence <b>" . $legalCase->getReferenceNo() . "</b> ce jour, le" .$date->format('d-m-Y').". Le dossier est en phase d'étude préliminaire avant son attribution à l'expert approprié.";

        return ['subject' => $subject, 'content' => $content];
    }
	
	public function assignedLegalCaseClient(LegalCase $legalCase, string $full_name, \DateTime $date): array
    {
        $subject = utf8_decode("Dossier attribué");
        $content = "Salut M./Mme/Mlle <b>" . $full_name . "</b><br> Votre dossier, de numéro de référence <b>". $legalCase->getReferenceNo() ."</b> est attribué à: <br><b>" . 
		$legalCase->getAssignedTo()->getUserDetails()->getFirstName(). " " . $legalCase->getAssignedTo()->getUserDetails()->getLastName(). "</b><br>".
		"Tel: ". $legalCase->getAssignedTo()->getUserDetails()->getTelephone(). "<br>".
		"Email: ". $legalCase->getAssignedTo()->getUserDetails()->getEmail(). "<br>".
		"Le " .$legalCase->getAssignmentDate()->format('d-m-Y');

        return ['subject' => $subject, 'content' => $content];
    }
	
	public function inProgressLegalCaseClient(LegalCase $legalCase, string $full_name, \DateTime $date): array
    {
        $subject = utf8_decode("Dossier en cours de traitement");
        $content = "Salut M./Mme/Mlle <b>" . $full_name . "</b><br> Votre dossier, de numéro de référence <b>". $legalCase->getReferenceNo() ."</b> est en cours de traitement avec: <br><b>" . 
		$legalCase->getAssignedTo()->getUserDetails()->getFirstName(). " " . $legalCase->getAssignedTo()->getUserDetails()->getLastName(). "</b><br>".
		"Tel: ". $legalCase->getAssignedTo()->getUserDetails()->getTelephone(). "<br>".
		"Email: ". $legalCase->getAssignedTo()->getUserDetails()->getEmail(). "<br>".
		"Date: " .$date->format('d-m-Y');

        return ['subject' => $subject, 'content' => $content];
    }
	
	public function incompleteLegalCaseClient(LegalCase $legalCase, string $full_name, \DateTime $date): array
    {
        $subject = utf8_decode("Dossier incomplet");
        $content = "Salut M./Mme/Mlle <b>" . $full_name . "</b><br> Le dévélopement de Votre dossier, de numéro de référence <b>". $legalCase->getReferenceNo() ."</b> nécessite d'information ou des documents additionels: <br>" 
		. $legalCase->getIncompleteMessage()->getMessage(). "<br>Pour plus de détail, contactez <br><b>".$legalCase->getAssignedTo()->getUserDetails()->getFirstName(). " " . $legalCase->getAssignedTo()->getUserDetails()->getLastName()."</b><br>".
		"Tel: ". $legalCase->getAssignedTo()->getUserDetails()->getTelephone(). "<br>".
		"Email: ". $legalCase->getAssignedTo()->getUserDetails()->getEmail(). "<br>".
		"Date: " .$date->format('d-m-Y');

        return ['subject' => $subject, 'content' => $content];
    }
	
	public function rejectedLegalCaseClient(LegalCase $legalCase, string $full_name, \DateTime $date): array
    {
        $subject = utf8_decode("Dossier abandonné");
        $content = "Salut M./Mme/Mlle <b>" . $full_name . "</b><br> Votre dossier, de numéro de référence <b>". $legalCase->getReferenceNo() ."</b> ne peut pas être traité par l'expert à qui il est courramment attribué pour motifs: <br>" 
		. $legalCase->getRejectionMotive()->getMotive(). "<br>Par conséquent, le dossier sera attribué à un autre expert.<br>".
		"Date: " .$date->format('d-m-Y');

        return ['subject' => $subject, 'content' => $content];
    }
	
	public function assignedLegalCaseConsultant(LegalCase $legalCase, string $full_name, \DateTime $date): array
    {
        $subject = utf8_decode("Dossier attribué");
        $content = "Salut! <br> Le de dossier de M./Mme/Mlle <b>" . $full_name . "</b> (numéro de référence <b>". $legalCase->getReferenceNo() ."</b> ) est en attente de traitement de votre part<br><b>. Procedez à votre compte de MERSY-SAS pour y accéder<br><br>". 
		"Par ".$legalCase->getAssignedBy()->getUserDetails()->getFirstName(). " " . $legalCase->getAssignedBy()->getUserDetails()->getLastName(). "<br>".
		"Le " .$legalCase->getAssignmentDate()->format('d-m-Y')."<br>";

        return ['subject' => $subject, 'content' => $content];
    }
	
	public function assignedLegalCaseClientAndConsultant(LegalCase $legalCase, \DateTime $date): array
    {
        $subject = utf8_decode("Dossier attribué");
        $content = "Salut M./Mme/Mlle <b>" . $legalCase->getFirstName(). " " . $legalCase->getLastName() . "</b><br> Votre dossier, de numéro de référence <b>". $legalCase->getReferenceNo() ."</b> est attribué à: <br><b>" . 
		$legalCase->getAssignedTo()->getUserDetails()->getFirstName(). " " . $legalCase->getAssignedTo()->getUserDetails()->getLastName(). "</b><br>".
		"Tel: ". $legalCase->getAssignedTo()->getUserDetails()->getTelephone(). "<br>".
		"Email: ". $legalCase->getAssignedTo()->getUserDetails()->getEmail(). "<br>".
		"Le " .$legalCase->getAssignmentDate()->format('d-m-Y');

        return ['subject' => $subject, 'content' => $content];
	}
	
	
}