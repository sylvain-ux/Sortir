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

class LocationAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('search',TextType::class,[
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'search',],
            ])
            ->add('street',TextType::class,
                ['attr' => ['class' => 'search_street'],]
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
            ->add('city',TextType::class,['mapped' => false,
                  'attr' => ['class' => 'search_city'],
                ])
            ->add('zip_code',HiddenType::class,
                [
                    'mapped' => false,
                    'attr' => ['class' => 'search_zip'],
                ]
            )

            ->add('save', SubmitType::class, ['label' => 'Ajouter']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TripLocation::class,
        ]);
    }
}
