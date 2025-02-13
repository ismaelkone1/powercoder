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

class BesoinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('client_id', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('salaries', EntityType::class, [
                'class' => Salarie::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('competences', EntityType::class, [
                'class' => Competence::class,
                'choice_label' => 'id',
                'multiple' => true,
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
