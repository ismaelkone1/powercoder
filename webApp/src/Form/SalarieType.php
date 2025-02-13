<?php

namespace App\Form;

use App\Entity\Salarie;
use App\Entity\Competence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalarieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du salarié',
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
                ]
            ])
            ->add('selectedCompetences', EntityType::class, [
                'class' => Competence::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
                'label' => 'Compétences',
                'attr' => [
                    'class' => 'mt-1 grid grid-cols-2 gap-4'
                ]
            ])
            ->add('interet', IntegerType::class, [
                'mapped' => false,
                'label' => 'Niveau d\'intérêt (1-10)',
                'attr' => [
                    'min' => 1,
                    'max' => 10,
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salarie::class,
        ]);
    }
}