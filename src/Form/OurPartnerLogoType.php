<?php

namespace App\Form;

use App\Entity\OurPartnerLogo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class OurPartnerLogoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		$builder
			->add('file', VichFileType::class, [
               'label' => false,
                //'mapped' => false,
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
            'data_class' => OurPartnerLogo::class,
        ]);
    }
}
