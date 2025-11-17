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
            ->add('country', TextType::class, ['required' => false, 'label' => 'Pays', 'attr' => ['placeholder' => 'France'], 'row_attr' => ['class' => 'address-row']])
            ->add('city', TextType::class, ['required' => false, 'label' => 'Ville', 'attr' => ['placeholder' => 'Paris'], 'row_attr' => ['class' => 'address-row']])
            ->add('postCode', TextType::class, ['required' => false, 'label' => 'Code Postal', 'attr' => ['placeholder' => '75000'], 'row_attr' => ['class' => 'address-row']])
            ->add('street', TextType::class, ['required' => false, 'label' => 'Rue', 'attr' => ['placeholder' => '10 rue de la Paix'], 'row_attr' => ['class' => 'address-row']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Address::class]);
    }
}
