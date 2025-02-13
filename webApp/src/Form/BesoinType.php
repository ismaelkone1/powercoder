<?php

namespace App\Form;

use App\Entity\Besoin;
use App\Entity\Competence;
use App\Entity\Salarie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BesoinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('competences', EntityType::class, [
                'class' => Competence::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false,
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Besoin::class,
        ]);
    }
}
