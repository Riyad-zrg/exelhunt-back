<?php

namespace App\Factory;

use App\Entity\Address;
use Faker\Factory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Address>
 */
final class AddressFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Address::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $faker = Factory::create('fr_FR');
        $country = $faker->randomElement(['France', 'United States', 'Germany', 'Italy', 'Spain']);
        $fakerByCountry = match ($country) {
            'France' => Factory::create('fr_FR'),
            'United States' => Factory::create('en_US'),
            'Germany' => Factory::create('de_DE'),
            'Italy' => Factory::create('it_IT'),
            'Spain' => Factory::create('es_ES'),
            default => Factory::create(),
        };

        return [
            'city' => $fakerByCountry->city(),
            'country' => $country,
            'postCode' => $fakerByCountry->postcode(),
            'street' => $fakerByCountry->streetAddress(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Address $address): void {})
        ;
    }
}
