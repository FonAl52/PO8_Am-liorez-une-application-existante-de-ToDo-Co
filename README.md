# PO8_Améliorez_une_application_existante_de : ToDo & Co

This repository is Allan Fontaine's eighth project for the Openclassrooms PHP/Symfony Developer certificate.

## Prerequisites

- PHP >= 8.1
- Composer
- Symfony CLI

## Installation

1. Clone the repository.
2. Run `composer install`.
3. Copy the `.env` file and configure the database.
4. Run `php bin/console doctrine:databse:create`. 
5. Run migrations: `php bin/console doctrine:migrations:migrate`.
6. Run `php bin/console lexik:jwt:generate-keypair`.
7. Load fixtures if necessary: `php bin/console doctrine:fixtures:load`.

## Launching the Development Server

Run `symfony serve` and access http://localhost:8000