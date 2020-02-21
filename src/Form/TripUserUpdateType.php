<?php

namespace App\Form;

use App\Entity\Category;
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

class TripUserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('category',EntityType::class,
                [
                    'class'=>Category::class,
                    'choice_label'=> 'name',
                    'required' => false,
                    'label'=>'Catégorie :'
                ])
            ->add(
                'location',
                EntityType::class,
                [
                    'label' => 'Lieu',
                    'class' => TripLocation::class,
                    'choice_label' => 'name',
                    'attr' => ['class' => 'select2'],
                    'help_html'     => true,
                    'help' => 'Ajouter un nouveau lieu, <a href="/sortir/public/trip/createLocation">cliquez ici</a>',
                ]
            )
            ->add('dateTimeStart', DateTimeType::class)
            ->add('duration', NumberType::class)
            ->add('registDeadline', DateTimeType::class)
            ->add('nbRegistMin', NumberType::class)
            ->add('nbRegistMax', NumberType::class)
            ->add('info', TextareaType::class)

            ->add('save', SubmitType::class, ['label' => 'Enregistrer la modification'])
            ->add('published', SubmitType::class, ['label' => 'Publier la sortie'])
            ->add('drop', SubmitType::class,
                [
                    'label' => 'Supprimer la sortie',
                    'attr' => array(
                        'onclick' => 'return confirm("La suppression de votre sortie sera définitive. Voulez-vous continuer ?")')
                ]);

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
