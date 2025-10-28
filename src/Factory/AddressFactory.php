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
     */
    protected function defaults(): array|callable
    {
        $faker = Factory::create('fr_FR');

        $countries = array_merge(array_fill(0, 44, 'France'), ['United States', 'Germany', 'Italy', 'Spain', 'Belgium', 'Switzerland']);
        $country = $faker->randomElement($countries);

        $fakerByCountry = match ($country) {
            'France' => Factory::create('fr_FR'),
            'United States' => Factory::create('en_US'),
            'Germany' => Factory::create('de_DE'),
            'Italy' => Factory::create('it_IT'),
            'Spain' => Factory::create('es_ES'),
            'Belgium' => Factory::create('fr_BE'),
            'Switzerland' => Factory::create('de_CH'),
            default => Factory::create(),
        };

        if ('France' === $country) {
            $majorCities = ['Paris', 'Marseille', 'Lyon', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier', 'Bordeaux', 'Lille', 'Reims'];
            $city = $fakerByCountry->boolean(60) ? $fakerByCountry->randomElement($majorCities) : $fakerByCountry->city();
        } else {
            $city = $fakerByCountry->city();
        }

        $postCode = (string) $fakerByCountry->postcode();
        if (mb_strlen($postCode) > 10) {
            $postCode = mb_substr($postCode, 0, 10);
        }

        $street = $fakerByCountry->streetAddress();
        if (mb_strlen($street) > 100) {
            $street = mb_substr($street, 0, 100);
        }

        if (mb_strlen($city) > 50) {
            $city = mb_substr($city, 0, 50);
        }

        if (mb_strlen($country) > 30) {
            $country = mb_substr($country, 0, 30);
        }

        return [
            'country' => $country,
            'city' => $city,
            'postCode' => $postCode,
            'street' => $street,
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
