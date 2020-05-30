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

<p>**Socrates** is a PHP Package that allows you to validate and retrieve personal data from most [National Identification Numbers](https://en.wikipedia.org/wiki/National_identification_number)  in Europe, with the goal of eventually supporting as many countries in the world as possible.</p>
<p>Some countries also encode personal information of the citizen, such as gender or the place of birth. This package allows you to extract that information in a consistent way.</p>

Our goals:
* Standardize and centralise what is usually very difficult and sparse information to find.
* Have a consistent API for retrieving citizen information from an ID, if available.
* Test each individual country validation and data extraction algorithm with a number of valid and invalid IDs.