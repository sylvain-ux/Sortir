<?php

namespace App\Form;


use App\Entity\School;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use function Sodium\add;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name',TextType::class,
                [
                    'label'=>'Prénom'
                ])
            ->add('firstname',TextType::class,
            [
                    'label'=>'Nom'
            ])

            ->add('phone',TextType::class,
                [
                    'label'=>'Téléphone'
                ] )

            ->add('email',EmailType::class,
            [
                'label'=>'Email'
            ])

            ->add('school',EntityType::class,[
                'label'=>'Ecole de rattachement',
                'class' => School::class,
                'choice_label' => 'name','disabled' => true
            ])



            ->add('save', SubmitType::class, ['label' => 'Modifier votre profil']);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
