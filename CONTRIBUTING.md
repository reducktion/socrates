# CONTRIBUTING

Thank you for your interest in wanting to contribute to Socrates!<br>
Up next is a small guide to create your first pull request. Check further below for a 
[walk-through](#how-to-implement-a-country-from-scratch) on how to
contribute with a new country from scratch.

## The Basics

### Process

1. Fork the project
1. Create a new branch
1. Code, test, commit and push
1. Open a pull request detailing your changes (do not forget to assign yourself to the PR).
1. Describe what your pull request is (change, bugfix, new country implementation, etc.)

### Guidelines

* We use [PSR-12](https://www.php-fig.org/psr/psr-12/) as our coding style, make sure to follow it.
* If you have multiple commits, squashing some of them might help us better understand what you did.
* You may need to [rebase](https://git-scm.com/book/en/v2/Git-Branching-Rebasing) to avoid merge conflicts.

### Setup

Clone your fork, then install the dev dependencies:
```bash
composer install
```

It is advised to run a Laravel/Vanilla PHP application locally, and pull in your local fork of the package to test.
Check [this post](https://johnbraun.blog/posts/creating-a-laravel-package-1) for a guide on how to do it.

## How to implement a country from scratch

### Step 1 - Research

Let's imagine we want to implement the fictional european country of "Socratia".<br>The first thing we should do
is find out what its two-digit ISO code is. After that, we need to *thoroughly* research and find out what the 
Socratia's National Identification Number is (henceforth referred to as NIN) and how it is validated, as well as 
collect some example specimens to then write our tests. Additionally, we should find out if the NIN encodes any personal 
information on the Citizen - gender, date of birth or place of birth.

After some imaginary googling we find out that:
- The two-digit ISO code is `SC`.
- The NIN is eleven characters long excluding hyphens.
- The NIN follows the format `GR-DDDDDDDD-C`, G a letter for gender, R a letter for region, the Ds
  are numbers representing a date and C is a control number.
- `G` can either be "M" or "F".
- `R` can be "P", "J" or "R" referring to its three regions of "Phpilia", "Javardia" and "Rustara".
- `C` must be an even number, but it can not be "2" if the citizen was registered after 2001 as those particular 
  numbers have been phased out.

Clearly there is some citizen information encoded in that ID. This means we will implement both a Validator and 
an Extractor.<br>


### Step 2 - Implement the Validator

Let's start working on our implementation. First we need to create the validator class. <br>
Create a directory in `src/Core/Europe/` named "Socratia" and create a new PHP class inside it.
We'll name it `SocratiaIdvalidator` and implement `IdValidator`:

```php
<?php

namespace Reducktion\Socrates\Core\Europe\Socratia;

use Reducktion\Socrates\Contracts\IdValidator;

class SocratiaIdValidator implements IdValidator
{
    // Implementing the `IdValidator` means we will need to implement the `validate` method
    public function validate(string $id): bool
    {
        // TODO implement
    }
}
```

We follow a "fail early" approach, so we first make sure
that the ID is not invalid before any further computations. <br>
Let's go ahead and do that now:

```php
<?php

namespace Reducktion\Socrates\Core\Europe\Socratia;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class SocratiaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);
    }
    
    // We usually create a sanitize method to strip the
    // ID of any separator characters and check its length.
    private function sanitize($string $id): string
    {
        $id = str_replace('-', '', $id);

        $idLength = strlen($id);
        
        // We want an eleven character string after stripping it from hyphens, else
        // we throw one of our internal exceptions.

        if ($idLength !== 11) {
            throw new InvalidLengthException('Socratian NIN', '11', $idLength);
        }
        
        $id = strtoupper($id);

        return $id;
    }
}
```

Great! We can now check the other conditions and implement the rest of the `validate()` method:

```php
public function validate(string $id): bool
{
    $id = $this->sanitize($id);
    
    // Characters #3 to #10 must be digits
    $dateDigits = substr($id, 2, 8);
    if (!is_numeric($dateDigits)) {
        return false;
    }
    
    // `G` can either be "M" or "F"
    $genderCharacter = $id[0];
    if ($genderCharacter !== 'M' && $genderCharacter !== 'F') {
        return false;
    }
    
    // `R` can be "P", "J" or "R"
    $regionCharacter = $id[1];
    if ($regionCharacter !== 'P' && $regionCharacter !== 'J' && $regionCharacter !== 'R') {
        return false;
    }
    
    // `C` must be an even number, but it can not be "2" if the citizen was registered after 2001
    $controlDigit = (int) $id[10];
    $year = (int) substr($dateDigits, 0, 4);
    if ($controlDigit === 2 && $year >= 2001) {
        return false;
    }
    
    if ($controlDigit % 2 !== 0) {
        return false;
    }
    
    // Don't accept silly dates like 2010-99-99
    $month = (int) substr($dateDigits, 4, 2);
    $day = (int) substr($dateDigits, 6, 2);
    try {
        new DateTime("$year-$month-$day");
    } catch (Exception $e) {
        return false;
    }
    
    // The ID is valid!
    return true;
}
```
That's it for our validator!
Now we head off to `Countries.php` and add the class to the validators array. You'll notice it is alphabetically ordered
and organised by the continent, so let's be sure to add it in the right place:

```php
<?php

namespace Reducktion\Socrates\Config;

abstract class Countries
{
    //...
    public static $validators = [
        /**
         * Validators for european countries.
         */
        'AL' => \Reducktion\Socrates\Core\Europe\Albania\AlbaniaIdValidator::class,
        //...
        'SC' => \Reducktion\Socrates\Core\Europe\Socratia\SocratiaIdValidator::class,
        'SE' => \Reducktion\Socrates\Core\Europe\Sweden\SwedenIdValidator::class,
        //...
```

Let's also add an entry to `Country.php` with our country code for convenience:

```php
<?php

namespace Reducktion\Socrates\Constants;

enum Country: string
{
    /**
     * European countries
     */
    case Albania = 'AL';
    //...
    case Socratia = 'SC';
    case Sweden = 'SE';
    // ...
```

### Step 3 - Implement the Extractor

Now we create an extractor class in the same directory as the validator:

```php
<?php

namespace Reducktion\Socrates\Core\Europe\Socratia;

use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Models\Citizen;

class SocratiaCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        // TODO implement
    }
}
```

Unlike the validator contract, this one requires us to return an instance of `Citizen`.
Citizen is a simple POPO that allows us to return the encoded information in the ID in a consistent format.

To begin, let us first check that the incoming ID is valid by referencing our validator:

```php
public function extract(string $id): Citizen
{
    // We still need to sanitize the ID! Assume the same method as in the validator.
    $id = $this->sanitize($id);

    if (! (new SocratiaIdValidator())->validate($id)) {
        // Throw one of our internal exceptions
        throw new InvalidIdException();
    }
    
    // We are good to go
}
```

A good practice is to segment each piece logic for each type of information in its own method.
If you recall from above, we can extract the gender, date of birth and place of birth from the Socratian ID.
Our `extract` method then becomes:

```php
public function extract(string $id): Citizen
{
    $id = $this->sanitize($id);

    if (! (new SocratiaIdValidator())->validate($id)) {
        // Throw one of our internal exceptions
        throw new InvalidIdException();
    }
    
    $gender = $this->getGender($id);
    $dateOfBirth = $this->getDateOfBirth($id);
    $placeOfBirth = $this->getPlaceOfBirth($id);

    $citizen = new Citizen();
    $citizen->setGender($gender);
    $citizen->setDateOfBirth($dateOfBirth);
    $citizen->setPlaceOfBirth($placeOfBirth);

    return $citizen;
}
```

Let us do each in order. Getting the gender is simple. We can return the Gender enum. Because we have already validated the ID we
can be confident that the gender character is safe to check:

```php
public function getGender(string $id): Gender
{
    return $id[0] === 'M' ? Gender::Male : Gender::Female;
}
```

Now for the date:

```php
public function getDateOfBirth(string $id): DateTime
{
    $dateDigits = substr($id, 2, 8);
    
    $year = (int) substr($dateDigits, 0, 4);
    $month = (int) substr($dateDigits, 4, 2);
    $day = (int) substr($dateDigits, 6, 2);
    
    return new DateTime("$year-$month-$day");
}
```

For the place of birth let us create a separate class in the same directory to hold all possible region values:

```php
<?php

namespace Reducktion\Socrates\Core\Europe\Socratia;

class SocratiaRegionsList
{
    public static array $regions = [
        'P' => 'Phpilia',
        'J' => 'Javardia',
        'R' => 'Rustaria',
    ];
}
```

Getting the place of birth is now as simple as:

```php
private function getPlaceOfBirth(string $id): string
{
    $regionCharacter = $id[1];

    if (! isset(SocratiaRegionsList::$regions[$regionCharacter])) {
        // Throw another of our internal exceptions
        throw new UnrecognisedPlaceOfBirthException(
            "The provided character '$regionCharacter' does not match any regions."
        );
    }

    return SocratiaRegionsList::$regions[$regionCharacter];
  }
```

Finally, as before, let us register our extractor class in the extractors array in `Countries.php`:

```php
<?php

namespace Reducktion\Socrates\Config;

abstract class Countries
{
    //...
    public static $extractors = [
        /**
         * Extractors for european countries.
         */
        'AL' => \Reducktion\Socrates\Core\Europe\Albania\AlbaniaCitizenInformationExtractor::class,
        //...
        'SC' => \Reducktion\Socrates\Core\Europe\Socratia\SocratiaCitizenInformationExtractor::class,
        'SE' => \Reducktion\Socrates\Core\Europe\Sweden\SwedenCitizenInformationExtractor::class,
        //...
```

That's it! We have implemented both the validator and the extractor for our fictional country.
However, we are not done yet as we need to...

### Step 4 - Write Tests

We want to ensure the quality of the algorithm and the extracted data. To do so, we will now write a test class.
First head over to `tests/Feature/Europe` and create a new `SocratiaTest` test class. We will extend our `FeatureTest`
abstract class:

```php
<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Reducktion\Socrates\Tests\Feature\FeatureTest;

class SocratiaTest extends FeatureTest
{
    public function test_extract_behaviour(): void
    {
        // TODO implement
    }
    
    public function test_validation_behaviour(): void
    {
        // TODO implement
    }
}
```

To stay consistent with our other tests, let's create two fields in our class: one for people and another one holding
invalid ids. We shall initialize them in the `setUp()` method:

```php
private $people; // This would be $validIds if Socratia only had a validator
private $invalidIds;

protected function setUp(): void
{
    parent::setUp();
    
    // Make this an array of arrays.
    $this->people = [
        'alexandre' => [
            'id' => 'MR-19940916-4',
            'gender' => Gender::Male,
            'dob' => new DateTime('1994-09-16'),
            'age' => 26, // as of 2021
            'pob' => 'Rustaria',
        ],
        // ...
    ];
    
    // Try to cover as many edge cases as possible
    $this->invalidIds = [
        'OR-19940916-4', // Gender is wrong
        'MZ-19940916-4', // Place of birth does not exist
        'MR-19941916-4', // Date of birth is invalid
        'MR-19940916-3', // Control digit is not even
        'MR-20020916-3', // Control digit is two but the citizen was born post 2001
        'MR-020916-3', // ID is too short
        // ...
    ];
}
```

Now for each of those two data sets we run our validator and extractor classes and check if all is well.
Because we are extending our own `TestCase` class, we have access to an instance of Socrates via the `$this->socrates` call:

```php
public function test_extract_behaviour(): void
{
    foreach ($this->people as $person) {
        $citizen = $this->socrates->getCitizenDataFromId($person['id'], Country::Socratia);

        self::assertEquals($person['gender'], $citizen->getGender());
        self::assertEquals($person['dob'], $citizen->getDateOfBirth());
        self::assertEquals($person['age'], $citizen->getAge());
        self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
    }

    $this->expectException(InvalidIdException::class);

    // An invalid ID should not be able to be extracted
    $this->socrates->getCitizenDataFromId('OR-19940916-4', Country::Socratia);
}

public function test_validation_behaviour(): void
{
    foreach ($this->people as $person) {
        self::assertTrue(
            $this->socrates->validateId($person['id'], Country::Socratia)
        );
    }

    foreach ($this->invalidIds as $fc) {
        self::assertFalse(
            $this->socrates->validateId($fc, Country::Socratia)
        );
    }

    $this->expectException(InvalidLengthException::class);

    $this->socrates->validateId('OR-940916-4', Country::Socratia);
}
```

### Final step - Update the documentation

Finally we just need to update our `COUNTRIES.md` file for reference:

```text
| Country                   | Country Code |     Validation     |     Extraction     |
|---------------------------|--------------|--------------------|--------------------|
| Albania 🇦🇱                |      AL      | :heavy_check_mark: | :heavy_check_mark: |
(...)
| Socratia 🏴               |      SC      | :heavy_check_mark: | :heavy_check_mark: |
| Sweden 🇸🇪                 |      SE      | :heavy_check_mark: | :heavy_check_mark: |
```

Congratulations! Now go ahead and implement a real country! 😊