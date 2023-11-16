<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class FinaliserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'nom'])
            ->add('prenom', TextType::class, ['label' => 'prenom'])
            ->add('pays', CountryType::class, ['label' => 'pays'])
            ->add('ville', TextType::class, ['label' => 'ville'])
            ->add('codepostal', IntegerType::class, ['label' => 'code postal'])
            ->add('adresse', TextType::class, ['label' => 'adresse'])
            ->add('submit', SubmitType::class, ['label' => 'Valider']);
    }
}