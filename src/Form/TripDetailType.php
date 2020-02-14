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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom de la sortie :', 'disabled' => true,])
            ->add(
                'dateTimeStart',
                DateTimeType::class,
                ['widget' => 'single_text', 'label' => 'Date et heure de la sortie :', 'disabled' => true,]
            )
            ->add(
                'registDeadline',
                DateType::class,
                ['widget' => 'single_text', 'label' => 'Date limite d\'inscription :', 'disabled' => true,]
            )
            ->add('nbRegistMax', TextType::class, ['label' => 'Nombre de places maximum :', 'disabled' => true,])
            ->add('nbRegistMin', TextType::class, ['label' => 'Nombre de places minimum :', 'disabled' => true,])
            ->add(
                'duration',
                IntegerType::class,
                ['label' => 'Durée :', 'disabled' => true,]
            )
            ->add(
                'info',
                TextareaType::class,
                [
                    'label' => 'Description et infos :',
                    'required' => false,
                    'attr' => array('placeholder' => '...'),
                    'disabled' => true,
                ]
            )
            ->add(
                'school',
                EntityType::class,
                [
                    'class' => School::class,
                    'choice_label' => 'name',
                    'label' => 'Ville organisatrice',
                    'disabled' => true,
                ]
            )
            ->add(
                'location',
                EntityType::class,
                ['class' => TripLocation::class, 'choice_label' => 'name', 'disabled' => true,]
            )


//            ->add('user',HiddenType::class)

            ->add(
                'state',
                EntityType::class,
                [
                    'class' => State::class,
                    'choice_label' => 'info',
                    'attr' => array('style' => 'display:none;'),
                    'label' => false,
                    'disabled' => true,
                ]
            )
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
