<?php 

namespace App\Service;

use App\Entity\User;
use PHPMailer\PHPMailer\PHPMailer;

class PHPMailerService
{
    ////////// FONCTION QUI ENVOIE LES MAILS ////////////
	
	public function sendMailwithPHPMailer(string $subject, string $content, string $email, string $full_name): bool
	{
		$result = 'hi';

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Mailer = "smtp";
		$mail->SMTPDebug  = 0;
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "tls";

		//$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$mail->Host = 'smtp.mersy-td.com';
		$mail->Port = 587;

		$mail->SMTPAutoTLS = false;
		$mail->SMTPOptions = [
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			]
		];

		$mail->Username   = "djamal.p@dser-td.com";
		$mail->Password   = "Dser@000013";
		$mail->IsHTML(true);

		/////Expéditeur ////
		$mail->SetFrom("djamal.p@dser-td.com", "MERSY-SAS");

		//// Destinataire /////
		$mail->AddAddress($email);
		//$mail->AddAddress($email, utf8_decode($full_name) );
		//$mail->AddAddress("djamal2810@gmail.com", "Djamalladine Mahamat Pierre");
		//$mail->AddAddress("cheriepassy2011@gmail.com", "Ulrich Roméo Ngniaba");


		$mail->Subject = $subject;
		//$content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";
		//$content = "<b>Bonjour This is a mail notification from DSER-AFRICA</b>";

		$mail->MsgHTML($content);
		if (!$mail->Send()) {
			$result =  false;
			// $result =  "Error while sending Email.";

			//var_dump($mail);
		} else {
			$result =  true;
			// $result =  "Email sent successfully";
		}

		//$result = "Email Sent";
		return $result;
	}
	
	public function sendMailwithPHPMailer2(string $subject, string $content, string $email1, string $email2): bool
	{
		$result = 'hi';

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Mailer = "smtp";
		$mail->SMTPDebug  = 0;
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "tls";

		//$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$mail->Host = 'smtp.mersy-td.com';
		$mail->Port = 587;

		$mail->SMTPAutoTLS = false;
		$mail->SMTPOptions = [
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			]
		];

		$mail->Username   = "djamal.p@dser-td.com";
		$mail->Password   = "Dser@000013";
		$mail->IsHTML(true);

		/////Expéditeur ////
		$mail->SetFrom("djamal.p@dser-td.com", "MERSY-SAS");

		//// Destinataire /////
		$mail->AddAddress($email1);
		$mail->AddAddress($email2);
		//$mail->AddAddress($email, utf8_decode($full_name) );
		//$mail->AddAddress("djamal2810@gmail.com", "Djamalladine Mahamat Pierre");
		//$mail->AddAddress("cheriepassy2011@gmail.com", "Ulrich Roméo Ngniaba");


		$mail->Subject = $subject;
		//$content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";
		//$content = "<b>Bonjour This is a mail notification from DSER-AFRICA</b>";

		$mail->MsgHTML($content);
		if (!$mail->Send()) {
			$result =  false;
			// $result =  "Error while sending Email.";

			//var_dump($mail);
		} else {
			$result =  true;
			// $result =  "Email sent successfully";
		}

		return $result;
	}
	
	
	
	public function sendNewsletterConfirmationMail(string $subject, string $content, string $email): bool
	{
		$result = 'hi';

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Mailer = "smtp";
		$mail->SMTPDebug  = 1;
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "tls";

		//$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$mail->Host = 'smtp.mersy-td.com';
		$mail->Port = 587;

		$mail->SMTPAutoTLS = false;
		$mail->SMTPOptions = [
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			]
		];

		$mail->Username   = "djamal.p@dser-td.com";
		$mail->Password   = "Dser@000013";
		$mail->IsHTML(true);

		/////Expéditeur ////
		$mail->SetFrom("djamal.p@dser-td.com", "MERSY-SAS");

		//// Destinataire /////
		$mail->AddAddress($email);
		//$mail->AddAddress($email, utf8_decode($full_name) );
		//$mail->AddAddress("djamal2810@gmail.com", "Djamalladine Mahamat Pierre");
		//$mail->AddAddress("cheriepassy2011@gmail.com", "Ulrich Roméo Ngniaba");


		$mail->Subject = $subject;
		//$content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";
		//$content = "<b>Bonjour This is a mail notification from DSER-AFRICA</b>";

		$mail->MsgHTML($content);
		if (!$mail->Send()) {
			$result =  false;
			// $result =  "Error while sending Email.";

			//var_dump($mail);
		} else {
			$result =  true;
			// $result =  "Email sent successfully";
		}

		//$result = "Email Sent";
		return $result;
	}
	
	public function sendMeetingSuggestionToConsultantMail(string $subject, string $content, string $email): bool
	{
		$result = 'hi';

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Mailer = "smtp";
		$mail->SMTPDebug  = 1;
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "tls";

		//$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$mail->Host = 'smtp.mersy-td.com';
		$mail->Port = 587;

		$mail->SMTPAutoTLS = false;
		$mail->SMTPOptions = [
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			]
		];

		$mail->Username   = "djamal.p@dser-td.com";
		$mail->Password   = "Dser@000013";
		$mail->IsHTML(true);

		/////Expéditeur ////
		$mail->SetFrom("djamal.p@dser-td.com", "MERSY-SAS");

		//// Destinataire /////
		$mail->AddAddress($email);
		//$mail->AddAddress($email, utf8_decode($full_name) );
		//$mail->AddAddress("djamal2810@gmail.com", "Djamalladine Mahamat Pierre");
		//$mail->AddAddress("cheriepassy2011@gmail.com", "Ulrich Roméo Ngniaba");


		$mail->Subject = $subject;
		//$content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";
		//$content = "<b>Bonjour This is a mail notification from DSER-AFRICA</b>";

		$mail->MsgHTML($content);
		if (!$mail->Send()) {
			$result =  false;
			// $result =  "Error while sending Email.";

			//var_dump($mail);
		} else {
			$result =  true;
			// $result =  "Email sent successfully";
		}

		//$result = "Email Sent";
		return $result;
	}
}