<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entrez votre nom'],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Entrez votre prénom'],
            ])
            ->add('date_naissance', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Admin' => 'Admin',
                    'Médecin' => 'Medecin',
                    'Patient' => 'Patient',
                ],
                'expanded' => false, // false = menu déroulant, true = boutons radio
                'multiple' => false,
            ])
            ->add('specialite', TextType::class, [
                'label' => 'Spécialité (uniquement pour Médecin)',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: Cardiologue'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'exemple@email.com'],
            ])
            ->add('mdp', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['placeholder' => 'Entrez un mot de passe sécurisé'],
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Votre ville'],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['placeholder' => 'Votre adresse'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
