<?php

namespace App\Form;

use App\Entity\LegalCaseDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\File;

class LegalCaseDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		$builder
            ->add('preferredName', HiddenType::class, [
                'label' => false,
                'required' => false,
				'attr' => ['id' => 'documentPreferredName'],
				//'data' => 'preferredName'
            ])
			->add('path', HiddenType::class, [
                'label' => false,
                'required' => false,
            ])
			->add('file', VichImageType::class, [
               'label' => false,
                //'mapped' => false,
				'required' => false,
                'download_uri' => false,
				'image_uri'         => false,
				'allow_delete' => false,
				'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
							'application/pdf',
							'application/x-pdf',
                        ]
                    ])
                ],
                //'image_uri' => true,
				//'download_label' => "Télécharger",
				//'disabled' => true,
            ])
			//->add('file', FileType::class, [
             //  'label' => false,
             //   'mapped' => false,
			//	'required' => false,
           // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LegalCaseDocument::class,
        ]);
    }
}
