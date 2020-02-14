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
            ->add('name',TextType::class)
            ->add('firstname',TextType::class)
            ->add('phone',TextType::class)
            ->add('email',EmailType::class)
            ->add('school',EntityType::class,[
                'class' => School::class,
                'choice_label' => 'name','disabled' => true
            ])
            ->add('avatar', FileType::class, [
                'label'=>'Téléchargez votre photo.png',
                'mapped'=>false,
                'required'=>false,
                'attr'=>['class'=>""]
            ])

            ->add('save', SubmitType::class, ['label' => 'Modifier']);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
