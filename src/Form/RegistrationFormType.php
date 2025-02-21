<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\Type;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a username']),
                ],
                'attr' => ['placeholder' => 'Nom d\'utilisateur', 'class' => 'form-control'],
                'label' => 'Nom d\'utilisateur',
                'label_attr' => ['class' => 'form-label'], // Pour les labels personnalisés
                'row_attr' => ['class' => 'form-floating mb-3'], // Classe de la ligne
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un email']),
                ],
                'attr' => ['placeholder' => 'Email', 'class' => 'form-control'],
                'label' => 'Email',
                'label_attr' => ['class' => 'form-label'], // Pour les labels personnalisés
                'row_attr' => ['class' => 'form-floating mb-3'], // Classe de la ligne
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Mot de passe', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Mot de passe',
                'label_attr' => ['class' => 'form-label'], // Pour les labels personnalisés
                'row_attr' => ['class' => 'form-floating mb-3'], // Classe de la ligne
            ])
            ->add('RGPDConsent', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(['message' => 'Vous devez accepter nos conditions d\'utilisation.']),
                ],
                'label' => 'J\'accepte les termes et conditions', // Texte personnalisé
                'label_attr' => ['class' => 'form-check-label text-secondary'], // Pour personnaliser le label
                'row_attr' => ['class' => 'mb-3'], // Classe pour l'espacement du champ
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
