<?php

namespace App\Form;

use App\Entity\Site;
use App\Form\SmartModType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SiteType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                $this->getConfiguration("Nom du Site","Entrer un nom pour votre Site")
            )
            ->add(
                'slug',
                TextType::class,
                $this->getConfiguration("Adresse web", "Taper l'adresse web (automatique)",[
                    'required' => false
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
