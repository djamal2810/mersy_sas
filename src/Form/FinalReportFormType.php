<?php

namespace App\Form;

use App\Entity\FinalReport;
use App\Entity\LegalCase;
use App\Form\ContactType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FinalReportFormType extends AbstractType
{

    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
    	$builder
			->add('referenceNo', TextType::class, [
                 	'label' => "No. de Reference",
					'property_path' => 'legalCase.referenceNo',
					'disabled' => true,
                    ])
			->add('vf', TextType::class, [
                    'label' => "VF",
					'mapped' => false,
					'required' => false,
                    ])
        	->add('firstName', TextType::class, [
               		'label' => "Nom:",
					'property_path' => 'legalCase.firstName',
					'disabled' => true,
                    ])
         	->add('lastName', TextType::class, [
                   	'label' => "Prénom",
					'property_path' => 'legalCase.lastName',
					'disabled' => true,
                    ])
           	->add('contact', ContactType::class, [
                  	'label' => false,
					'property_path' => 'legalCase.contact',
					'disabled' => true,
                    ])
			->add('subject', TextareaType::class, [
                        'label' => "Objet",
						'required' => false,
                    ])
			->add('legalAnalysis', TextareaType::class, [
                        'label' => "Analyse juridique",
						'required' => false,
                    ])
			->add('legalAdvice', TextareaType::class, [
                        'label' => "Avis juridique",
						'required' => false,
                    ])
			->add('save', SubmitType::class, [
					'label' => $options['translator']->trans('Sauvegardez'),
					'attr' => ['class' => 'btn btn-primary',
							  'style' => 'margin-top:1em;margin-bttom:1em',],
			])
			;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FinalReport::class,
			'translator' => null,
        ]);
    }

}
