<?php
namespace App\Form;

use App\Entity\User;
use App\Enum\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class SignupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('date_naissance', null, [
                'widget' => 'single_text',
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Médecin' => Role::Medecin,
                    'Patient' => Role::Patient,
                ],
                'expanded' => true, // Pour rendre les choix radios visibles
                'multiple' => false, // Ne permet qu'un seul choix
            ])
            ->add('specialite', null, [
                'required' => false
            ])
            ->add('ville')
            ->add('adresse')
            ->add('email')
            ->add('mdp', PasswordType::class)
            ->add('confirm_password', PasswordType::class, [
                'mapped' => false,
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
