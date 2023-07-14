<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Repository\PaysOhadaRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\DepartementRepository;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('town', TextType::class, [
                'label' => "Ville",
                'required' => false,
            ])
            ->add('neighborhood', TextType::class, [
                'label' => "Quartier",
                'required' => false,
            ])
            ->add('street', TextType::class, [
                'label' => "Rue",
                'required' => false,
            ])
            ->add('postalCode', TextType::class, [
                'label' => "Boîte postale",
                'required' => false,
            ])
            ->add('telephone', TextType::class, [
                'label' => "Téléphone", 
                'required' => false
            ])
            ->add('email', TextType::class, [
                'label' => "E-mail", 
                'required' => false,
                ])
        ;
        
      
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }

}
