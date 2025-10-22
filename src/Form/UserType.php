<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickname', TextType::class, ['label' => 'Pseudo', 'required' => true, 'attr' => ['placeholder' => 'VixSky']])
            ->add('firstname', TextType::class, ['required' => true, 'label' => 'Prénom', 'attr' => ['placeholder' => 'Jean']])
            ->add('lastname', TextType::class, ['required' => true, 'label' => 'Nom', 'attr' => ['placeholder' => 'Dupont']])
            ->add('email', EmailType::class, ['required' => true, 'label' => 'Email', 'attr' => ['placeholder' => 'jean.dupont@gmail.com']])
            ->add('avatar', TextType::class, ['required' => true, 'label' => 'avatar'])
            ->add('biography', TextareaType::class, ['required' => false, 'label' => 'Biographie', 'attr' => ['placeholder' => 'Parlez-nous un peu de vous...']])
            ->add('Address', AddressType::class, [
                'required' => true,
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => ['label' => 'Mot de passe', 'attr' => ['placeholder' => '***********']],
                'second_options' => ['label' => 'Confirmer le mot de passe', 'attr' => ['placeholder' => '***********']],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
            ])
            ->add('save', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => ['class' => 'btn btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
