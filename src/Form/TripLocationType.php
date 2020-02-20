<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\TripLocation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripLocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
/*            ->add('name', TextType::class, ['label' => 'Ville :'])
            ->add('city', EntityType::class,['class' => City::class, 'choice_label' => 'name'])
            ->add('street', TextType::class, ['label' => 'Lieu :'])
            ->add('longitude', TextType::class, ['label' => 'Longitude :'])
            ->add('latitude', TextType::class, ['label' => 'Latitude :'])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])*/
            ->add('name', TextType::class, ['label' => 'Nom :'])
            ->add('search',TextType::class,[
                'label' => 'Recherche',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'search',],
            ])
            ->add('street',TextType::class,
                [
                    'label' => 'Rue',
                    'attr' => ['class' => 'search_street'],]
            )
            ->add('longitude',HiddenType::class,
                [
                    'attr' => ['class' => 'search_lng'],
                ]
            )
            ->add('latitude',HiddenType::class,
                [
                    'attr' => ['class' => 'search_lat'],
                ]
            )
            ->add('city',TextType::class,[
                'label' => 'Ville',
                'mapped' => false,
                 'attr' => ['class' => 'search_city'],
            ])
            ->add('zip_code',TextType::class,
                [
                    'label' => 'Code postal',
                    'mapped' => false,
                    'attr' => ['class' => 'search_zip'],
                ]
            )
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TripLocation::class,
        ]);
    }
}
