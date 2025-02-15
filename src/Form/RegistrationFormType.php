<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le nom est obligatoire.']),
                new Regex([
                    'pattern' => "/^[A-Z][a-zA-ZÀ-ÿ -]+$/",
                    'message' => "Le nom doit commencer par une majuscule et ne contenir que des lettres et des espaces."
                ])
            ],
        ])

        ->add('prenom', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le prénom est obligatoire.']),
                new Regex([
                    'pattern' => "/^[A-Z][a-zA-ZÀ-ÿ -]+$/",
                    'message' => "Le prénom doit commencer par une majuscule et ne contenir que des lettres et des espaces."
                ])
            ],
        ])

        ->add('role', ChoiceType::class, [
            'choices' => [
                'Médecin' => 'Medecin', // Libellé modifié pour l'affichage
                'Patient' => 'Patient',
            ],
            'placeholder' => 'Choisissez votre rôle', // Ajout du placeholder
            'constraints' => [new NotBlank(['message' => 'Veuillez sélectionner un rôle'])],
            'attr' => [
                'class' => 'form-select',
                'id' => 'role-select'
            ]
        ])
        ->add('specialite', TextType::class, [
            'required' => false,
            'attr' => [
                'placeholder' => 'Spécialité médicale'
            ]
        ])

            ->add('email', TextType::class, [
    'constraints' => [
        new NotBlank(['message' => 'L\'email est obligatoire.']),
        new Email(['message' => 'Veuillez entrer une adresse email valide.'])
    ],
])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe']),
         new Length(['min' => 8, 'minMessage' => 'Le mot de passe doit contenir au moins 8 caractères.']),
        new Regex([
            'pattern' => '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            'message' => 'Le mot de passe doit contenir au moins une majuscule, un chiffre et un caractère spécial.'
        ])
                ],
            ])

            ->add('ville', ChoiceType::class, [
                'choices' => [
                    'Ariana' => 'Ariana',
                    'Béja' => 'Béja',
                    'Ben Arous' => 'Ben Arous',
                    'Bizerte' => 'Bizerte',
                    'Gabès' => 'Gabes',
                    'Gafsa' => 'Gafsa',
                    'Jendouba' => 'Jendouba',
                    'Kairouan' => 'Kairouan',
                    'Kasserine' => 'Kasserine',
                    'Kebili' => 'Kebili',
                    'La Manouba' => 'La Manouba',
                    'Le Kef' => 'Le Kef',
                    'Mahdia' => 'Mahdia',
                    'Médenine' => 'Médenine',
                    'Monastir' => 'Monastir',
                    'Nabeul' => 'Nabeul',
                    'Sfax' => 'Sfax',
                    'Sidi Bouzid' => 'Sidi Bouzid',
                    'Siliana' => 'Siliana',
                    'Sousse' => 'Sousse',
                    'Tataouine' => 'Tataouine',
                    'Tozeur' => 'Tozeur',
                    'Tunis' => 'Tunis',
                    'Zaghouan' => 'Zaghouan'
                ],
                'placeholder' => 'Sélectionnez votre ville',
                'constraints' => [new NotBlank()],
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('adresse', TextType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [new NotBlank()],
                'attr' => [
                    'max' => (new \DateTime())->format('Y-m-d')
                ]
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
