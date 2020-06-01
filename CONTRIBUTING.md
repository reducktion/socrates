# CONTRIBUTING

Contributions are welcome, and are accepted via pull requests.
Please review these guidelines before submitting any pull requests.

## Process

1. Fork the project
1. Create a new branch
1. Code, test, commit and push
1. Open a pull request detailing your changes.
1. Describe what your pull request is (change, bugfix, new country implementation, etc.)

## Guidelines

* We use PSR-12 as our coding style, make sure to follow it.
* If you have multiple commits, squashing some of them might help us have a better sense of what you did.
* You may need to [rebase](https://git-scm.com/book/en/v2/Git-Branching-Rebasing) to avoid merge conflicts.

## Setup

Clone your fork, then install the dev dependencies:
```bash
composer install
```

It is avised to run a Laravel/Vanilla PHP application locally, and pull in your local fork of the package to test.
Check [this post](https://johnbraun.blog/posts/creating-a-laravel-package-1) for a guide on how to do it.
