<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportUsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fileCSV', FileType::class, [
                'label' => 'Fichier .csv',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
 /*                   new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'text/csv',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid csv file',
                    ])*/
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
/*            ->add('name')
            ->add('firstname')
            ->add('phone')
            ->add('email')
            ->add('password')
            ->add('avatarName')
            ->add('actif')
            ->add('roles')
            ->add('user')
            ->add('school')*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
