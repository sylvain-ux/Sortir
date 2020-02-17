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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
/*            ->add('search',TextType::class,[
                'mapped' => false,
                'attr' => ['class' => 'search'],
            ])*/
            ->add(
                'location',
                EntityType::class,
                [
                    'class'        => TripLocation::class,
                    'choice_label' => 'name',
                    'attr'         => ['class' => 'select2'],
                    'help_html'     => true,
                    'help' => 'Ajouter un nouveau lieu, <a href="/sortir/public/admin/location/add">cliquez ici</a>',
                ]
            )

            //->add('city',TextType::class,['mapped' => false])
            ->add('dateTimeStart', DateTimeType::class,
                    ['label' => 'Date de la sortie','widget' => 'single_text','data' => new \DateTime("now"),'html5' => true ]
            )
            ->add('duration', NumberType::class,
                [
                    'label' => 'Durée','help' => 'en minutes','html5' => true, 'attr' => ['min' => 0, 'step'=>5],
                ]
            )
            ->add('registDeadline', DateTimeType::class,
                ['label' => 'Fin des inscriptions','widget' => 'single_text','data' => new \DateTime("now") ]
            )
            ->add('nbRegistMin', NumberType::class,
                ['label' => 'Nombre minimum','html5' => true,
                 'attr' => ['min' => 0],
                ]
            )
            ->add('nbRegistMax', NumberType::class,
                ['label' => 'Nombre maximum','html5' => true,
                 'attr' => ['min' => 0],
                ]
            )
            ->add('info', TextareaType::class)
            ->add(
                'user',
                EntityType::class,array(
                    'choice_label' => function ($user) {
                        return $user->getFirstname() . ' ' . $user->getName();
                    },
                    'class' => 'App\Entity\User',
                    'attr' => ['class' => 'select2'],
                )
            )
            ->add(
                'school',
                EntityType::class,
                [
                    'class'        => School::class,
                    'choice_label' => 'name',
                ]
            )
            ->add(
                'state',
                EntityType::class,
                [
                    'class'        => State::class,
                    'choice_label' => 'info',
                ]
            )

            ->add('save', SubmitType::class, ['label' => 'Enregistrer']);
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