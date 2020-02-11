<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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


//            ->add('user')
//            ->add('school')
//            ->add('state')
//            ->add('location')
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('send', SubmitType::class, ['label' => 'Publier la sortie'])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler']);
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