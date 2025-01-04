<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a username']),
                ],
                'attr' => ['placeholder' => 'Username'],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter an email']),
                ],
                'attr' => ['placeholder' => 'Email'],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Password'],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a password']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(['message' => 'You should agree to our terms.']),
                ],
                'label' => 'J\'accepte les termes et conditions', // Ajout du texte personnalisé
                'label_attr' => ['class' => 'form-check-label text-secondary'], // Ajouter une classe pour le style
            ]);            
            /* ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'html5' => true,
                'required' => false, // Le champ ne sera pas visible
                'data' => new \DateTime(), // Définir la date de création à la date et heure actuelles
                'attr' => ['style' => 'display:none;'], // Masquer le champ dans le formulaire
            ])
            ->add('isRole', CheckboxType::class, [
                'required' => false,
                'label' => 'Has Role?',
                'attr' => ['style' => 'display:none;'], // Masquer ce champ
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',
                ],
                'multiple' => true,
                'expanded' => true, // Affichage sous forme de cases à cocher
                'label' => 'Roles',
                'attr' => ['style' => 'display:none;'], // Masquer ce champ
            ])
            ->add('isActive', CheckboxType::class, [
                'required' => false,
                'label' => 'Is Active',
                'attr' => ['style' => 'display:none;'], // Masquer ce champ
            ]); */
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
