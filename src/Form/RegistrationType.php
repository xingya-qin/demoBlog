<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('username')
            ->add('password', PasswordType::class) // on apple la class PasswordType afin d'avoir des champs du formulaire de type 
            // "password" pour masquer les mots de passe à la saisie du formulaire 
            ->add('confirm_password', PasswordType::class) // on ajoute un champ afin de confirmer le mot de passe, ce champ ne sera pas inséré en BDD
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
