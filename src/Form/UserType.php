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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickname', TextType::class, ['label' => 'Pseudo', 'required' => true, 'attr' => ['placeholder' => 'VixSky'], 'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le\'pseudonyme est obligatoire.',
                ]), ]])
            ->add('firstname', TextType::class, ['required' => true, 'label' => 'Prénom', 'attr' => ['placeholder' => 'Jean'], 'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le prénom est obligatoire.',
                ]), ]])
            ->add('lastname', TextType::class, ['required' => true, 'label' => 'Nom', 'attr' => ['placeholder' => 'Dupont'], 'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le nom est obligatoire.',
                ]), ]])
            ->add('email', EmailType::class, ['required' => true, 'label' => 'Email',
                'attr' => [
                    'placeholder' => 'jean.dupont@gmail.com',
                    'autocomplete' => 'off'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'L\'email est obligatoire.',
                    ]),
                    new Assert\Email([
                        'message' => 'L\'adresse "{{ value }}" n\'est pas valide (doit contenir un "@" et un ".").',
                        'mode' => 'strict',
                    ]),
                ]])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'custom-file-input',
                    'accept' => 'image/*',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '4M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Formats autorisés : JPEG, PNG, WEBP',
                    ]),
                ],
            ])
            ->add('biography', TextareaType::class, ['required' => false, 'label' => 'Biographie', 'attr' => ['placeholder' => 'Parlez-nous un peu de vous...']])
            ->add('Address', AddressType::class, [
                'required' => true,
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => '***********',
                        'autocomplete' => 'new-password',
                    ],
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Le mot de passe est obligatoire.',
                        ]),
                        new Assert\Length([
                            'min' => 8,
                            'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => ['label' => 'Confirmer le mot de passe', 'attr' => ['placeholder' => '***********', 'autocomplete' => 'new-password']],
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
