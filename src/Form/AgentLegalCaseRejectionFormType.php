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

class AgentLegalCaseRejectionFormType extends AbstractType
{

    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
             ->add('motive', CKEditorType::class, [
                'label' => $options['translator']->trans('Motifs'),
				'property_path' => 'rejectionMotive.motive',
            ])
			->add('send', SubmitType::class, 
				 [
					'label' => $options['translator']->trans('Envoyez'),
					'attr' => ['class' => 'btn btn-primary'],
				],)
			->add('cancel', SubmitType::class, 
				 [
					'label' => $options['translator']->trans('Annulez'),
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
