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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use App\Entity\UserRoleChoice;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class UserAccountManagementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
	
        $builder
            ->add('username', TextType::class, [
                'label' => $options['translator']->trans("Nom d'utilisateur"),
            ])
			->add('firstName', TextType::class, [
                'label' => $options['translator']->trans("Nom"),
				//'mapped' => false,
				//'data' => $firstName,
				'property_path' => 'userDetails.firstName',
            ])
			->add('lastName', TextType::class, [
                'label' => $options['translator']->trans("Prénom"),
				//'mapped' => false,
				//'data' => $lastName,
				'property_path' => 'userDetails.lastName',
            ])
			->add('email', TextType::class, [
                'label' => $options['translator']->trans("Email"),
				//'mapped' => false,
				//'data' => $email,
				'property_path' => 'userDetails.email',
            ])
			->add('telephone', TextType::class, [
                'label' => $options['translator']->trans("Téléphone"),
				//'mapped' => false,
				//'data' => $telephone,
				'property_path' => 'userDetails.telephone',
            ])
			/*
			->add('userCategory', EntityType::class, [
				'mapped' => false,
				'placeholder' => 'Catégorie',
				'class' => UserRoleChoice::class,
				'choice_label' => 'role',
				'label' => false,
    		])
			*/
            ->add('password', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
				'type' => PasswordType::class,
				'mapped' => false,
				'invalid_message' => 'Les mots de passe doivent être identiques',
				'required' => true,
    			'first_options'  => ['label' => 'Mot de passe'],
    			'second_options' => ['label' => 'Rentrez le mot de passe'],
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
			->add('Sauvegardez', SubmitType::class, [
    			'attr' => ['class' => 'btn btn-primary'],
				'label' => $options['translator']->trans("Envoyez")
			])
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
