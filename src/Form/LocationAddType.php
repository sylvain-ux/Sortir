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
            ->add('search',TextType::class,[
                'mapped' => false,
            ])
            ->add('name')
            ->add('street')
            ->add('longitude',HiddenType::class)
            ->add('latitude',HiddenType::class)
/*            ->add('city',TextType::class)*/
            ->add('city', EntityType::class, array(
                'choice_label' => function ($city) {

                    return $city->getName() . ' ' . $city->getZipCode();
                },
                'choice_attr' => function($city) {
                    return ['class' => 'zip_'.$city->getZipCode()];
                },
                'class' => 'App\Entity\City',
                'attr' => ['class' => 'select2'],
            ))
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
