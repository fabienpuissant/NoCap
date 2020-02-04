<?php

namespace App\Form;

use App\Entity\Photographe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Email', EmailType::class, ['label' => 'Email'])
            ->add('Password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('save', SubmitType::class, ['label' => 'Se Connecter'])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Photographe::class,
        ]);
    }
}
