<p align="center">
    <img src="https://raw.githubusercontent.com/AlexOlival/socrates/docs/docs/logo.png" alt="Socrates logo" width="480">
</p>
<p align="center">
    <img src="https://raw.githubusercontent.com/AlexOlival/socrates/docs/docs/example.png" alt="Usage example" width="800">
</p>
<p align="center">
    <img src="https://github.com/AlexOlival/socrates/workflows/Build/badge.svg" alt="Badge">
</p>

------
## Introduction
>I am a **Citizen of the World**, and my Nationality is Goodwill.

**Socrates** is a PHP Package that allows you to validate and retrieve personal data from most [National Identification Numbers](https://en.wikipedia.org/wiki/National_identification_number) in Europe, with the goal of eventually supporting as many countries in the world as possible.
<p>Some countries also encode personal information of the citizen, such as gender or the place of birth. This package allows you to extract that information in a consistent way.</p>
<p>For Laravel, a Facade and request Validator is also made available (see usage below)</p>

Our goals:
* Standardize and centralise what is usually very difficult and sparse information to find.
* Have a consistent API for retrieving citizen information from an ID, if available.
* Test each individual country validation and data extraction algorithm with a number of valid and invalid IDs.
* Support as many countries as viably possible.

## Installation
`composer require reducktion/socrates`

## Usage
Socrates provide two methods: `validateId` and `getCitizenDataFromId`. Both receive the ID and the country code in [ISO 3166-2 format](https://en.wikipedia.org/wiki/ISO_3166-2)  as the first and second parameters respectively. Simply instantiate the class, and call the method you wish:

```php
use Reducktion\Socrates\Socrates;

$socrates = new Socrates();
$socrates->validateId('14349483 0 ZV3', 'PT');
```

For Laravel, a facade is also available for your convenience:

```php
use Reducktion\Socrates\Laravel\Facades\Socrates;

Socrates::getCitizenDataFromId('550309-6447', 'SE');
```

You can also use the `national_id:[COUNTRY CODE]` request validation rule:

```php
$request->validate([
    'id' => 'national_id:' . $request->get('country-code'),
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
if ($socrates->validateId('719102091', 'NL')) {
    echo 'Valid ID.'
} else {
    echo 'Invalid ID.'
}
```

### getCitizenDataFromId