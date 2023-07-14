<?php

namespace App\Form;

use App\Entity\UserQuestion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UserQuestionFormType extends AbstractType
{
	
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => $options['translator']->trans("Nom"),
            ])
			->add('email', TextType::class, [
                'label' => $options['translator']->trans('Email'),
				//'mapped' => false,
            ])
			->add('telephone', TextType::class, [
                'label' => $options['translator']->trans('Téléphone'),
				//'mapped' => false,
            ])
			->add('question', TextareaType::class, [
                'label' => $options['translator']->trans('Votre question'),
				//'mapped' => false,
            ])
			->add('save', SubmitType::class, [
				'label' => $options['translator']->trans('Envoyez'),
				'attr' => ['class' => 'btn btn-primary'],
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserQuestion::class,
			'translator' => null,
        ]);
    }
}
