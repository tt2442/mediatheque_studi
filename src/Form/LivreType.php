<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre')
            ->add('Description')
            ->add('Img', FileType::class, [
                'label' => "Image de couverture",
                'mapped' => false,
                'required' => true,
                'label_attr' => ['class' => 'form_label'],
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                    ])
                ],
            ])
            ->add('Auteur')
            ->add('Date', DateType::class, ['label' => "Date de parution",  'widget' => 'single_text'])
            ->add('Type', ChoiceType::class, [
                'choices' => [
                    'Romans' => 'Romans',
                    'Bandes dessinées' => 'Bandes dessinées',
                    'Albums pour enfants' => 'Albums pour enfants',
                    'Documentaires' => 'Documentaires'
                ]
            ])
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
