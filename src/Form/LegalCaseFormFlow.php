<?php

namespace App\Form;

use Craue\FormFlowBundle\Form\FormFlow;

class LegalCaseFormFlow extends FormFlow 
{

    protected function loadStepsConfig(): array
    {
        $legalCaseType = LegalCaseFormType::class;
        return [
            [
                'label' => 'Renseignements rélatifs au représentant',
                'form_type' => $legalCaseType,
            ],
            [
                'label' => "Renseignements rélatifs au dossier",
                'form_type' => $legalCaseType,
            ],
            [
                'label' => "Documents rélatifs au dossier",
                'form_type' => $legalCaseType,
            ],
            [
                'label' => "Les attentes du client",
                'form_type' => $legalCaseType,
            ],
        ];
    }

    /**
	 * {@inheritDoc}
	 */
	public function getFormOptions($step, array $options = []) {
		$options = parent::getFormOptions($step, $options);

		return $options;
	}

}