<p align="center">
    <img src="https://raw.githubusercontent.com/AlexOlival/socrates/master/docs/logo.png" alt="Socrates logo" width="480">
</p>
<p align="center">
    <img src="https://raw.githubusercontent.com/AlexOlival/socrates/master/docs/example.png" alt="Usage example" width="800">
</p>
<p align="center">
    <img  alt="Badge" src="https://github.com/AlexOlival/socrates/workflows/Build/badge.svg">
    <img alt="Total Downloads" src="https://img.shields.io/packagist/dt/reducktion/socrates">
    <img alt="Latest Version" src="https://img.shields.io/packagist/v/reducktion/socrates">
    <img alt="License" src="https://img.shields.io/github/license/reducktion/socrates">
    <img alt="StyleCI" src="https://github.styleci.io/repos/238900350/shield?branch=master">
    <img alt="Contributors" src="https://img.shields.io/badge/all_contributors-1-orange.svg?style=flat-square">
</p>

------
## Introduction
>I am a **Citizen of the World**, and my Nationality is Goodwill.

<i>You can now read [a blog post](https://medium.com/@alex.olival/road-to-mastery-building-an-open-source-package-3936f57aed81) about how this package was created and its goals.</i>

**Socrates** is a PHP Package that allows you to validate and retrieve personal data from [National Identification Numbers](https://en.wikipedia.org/wiki/National_identification_number). At the moment, most countries in Europe are supported, but the goal is to eventually support as many countries in the world as possible.
<p>Some countries also encode personal information of the citizen, such as gender or the place of birth. This package allows you to extract that information in a consistent way.</p>
<p>For Laravel, a Facade and request Validator is also available (see below).</p>
<p>This package can be useful for many things, such as validating a user's ID for finance related applications or verifying a user's age without asking for it explicitly. However, we recommend you review your country's data protection laws before storing any information.</p>

[Ports](https://github.com/reducktion/socrates#ports) of this package to other languages are currently in progress. Check further below for which ones are currently available.

Our goals:
* Standardize and centralise what is usually very difficult and sparse information to find.
* Have a consistent API for retrieving citizen information from an ID, if available.
* Test each individual country validation and data extraction algorithm with a number of valid and invalid IDs.
* Support as many countries as viably possible.

## Installation
`composer require reducktion/socrates`

## Usage
Socrates provides two methods: `validateId` and `getCitizenDataFromId`. Both receive the ID and the country code in [ISO 3166-2 format](https://en.wikipedia.org/wiki/ISO_3166-2)  as the first and second parameters respectively. Simply instantiate the class and call the method you wish:

```php
use Reducktion\Socrates\Socrates;
use Reducktion\Socrates\Constants\Country;

$socrates = new Socrates();
$socrates->validateId('14349483 0 ZV3', Country::PORTUGAL);
// or
$socrates->validateId('14349483 0 ZV3', 'PT');
```

For Laravel, a facade is also available for your convenience:

```php
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Constants\Country;

Socrates::getCitizenDataFromId('550309-6447', Country::SWEDEN);
```

You can also use the `national_id:[COUNTRY CODE]` request validation rule:

```php
$request->validate([
    'id' => 'national_id:lv'
]);
```
Still in Laravel, the package will try to guess the country to be validated by the default locale:

```php
App::setLocale('PT');

Socrates::validateId('11084129 8 ZX8');
```
 
However this is **not** recommended. The safest way is to always explicitly pass the country code as the second parameter.

### validateId
This method will return true or false depending on the validity of the ID.
In case the ID has the wrong character length, an `InvalidLengthException` will be thrown.

```php
if ($socrates->validateId('719102091', Country::NETHERLANDS)) {
    echo 'Valid ID.'
} else {
    echo 'Invalid ID.'
}
```

### getCitizenDataFromId
This method will return an instance of `Citizen`. If the ID is invalid, an `InvalidIdException` will be thrown. If the country does not support data extraction, an `UnsupportedOperationException` will be thrown.

```php
$citizen = $socrates->getCitizenDataFromId('3860123012', Country::ESTONIA);
```

The `Citizen` class stores the extracted citizen data in a consistent format across all countries.<br>
It exposes the `getGender()`, `getDateOfBirthNative()`, `getDateOfBirth()`, `getAge()` and `getPlaceOfBirth()` methods.<br><br>
Both `getGender` and `getPlaceOfBirth` return a `string`, `getAge()` returns an `int`, `getDateOfBirthNative()` returns a native `DateTime` and `getDateOfBirth()` returns a `Carbon` instance. <br>
**It is recommended to use `getDateOfBirthNative()` as Carbon will be removed as a dependency in a future release of Socrates.**
<p>Using the example above, Estonia only encodes the date of birth and gender of the citizen in their ID. So the above methods will return:</p>
 
```php
echo $citizen->getGender(); // 'Male'
echo $citizen->getDateOfBirthNative(); // DateTime instance with the date '1986-01-23'
echo $citizen->getDateOfBirth(); // DEPRECATED - Carbon instance with the date '1986-01-23'
echo $citizen->getAge(); // 34 (as of June 2020)
echo $citizen->getPlaceOfBirth(); // null
```

## Supported and Unsupported Countries

[Here](COUNTRIES.md) you can see the full list of supported countries and whether they support data extraction.

Three european countries are currently unsupported: Austria 🇦🇹, Belarus 🇧🇾 and Cyprus 🇨🇾. This is because we could not find a reliable source for the algorithm, if at all. Help would be appreciated to get these countries supported.

## Testing
`composer test`

## Ports
This package is also available for the following languages:

[Rust](https://github.com/reducktion/socrates-rs)<br>
[Java](https://github.com/reducktion/socrates-java)

## Contributing

Did you find a problem in any of the algorithms? 
Do you know how to implement a country which we have missed?
Are there any improvements that you think should be made to the codebase?
Any help is appreciated! Take a look at our [contributing guidelines](CONTRIBUTING.md).

## Code of Conduct
Our CoC is based on Ruby's. Check out [our code of conduct](CODE_OF_CONDUCT.md).

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits
Socrates was made with 💖 by [Alexandre Olival](https://github.com/AlexOlival)  and [João Cruz](https://github.com/JoaoFSCruz). 
We are Reducktion.
We hope to make someone's life easier after all the hard work compiling, researching, reverse-engineering and agonizing over ID validation algorithms - many of which were very obscure and hard to find.

## Special thanks
A big thanks goes to these people who helped us either test with real life IDs or guide us in finding the algorithm for their countries:
* Alexandra from 🇷🇴
* Berilay from 🇹🇷
* Christian from 🇨🇭
* Domynikas from 🇱🇹
* Jeppe from 🇩🇰
* Jeremy from 🇫🇷 
* Lisa from 🇬🇷
* Miguel from 🇪🇸

and Nair from 🇵🇹 for the package name.

## Contributors ✨

Thanks goes to these wonderful people ([emoji key](https://allcontributors.org/docs/en/emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tr>
    <td align="center"><a href="https://github.com/SLourenco"><img src="https://avatars2.githubusercontent.com/u/7704656?v=4" width="100px;" alt=""/><br /><sub><b>SLourenco</b></sub></a><br /><a href="https://github.com/reducktion/socrates/issues?q=author%3ASLourenco" title="Bug reports">🐛</a> <a href="https://github.com/reducktion/socrates/commits?author=SLourenco" title="Documentation">📖</a> <a href="https://github.com/reducktion/socrates/commits?author=SLourenco" title="Code">💻</a></td>
    <td align="center"><a href="https://www.linkedin.com/in/flavioheleno/"><img src="https://avatars0.githubusercontent.com/u/471860?v=4" width="100px;" alt=""/><br /><sub><b>Flávio Heleno</b></sub></a><br /><a href="https://github.com/reducktion/socrates/commits?author=flavioheleno" title="Code">💻</a> <a href="https://github.com/reducktion/socrates/commits?author=flavioheleno" title="Documentation">📖</a></td>
    <td align="center"><a href="https://github.com/YvesBos"><img src="https://avatars1.githubusercontent.com/u/22393924?v=4" width="100px;" alt=""/><br /><sub><b>Yves Bos</b></sub></a><br /><a href="https://github.com/reducktion/socrates/commits?author=YvesBos" title="Documentation">📖</a> <a href="https://github.com/reducktion/socrates/commits?author=YvesBos" title="Code">💻</a> <a href="https://github.com/reducktion/socrates/issues?q=author%3AYvesBos" title="Bug reports">🐛</a></td>
    <td align="center"><a href="https://github.com/bofalke"><img src="https://avatars2.githubusercontent.com/u/54977705?v=4" width="100px;" alt=""/><br /><sub><b>bofalke</b></sub></a><br /><a href="https://github.com/reducktion/socrates/commits?author=bofalke" title="Code">💻</a></td>
    <td align="center"><a href="https://github.com/gheleri"><img src="https://avatars1.githubusercontent.com/u/1103419?v=4" width="100px;" alt=""/><br /><sub><b>Rodolpho Lima</b></sub></a><br /><a href="https://github.com/reducktion/socrates/commits?author=gheleri" title="Code">💻</a> <a href="https://github.com/reducktion/socrates/commits?author=gheleri" title="Documentation">📖</a></td>
    <td align="center"><a href="https://github.com/tiagomichaelsousa"><img src="https://avatars1.githubusercontent.com/u/28356381?v=4" width="100px;" alt=""/><br /><sub><b>tiagomichaelsousa</b></sub></a><br /><a href="https://github.com/reducktion/socrates/commits?author=tiagomichaelsousa" title="Code">💻</a> <a href="https://github.com/reducktion/socrates/commits?author=tiagomichaelsousa" title="Documentation">📖</a></td>
  </tr>
</table>

<!-- markdownlint-enable -->
<!-- prettier-ignore-end -->
<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!