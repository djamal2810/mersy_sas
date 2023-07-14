<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\LegalCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Constants\Constants;


class AgentAssignmentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
			->add('agent', EntityType::class, [
				'mapped' => false,
				'placeholder' => 'Choisissez',
				//'placeholder' => $defaultChoice?$defaultChoice->getUsername():'Choisissez',
				//'by_reference' => false,
				//'data' => ($defaultChoice->getEntityProperty() ? $defaultChoice->getEntityProperty() : null),
				'class' => User::class,
				//'mapped' => false,
				'query_builder' => function (EntityRepository $er) 
				{
        			return $er->createQueryBuilder('u')
						->where('u.roles LIKE :roles')
        				->setParameter('roles', '%"'.Constants::ROLE_CLIENT_SERVICE_PROVIDER.'"%')
						->orderBy('u.id', 'ASC');
    			},
    			'choice_label' => 'username',
				'attr' => array(
            		'class' => 'agent-class',
					'assigned_agent_name' => $options['agent_name']?$options['agent_name']:'',
        		),
				'label' => false,
    		])
			->add('submit', SubmitType::class, [
    			'attr' => ['class' => 'btn btn-primary submit-class'],
				'label' => $options['translator']->trans("Attribuez")
			])

        ;
		
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LegalCase::class,
			'translator' => null,
			'agent_name' => null,
			'attr' => array(
            	'class' => 'agent-assignment-drop-down-list'
        	),
        ]);
    }
}
