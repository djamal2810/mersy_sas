<?php

namespace App\Form;

use App\Entity\OurPartner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class OurPartnerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('description', TextareaType::class, [
                'label' => $options['translator']->trans('Description du partenaire'),
            ])
			->add('ourPartnerLogo', OurPartnerLogoType::class, [
                        'label' => false,
                        //'entry_type' => LegalCaseDocumentType::class,
						//'property_path' => 'legalCaseDocuments',
                        //'entry_options' => [
                         //   'label' => false
                       // ],
                        //'allow_add' => true,
                        //'allow_delete' => true,
                        'by_reference' => false
                    ])
			->add('submit', SubmitType::class, [
					'label' => $options['translator']->trans('Envoyez'),
					'attr' => ['class' => 'btn btn-primary'],
			])
        ;	
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OurPartner::class,
			'translator' => null,
        ]);
    }
}
