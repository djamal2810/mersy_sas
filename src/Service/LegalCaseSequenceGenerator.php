<?php

namespace App\Service;

use App\Entity\LegalCase;

class LegalCaseSequenceGenerator
{
	public function nextReferenceNo(LegalCase $lastEntity=null) : string
	{
		//$nextInt = $this->generator->getNext('ordernumber', 1);
		//return sprintf("MRS-SAS-%'.09d\n", $nextInt);
		
		$reference = null;
        if($lastEntity)
        {
            $maxId = $lastEntity->getId();
            $maxId++;
        }else 
        {
            $maxId = 1;
        }

        $reference = sprintf("MRS-SAS-%'.09d", $maxId);
        return  $reference;
	}
}
