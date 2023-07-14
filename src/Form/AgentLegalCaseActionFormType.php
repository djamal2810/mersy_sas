<?php

namespace App\Form;


use App\Entity\LegalCase;
use App\Form\ContactType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class AgentLegalCaseActionFormType extends AbstractType
{

    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
             ->add('accept', SubmitType::class, 
				 [
					'label' => $options['translator']->trans('Acceptez'),
					'attr' => ['class' => 'btn btn-primary'],
				],)
			->add('reject', SubmitType::class, 
				 [
					'label' => $options['translator']->trans('Rejetez'),
					'attr' => ['class' => 'btn btn-danger'],
				],)
			->add('incomplete', SubmitType::class, 
				 [
					'label' => $options['translator']->trans('Completez'),
					'attr' => ['class' => 'btn btn-warning'],
				],)
        ;	
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LegalCase::class,
			'translator' => null,
        ]);
    }

}
