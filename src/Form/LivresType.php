<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Livre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('ISBN')
            ->add('Slug')
            ->add('Image')
            ->add('Resume')
            ->add('Editeur')
            ->add('DateEdition', null, [
                'widget' => 'single_text'
            ])
            ->add('Prix')
            ->add('categorie', EntityType::class, [
                'class' => Categories::class,
'choice_label' => 'libelle',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
