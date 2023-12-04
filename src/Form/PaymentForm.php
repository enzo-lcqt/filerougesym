<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroCarte', TextType::class, [
                'label' => 'Numéro de carte (12 chiffres)',
                'attr' => ['maxlength' => 12],
            ])
            ->add('ccv', PasswordType::class, [
                'label' => 'CCV (3 chiffres)',
                'attr' => ['maxlength' => 3],
            ])
            ->add('dateExpiration', TextType::class, [
                'label' => 'Date d\'expiration de la carte (MM/yy)',
                'attr' => ['maxlength' => 5], // Pour forcer le format MM/yy
            ])
            ->add('submit', SubmitType::class, ['label' => 'Valider le paiement']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}