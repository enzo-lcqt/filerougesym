<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class deliveryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pays', TextType::class, ['label' => 'pays'])
            ->add('ville', TextType::class, ['label' => 'ville'])
            ->add('codepostal', TextType::class, ['label' => 'code postal'])
            ->add('adresse', TextType::class, ['label' => 'adresse']);
    }
}