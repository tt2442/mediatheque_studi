<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Prenom', null, ['label' => 'Prénom'])
            ->add('Nom', null, ['label' => 'Nom'])
            ->add('email', EmailType::class, ['label' => 'Email', 'required' => false])
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'required' => false,
                'label' => "Droit d'accès",
                'choices' => [
                    'Inscrit' => 'ROLE_Inscrit',
                    'Employe' => 'ROLE_Employe',
                ],
            ])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('Datebirth')
            ->add('Adresse');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
