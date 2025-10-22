<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', TextType::class, ['required' => false, 'label' => 'Pays', 'attr' => ['placeholder' => 'France']])
            ->add('city', TextType::class, ['required' => false, 'label' => 'Ville', 'attr' => ['placeholder' => 'Paris']])
            ->add('postCode', TextType::class, ['required' => false, 'label' => 'Code Postal', 'attr' => ['placeholder' => '75000']])
            ->add('street', TextType::class, ['required' => false, 'label' => 'Rue', 'attr' => ['placeholder' => '10 rue de la Paix']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Address::class]);
    }
}
