<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
/*            ->add('registDeadline')
            ->add('nbRegistMin')
            ->add('nbRegistMax')
            ->add('info')
            ->add('reason')
            ->add('users')
            ->add('user')
            ->add('school')
            ->add('state')
            ->add('location')*/
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
