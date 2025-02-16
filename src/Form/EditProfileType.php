<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control my-1']
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control my-1']
            ])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
                'attr' => ['class' => 'form-control my-1']
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => ['class' => 'form-control my-1']
            ])
            // Ajouter l'adresse
            ->add('address', CollectionType::class, [
                'entry_type' => AddressType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,  // Autoriser l'ajout d'une adresse si vide
                'allow_delete' => false,
                'by_reference' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour le profil'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class, 
        ]);
    }
}
