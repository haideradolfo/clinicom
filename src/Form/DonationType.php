<?php

namespace App\Form;

use App\Entity\Donation;
use App\Entity\TypeDonation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class DonationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descriptionDonation', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Entrez une description'],
            ])
            ->add('montantDonation', NumberType::class, [
                'label' => 'Montant du Don (Dt)',
                'required' => false,
                'attr' => ['placeholder' => 'Entrez le montant', 'min' => 1],
            ])
            ->add('typeSang', ChoiceType::class, [ // ✅ Correction ici
                'label' => 'Type de Sang',
                'choices' => [
                    'O+' => 'O+',
                    'O-' => 'O-',
                    'A+' => 'A+',
                    'A-' => 'A-',
                    'B+' => 'B+',
                    'B-' => 'B-',
                    'AB+' => 'AB+',
                    'AB-' => 'AB-',
                ],
                'placeholder' => 'Sélectionnez votre type de sang',
                'required' => false,
            ])
            ->add('photoDonation', FileType::class, [
                'label' => 'Télécharger une photo (facultatif)',
                'required' => false,
                'attr' => ['accept' => 'image/*'],
            ])
            ->add('TypeDonation', EntityType::class, [
                'class' => TypeDonation::class,
                'choice_label' => 'typeDeDonation', 
                'placeholder' => 'Sélectionnez un type de donation',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donation::class,
        ]);
    }
}
