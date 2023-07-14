<?php

namespace App\Form;

use App\Entity\Announcement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;


class AddNewsLettreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title', TextType::class, [
                'label' => "Titre",
            ])
			->add('abstract', TextareaType::class, [
                'label' => 'Extrait',
				//'mapped' => false,
				'attr' => ['col' => 100, 'row' => 7],
            ])
			//->add('content', TextareaType::class, [
            //    'label' => 'Details',
           // ])
			->add('content', CKEditorType::class, [
                'label' => 'Détails',
            ])
			->add('announcementPoster', AnnouncementPosterType::class, [
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
			->add('publish', SubmitType::class, [
					'label' => $options['translator']->trans('Publiez'),
					'attr' => ['class' => 'btn btn-primary'],
			])
        ;	
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Announcement::class,
			'translator' => null,
        ]);
    }
}
