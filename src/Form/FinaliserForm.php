<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FinaliserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('prenom', TextType::class, ['label' => 'Prénom'])
            ->add('pays', CountryType::class, ['label' => 'Pays'])
            ->add('ville', TextType::class, ['label' => 'Ville'])
            ->add('codepostal', IntegerType::class, ['label' => 'Code Postal'])
            ->add('adresse', TextType::class, ['label' => 'Adresse'])
            ->add('total', TextType::class, [
                'label' => 'Total',
                'mapped' => false, // Ne pas mapper ce champ à une propriété de l'entité
                'attr' => ['readonly' => true, 'value' => $options['montant_total']], // Le rendre en lecture seule
            ])
            ->add('submit', SubmitType::class, ['label' => 'Valider']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'montant_total' => null,
        ]);
    }
}