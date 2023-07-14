<?php

namespace App\Form;

use App\Entity\Article;
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


class ModifyArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre",
            ])
			->add('author', TextType::class, [
                'label' => "Auteur",
				'mapped' => false,
				'data' => $options['author'],
				'disabled' => true,
				//'attr' => array('style' => 'width: 200px')
            ])
			->add('creationDate', DateType::class, [
                'label' => 'Date de creation',
				//'mapped' => false,
				'widget' => 'single_text',
    			// prevents rendering it as type="date", to avoid HTML5 date pickers
    			'html5' => false,
				'format' => 'dd-MM-yyyy',
				'disabled' => true
            ])
			->add('publicationDate', DateType::class, [
                'label' => 'Date de publication',
				//'mapped' => false,
				'widget' => 'single_text',
    			// prevents rendering it as type="date", to avoid HTML5 date pickers
    			'html5' => false,
				'format' => 'dd-MM-yyyy',
				'disabled' => true
            ])
			->add('modificationDate', DateType::class, [
                'label' => 'Date de modification',
				//'mapped' => false,
				'widget' => 'single_text',
    			// prevents rendering it as type="date", to avoid HTML5 date pickers
    			'html5' => false,
				'data' => $options['modificationDate'],
				'format' => 'dd-MM-yyyy',
				'disabled' => true
            ])
			->add('abstract', TextareaType::class, [
                'label' => 'Extrait',
				//'mapped' => false,
				'attr' => ['col' => 100, 'row' => 7],
            ])
			//->add('content', TextareaType::class, [
            //    'label' => 'Details',
            //])
			->add('content', CKEditorType::class, [
                'label' => 'Details',
            ])
			->add('publish', SubmitType::class, [
					'label' => $options['translator']->trans('Modifiez'),
					'attr' => ['class' => 'btn btn-primary'],
			])
			//->add('_target_path', HiddenType::class, [
			//	'mapped' => false,
    		//	'data' => 'app_home',
			///])
			///->add('_csrf_token', HiddenType::class, [
			//	'mapped' => false,
    		//	'data' => 'app_home',
			//])
        ;	
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
			'author' => null,
			'translator' => null,
			'modificationDate' => null,
        ]);
    }
}
