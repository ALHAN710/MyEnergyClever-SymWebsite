<?php

namespace App\Form;

use App\Entity\User1;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            $this->getConfiguration("Nom de l'entreprise","Nom de l'entreprise")
        )
        
        ->add(
            'email',
            EmailType::class, 
            $this->getConfiguration("Email","Email de l'entreprise")
        )
        ->add(
            'avatar',
            TextType::class, 
            $this->getConfiguration("Logo","URL du logo de l'entreprise",[
                'required' => false
            ])
        )
        ->add(
            'hash',
            PasswordType::class, 
            $this->getConfiguration("Mot de passe","Entrer votre mot de passe")
        )
        ->add(
            'passwordConfirm', 
            PasswordType::class,
            $this->getConfiguration("Confirmation de mot de passe", "Veuillez confirmer votre mot de passe")
        )
        ->add(
            'role',
            ChoiceType::class,[
                'choices' => [
                    'USER' => 'ROLE_USER',
                    'ADMIN' => 'ROLE_ADMIN'
                ],
                'label'    => 'Rôle'
            ]
        )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User1::class,
        ]);
    }
}
