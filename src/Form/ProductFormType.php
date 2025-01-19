<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use App\Enum\Gender;
use App\Enum\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'titre',
                'attr' => ['placeholder' => 'Entrer le nom d\'article'],
                'required'  => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Ajoutez une description détaillée de ton article'],
                'required'  => true,                
            ])
            ->add('gender', ChoiceType::class, [
                'label' => false,
                'choices' => Gender::getChoices(),
                'placeholder' => 'Sélectionnez un genre',
                'required'  => true,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => false,
                'placeholder' => 'Choisissez une catégorie',                
                'required'  => true,
            ])
            ->add('size', NumberType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Entrez la taille du produit'],
                'required'  => true,
            ])
            ->add('state', ChoiceType::class, [
                'label' => false,
                'choices' => State::getChoices(),
                'placeholder' => 'Sélectionnez un état',
                'required'  => true,
            ])
            ->add('price', NumberType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Entrez le prix du produit'],
                'required'  => true,                
            ])          
            ->add('image', FileType::class, [
                'label' => true,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => [
                    'class' => 'btn-general  w-25', // Utilisez la classe btn-general
                    'style' => 'margin-top:25px'   // Garder le style de marge si nécessaire
                ],
                'row_attr' => [
                    'class' => 'text-end'  // Alignement à droite dans le conteneur
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
