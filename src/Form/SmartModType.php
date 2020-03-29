<?php

namespace App\Form;

use App\Entity\SmartMod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SmartModType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'moduleId',
                TextType::class,
                $this->getConfiguration("Identifiant du module", "Entrer l'identifiant unique du module")
            )
            ->add(
                'modName',
                TextType::class,
                $this->getConfiguration("Nom du module", "Entrer le nom de la zone du module")
            )
            //->add('associatedSite')
            ->add(
                'installationType',
                ChoiceType::class,
                $this->getConfiguration("Type d'installation", "Spécifier le type d'installation", [
                    'choices' => [
                        'Monophasé' => 'Monophasé',
                        'Triphasé' => 'Triphasé'
                    ]
                ])
            )
            ->add(
                'modType',
                ChoiceType::class,
                $this->getConfiguration("Type de module", "Spécifier le type de module", [
                    'choices' => [
                        'FUEL POWER' => 'FUEL',
                        'GRID POWER' => 'GRID'
                    ]
                ])
            )
            ->add(
                'critiqFuelStock',
                NumberType::class,
                $this->getConfiguration("Stock Critique", "Veuillez spécifier le seuil min du stock de carburant", [
                    'required' => false,
                    'attr' => [
                        'min'      => 0,
                        'value'    => 0,
                        'hidden'   => true
                    ]
                ])
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SmartMod::class,
        ]);
    }
}
