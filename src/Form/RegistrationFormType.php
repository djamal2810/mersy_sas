<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Contracts\Translation\TranslatorInterface;


class RegistrationFormType extends AbstractType
{
	
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => $options['translator']->trans("Nom d'utilisateur"),
				'attr' => [
					'placeholder' => $options['translator']->trans("Nom d'utilisateur"),
				]
            ])
			->add('firstName', TextType::class, [
                'label' => $options['translator']->trans('Nom'),
				'mapped' => false,
				'attr' => [
					'placeholder' => $options['translator']->trans("Nom"),
				]
            ])
			->add('lastName', TextType::class, [
                'label' => $options['translator']->trans('Prénom'),
				'mapped' => false,
				'attr' => [
					'placeholder' => $options['translator']->trans("Prénom"),
				]
            ])
			->add('email', TextType::class, [
                'label' => $options['translator']->trans('Email'),
				'mapped' => false,
				'attr' => [
					'placeholder' => $options['translator']->trans("Email"),
				]
            ])
			->add('telephone', TextType::class, [
                'label' => $options['translator']->trans('Téléphone'),
				'mapped' => false,
				'attr' => [
					'placeholder' => $options['translator']->trans("Téléphone"),
				]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
				'label' => $options['translator']->trans("Accord sur les termes"),
                'constraints' => [
                    new IsTrue([
                        'message' => $options['translator']->trans("Vous devriez accepter nos termes"),
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
				'type' => PasswordType::class,
				'mapped' => false,
				'invalid_message' => 'Les mots de passe doivent être identiques',
				'required' => true,
    			'first_options'  => ['label' => $options['translator']->trans('Mot de passe')],
    			'second_options' => ['label' => $options['translator']->trans('Rentrez le mot de passe')],
				'attr' => [
					'placeholder' => $options['translator']->trans("Mot de passe"),
				],
                //'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
			->add('save', SubmitType::class, [
				'label' => $options['translator']->trans('Envoyez'),
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
            'data_class' => User::class,
			'translator' => null,
        ]);
    }
}
