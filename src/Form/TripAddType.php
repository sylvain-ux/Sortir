<?php

namespace App\Form;

use App\Entity\School;
use App\Entity\State;
use App\Entity\Trip;
use App\Entity\TripLocation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('dateTimeStart',DateTimeType::class)
            ->add('duration',NumberType::class)
            ->add('registDeadline',DateTimeType::class)
            ->add('nbRegistMin',NumberType::class)
            ->add('nbRegistMax',NumberType::class)
            ->add('info',TextareaType::class)
/*            ->add('reason',TextareaType::class)*/
            //->add('users')
            ->add('user',EntityType::class,[
                'class' => User::class,
                'choice_label' => 'name',
            ])
            ->add('school',EntityType::class,[
                'class' => School::class,
                'choice_label' => 'name',
            ])
            ->add('state',EntityType::class,[
                'class' => State::class,
                'choice_label' => 'info',
            ])
            ->add('location',EntityType::class,[
                'class' => TripLocation::class,
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
