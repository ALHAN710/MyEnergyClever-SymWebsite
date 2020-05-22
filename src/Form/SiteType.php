<?php

namespace App\Form;

use App\Entity\Site;
use App\Form\SmartModType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SiteType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                $this->getConfiguration("Site Name", "Enter a name for your Site")
            )
            ->add(
                'slug',
                TextType::class,
                $this->getConfiguration("Web Address", "Type the web address (automatic)", [
                    'required' => false
                ])
            )
            ->add(
                'subscription',
                ChoiceType::class,
                $this->getConfiguration("Subscription", "Specify the type of subscription", [
                    'choices' => [
                        'MT'          => 'MT',
                        'Tertiary'    => 'Tertiary',
                        'Residential' => 'Residential'
                    ]
                ])
            )
            ->add(
                'smartMods',
                CollectionType::class,
                [
                    'entry_type' => SmartModType::class,
                    'allow_add'  => true,
                    'allow_delete' => true
                ]
            )
            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
        ]);
    }
}
