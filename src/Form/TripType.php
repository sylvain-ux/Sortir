<?php

namespace App\Form;

use App\Entity\School;
use App\Entity\State;
use App\Entity\Trip;
use App\Entity\TripLocation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom de la sortie :'])
            ->add(
                'dateTimeStart',
                DateTimeType::class,
                ['widget' => 'single_text', 'label' => 'Date et heure de la sortie :']
            )
            ->add(
                'registDeadline',
                DateType::class,
                ['widget' => 'single_text', 'label' => 'Date limite d\'inscription :']
            )
            ->add('nbRegistMax', TextType::class, ['label' => 'Nombre de places maximum :'])
            ->add('nbRegistMin', TextType::class, ['label' => 'Nombre de places minimum :'])
            ->add(
                'duration',
                IntegerType::class,
                ['label' => 'Durée :']
            )
            ->add(
                'info',
                TextareaType::class,
                [
                    'label' => 'Description et infos :',
                    'required' => false,
                    'attr' => array('placeholder' => '...'),
                ]
            )
            ->add('school', EntityType::class,
                [
                    'class' => School::class,
                    'choice_label' => 'name',
                    'label' => 'Ville organisatrice',
                    'disabled' => true
                ])
            ->add('location', EntityType::class, ['class' => TripLocation::class, 'choice_label' => 'name'])


//            ->add('user',HiddenType::class)
            ->add('state', EntityType::class,
                [
                    'class' => State::class,
                    'choice_label' => 'info',
                    'attr' => array('style'=>'display:none;'),
                    'label'=> false
                ])

            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
//            ->add('send', SubmitType::class, ['label' => 'Publier la sortie'])
//            ->add('cancel', SubmitType::class, ['label' => 'Annuler'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Trip::class,
            ]
        );
    }
}