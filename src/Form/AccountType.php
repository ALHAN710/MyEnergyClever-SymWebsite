<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\User1;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('firstName')
            //->add('lastName')
            ->add('enterpriseName')
            ->add('email')
            ->add('avatar')
            //->add('picture')
            //->add('hash')
            //->add('introduction')
            //->add('description')
            //->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => User::class,
            'data_class' => User1::class,
        ]);
    }
}
