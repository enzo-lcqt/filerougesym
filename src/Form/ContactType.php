<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Ajoute un champ 'nom' de type TextType avec un label personnalisé
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            
            // Ajoute un champ 'prenom' de type TextType avec un label personnalisé
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            
            // Ajoute un champ 'telephone' de type TelType avec un label personnalisé
            ->add('telephone', TelType::class, [ 
                'label' => 'Téléphone',
            ])
            
            // Ajoute un champ 'email' de type EmailType avec un label personnalisé
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
            ])
            
            // Ajoute un champ 'password' de type RepeatedType avec deux champs de type PasswordType pour la confirmation
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                ],
            ])
            
            // Ajoute un champ 'ville' de type TextType avec un label personnalisé
            ->add('ville', TextType::class, [
                'label' => 'Ville',
            ])
            
            // Ajoute un champ 'adresse' de type TextType avec un label personnalisé
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
            ])
            
            // Ajoute un champ 'code_postal' de type TextType avec un label personnalisé
            ->add('code_postal', TextType::class, [
                'label' => 'Code postal',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Configure les options du formulaire, notamment pour permettre de traiter un objet null
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
