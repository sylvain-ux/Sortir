<?php

namespace App\Form;

use App\Entity\School;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use function Sodium\add;

class UserAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class)
            ->add('firstname',TextType::class)
            ->add('phone',TextType::class)
            ->add('email',EmailType::class)
            ->add('school',EntityType::class,[
                'class' => School::class,
                'choice_label' => 'name',
            ])
            ->add('actif',ChoiceType::class, [
                'multiple' => false,
                'expanded' => true,
                'choices'  => [
                    'Oui' => 1,
                    'Non' => 0,
                ]])
            ->add(
                'Password',
                PasswordType::class,
                [
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'Please enter a password',
                            ]
                        ),
                        new Length(
                            [
                                'min' => 6,
                                'minMessage' => 'Your password should be at least {{ limit }} characters',
                                // max length allowed by Symfony for security reasons
                                'max' => 4096,
                            ]
                        ),
                    ],
                    'label' => 'Mot de passe',
                ]
            )
            ->add('save', SubmitType::class, ['label' => 'Ajouter']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
