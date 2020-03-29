<?php

namespace App\Form;

use DateTime;
use App\Entity\ApproFuel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ApproType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        date_default_timezone_set('Africa/Douala');
        $date = date('Y-m-d\TH:i');
        //$date->format('Y-m-d H:i:s');
        $builder
            ->add(
                'addAt',
                DateTimeType::class,
                $this->getConfiguration("Date d'ajout", "Entrer la date d'approvisionnement", [
                    "widget" => "single_text",
                    "attr" => [
                        "value"    => $date,
                        "required" => true,
                        "max"      => $date,
                    ]
                ])
            )
            ->add(
                'quantity',
                NumberType::class,
                $this->getConfiguration("Quantité (Litres) ", "Entrer la quantité d'approvisionnement", [
                    'attr' => [
                        "min" => 0,
                        "value" => 20,
                    ]
                ])
            )
            //->add('smartMod')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ApproFuel::class,
        ]);
    }
}
