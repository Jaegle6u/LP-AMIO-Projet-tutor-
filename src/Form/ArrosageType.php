<?php

namespace App\Form;

use App\Entity\Arrosage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArrosageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('minTemperature')
            ->add('maxTemperature')
            ->add('minHumidite')
            ->add('maxHumidite')
            ->add('minHumiditeSol')
            ->add('maxHumiditeSol')
            ->add('checkTemperature')
            ->add('checkHumidite')
            ->add('checkHumiditeSol')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Arrosage::class,
        ]);
    }
}
