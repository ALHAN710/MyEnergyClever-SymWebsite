<?php

namespace App\Form;

use App\Entity\User1;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdminRegistration1Type extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'enterpriseName',
                TextType::class,
                $this->getConfiguration("Nom de l'entreprise", "Nom de l'entreprise")
            )

            ->add(
                'email',
                EmailType::class,
                $this->getConfiguration("Email", "Email de l'entreprise")
            )
            ->add(
                'avatar',
                FileType::class,
                $this->getConfiguration(
                    "Avatar Utilisateur (IMG file)",
                    "",
                    [
                        // unmapped means that this field is not associated to any entity property
                        'mapped' => false,

                        // make it optional so you don't have to re-upload the IMG file
                        // every time you edit the Product details
                        'required' => false,

                        // unmapped fields can't define their validation using annotations
                        // in the associated entity, so you can use the PHP constraint classes
                        'constraints' => [
                            new File([
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/png',
                                    'image/jpeg',
                                ],
                                'mimeTypesMessage' => 'Please upload a valid Image format(jpeg, png)',
                            ])
                        ],
                    ]
                )
            )
            ->add(
                'enterpriseLogo',
                FileType::class,
                $this->getConfiguration(
                    "Logo Entreprise (IMG file)",
                    "",
                    [
                        // unmapped means that this field is not associated to any entity property
                        'mapped' => false,

                        // make it optional so you don't have to re-upload the IMG file
                        // every time you edit the Product details
                        'required' => false,

                        // unmapped fields can't define their validation using annotations
                        // in the associated entity, so you can use the PHP constraint classes
                        'constraints' => [
                            new File([
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/png',
                                    'image/jpeg',
                                ],
                                'mimeTypesMessage' => 'Please upload a valid Image format(jpeg, png)',
                            ])
                        ],
                    ]
                )
            )
            ->add(
                'hash',
                PasswordType::class,
                $this->getConfiguration("Mot de passe", "Entrer votre mot de passe")
            )
            ->add(
                'passwordConfirm',
                PasswordType::class,
                $this->getConfiguration("Confirmation de mot de passe", "Veuillez confirmer votre mot de passe")
            )
            ->add(
                'role',
                ChoiceType::class,
                [
                    'choices' => [
                        'USER' => 'ROLE_USER',
                        'ADMIN' => 'ROLE_ADMIN'
                    ],
                    'label'    => 'Rôle'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User1::class,
        ]);
    }
}
