ZodiacSignBundle: Zodiac signs calculator
=========================================

This bundle allows you to resolve the zodiac sign from various data types.

Installation
------------

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
composer require seb-jean/zodiac-sign-bundle
```

### Applications that don't use Symfony Flex

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
composer require seb-jean/zodiac-sign-bundle
```

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    SebJean\ZodiacSignBundle\ZodiacSignBundle::class => ['all' => true],
];
```

## Usage

This bundle provides a `calculateZodiacSign` method that accepts a date in various formats:
- `\DateTimeInterface` object
- `string` (date string)
- `int` (timestamp)
- `float` (timestamp with microseconds)
- `null` (for the current date)

It returns a `ZodiacSign` enum that you can use in PHP or Twig.

### Service

```php
use SebJean\ZodiacSignBundle\ZodiacSignCalculator;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfileController
{
    public function show(ZodiacSignCalculator $zodiacSignCalculator, TranslatorInterface $translator): Response
    {
        $date = new \DateTime('2010-10-04 13:45');

        $zodiacSign = $zodiacSignCalculator->calculateZodiacSign($date);

        $name = $zodiacSign->trans($translator); // "Libra"
        $symbol = $zodiacSign->getSymbol(); // "♎"
        
        // Get translated name with specific locale
        // $name = $zodiacSign->trans($translator, 'fr'); "Balance"

        return $this->render('profile/show.html.twig', [
            'name' => $name,
            'symbol' => $symbol,
        ]);
    }
}
```

### Twig

The bundle provides a `zodiac_sign` filter and function that returns a `ZodiacSign` enum.

```twig
{# With filter #}
{% set zodiac_sign = '1990-05-15'|zodiac_sign %}

{# ... or use the equivalent function: #}
{% set zodiac_sign = zodiac_sign('1990-05-15') %}

Your zodiac sign is {{ zodiac_sign.symbol }} {{ zodiac_sign|trans }}
{# outputs "Your zodiac sign is ♉ Taurus" #}

{# Get translated name with specific locale #}
{{ zodiac_sign|trans(locale: 'fr') }}
{# outputs "Taureau" #}
```

## Tests

To run the test suite, you first need to clone this repo and then install all
dependencies [through Composer](https://getcomposer.org):

```bash
composer install
```

To run the test suite, go to the project root and run:

```bash
vendor/bin/phpunit
```

Contributing
------------

Contributions are welcome! Please start by creating an issue to discuss your changes.

License
-------

This bundle is released under the [MIT license](LICENSE)