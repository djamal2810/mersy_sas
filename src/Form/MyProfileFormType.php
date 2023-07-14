<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class MyProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur",
            ])
			->add('firstName', TextType::class, [
                'label' => 'Nom',
				'mapped' => false,
				'data' => $options['firstName'],
            ])
			->add('lastName', TextType::class, [
                'label' => 'Prenom',
				'mapped' => false,
				'data' => $options['lastName'],
            ])
			->add('email', TextType::class, [
                'label' => 'Email',
				'mapped' => false,
				'data' => $options['email'],
            ])
			->add('telephone', TextType::class, [
                'label' => 'Téléphone',
				'mapped' => false,
				'data' => $options['telephone'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
				'type' => PasswordType::class,
				'invalid_message' => $options['translator']->trans('Les mots de passe doivent être identiques'),
				'required' => false,
    			'first_options'  => ['label' => $options['translator']->trans('Mot de passe'), 'hash_property_path' => 'password'],
    			'second_options' => ['label' => $options['translator']->trans('Rentrez le mot de passe')],
                'mapped' => false,
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
    			'attr' => ['class' => 'btn btn-primary'],
				'label' => $options['translator']->trans("Modifiez"),
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
			'firstName' => null,
			'lastName' => null,
			'email' => null,
			'telephone' => null,
        ]);
    }
}
