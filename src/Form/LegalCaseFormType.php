<?php

namespace App\Form;


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
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class LegalCaseFormType extends AbstractType
{

    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        switch ($options['flow_step']) {

           case 1:
                $builder
                    ->add('firstName', TextType::class, [
                       'label' => "Nom:",
                    ])
                    ->add('lastName', TextType::class, [
                        'label' => "Prénom",
                        //'required' => false,
                    ])
                    ->add('contact', ContactType::class, [
                        'label' => false,
						//'mapped' => false,
                    ]);
            	break;
				
            case 2:
                $builder
                	->add('casePresentation', CKEditorType::class, [
                        'label' => "Présentation du dossier",
                        'required' => false,
                    ]);
                break;
				
			case 3:
                $builder
				 	->add('legalCaseDocuments', CollectionType::class, [
                        'label' => false,
                        'entry_type' => LegalCaseDocumentType::class,
						//'mapped' => false,
						//'property_path' => 'legalCaseDocuments',
                        'entry_options' => [
                            'label' => false
                        ],
                        'allow_add' => true,
                        'allow_delete' => true,
                        'by_reference' => false
                    ]);
                break;

			case 4:
                $builder
					->add('expectations', CKEditorType::class, [
                        'label' => "Les attentes",
                        'required' => false,
                    ]);
                break;
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LegalCase::class,
        ]);
    }

}
