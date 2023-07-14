<?php

namespace App\Form;

use App\Entity\AnnouncementPoster;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
//use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AnnouncementPosterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		
		$builder
			->add('file', VichImageType::class, [
               'label' => false,
				//'label' => "Poster",
                //'mapped' => false,
				'attr' => ['id' => 'posterFile'],
				'required' => false,
                'download_uri' => false,
				'allow_delete' => false,
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
            'data_class' => AnnouncementPoster::class,
        ]);
    }
}
