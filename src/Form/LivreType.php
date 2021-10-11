<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre')
            ->add('Description')
            ->add('Img')
            ->add('Auteur')
            ->add('Date')
            ->add('Type')
            ->add('Genres', EntityType::class, [
                // looks for choices from this entity
                'class' => Genre::class,
                'required' => true,

                // uses the User.username property as the visible option string
                'choice_label' => 'Titre',

                // used to render a select box, check boxes or radios
                'multiple' => true,
                'by_reference' => true,
                'label' => 'Genre',

                'attr' => ['style' => 'height:100%;'],

                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
