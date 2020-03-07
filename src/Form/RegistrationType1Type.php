<?php

namespace App\Form;

use App\Entity\User1;
use App\Form\ApplicationType;
//use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType1Type extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enterpriseName', TextType::class, $this->getConfiguration("Nom de l'entreprise","Nom de l'entreprise"))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe","Entrer votre mot de passe"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User1::class,
        ]);
    }
}
