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
1. Open a pull request detailing your changes.
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
some specimens to then write our tests. Additionally, we should find out if the NIN encodes any personal 
information on the Citizen - gender, date of birth or place of birth.

After some imaginary googling we find out that:
- The two-digit ISO code is `SC`.
- The NIN is eleven characters long excluding hyphens.
- The NIN follows the format `GR-DDDDDDDD-C`, G a letter for gender, R a letter for region, the Ds
  are numbers representing a date and C is a control number.
- `G` can either be "M", "F" or "O".
- `R` can be "P", "J" or "R" referring to its three regions of "Phpilia", "Javardia" and "Rustara".
- `C` must be an even number, but it can not be "2" if the citizen was registered after 2001 as those particular 
  numbers have been phased out.

Clearly there is some citizen information encoded in that ID. This means we will implement both a Validator and 
an Extractor.<br>


### Step 2 - Implement the Validator

Armed with this knowledge let's start working on our implementation!
First let's head off to `Countries.php` and add it to the array. You'll notice it is alphabetically ordered 
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
        'RS' => \Reducktion\Socrates\Core\Europe\Serbia\SerbiaIdValidator::class,
        'SC' => \Reducktion\Socrates\Core\Europe\Socratia\SocratiaIdValidator::class,
        'SE' => \Reducktion\Socrates\Core\Europe\Sweden\SwedenIdValidator::class,
        //...
```

Wonderful! Now let's go ahead and create the class we just referenced in that array. <br>
Create a directory in `src/Core/Europe/` named "Socratia" and create a new PHP class inside it.
We'll name it `SocratiaIdvalidator` and implement `IdValidator`:

```php
<?php

namespace Reducktion\Socrates\Core\Europe\Socratia;

use Reducktion\Socrates\Contracts\IdValidator;

class SocratiaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        // TODO implement
    }
}
```

Implementing the `IdValidator` means we will need to implement that simple `validate` method to conform to our 
internal API.
Let's begin with some validations. We follow a "fail early" approach, so we first make sure
that the ID is not invalid before any further computations. <br>
We usually create a `sanitize()` method to strip the
ID of any separator characters (if it makes sense) and check its length.
If you recall, we want an eleven character string after stripping it from hyphens. If that is not the case, we
will throw one of our internal exceptions.
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
    
    private function sanitize($string $id): string
    {
        $id = str_replace('-', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 11) {
            throw new InvalidLengthException('Socratian NIN', '11', $idLength);
        }

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
    
    // `G` can either be "M", "F" or "O"
    $genderCharacter = $id[0];
    if ($genderCharacter !== 'M' && $genderCharacter !== 'F' && $genderCharacter !== 'O') {
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