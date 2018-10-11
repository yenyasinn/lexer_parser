# Lexer & Parser service with field formatter for Drupal 8

## Prerequisites

- Docker
- amazee.io/Lagoon local Docker development environment (http://lagoon.readthedocs.io/en/latest/using_lagoon/local_development_environments/)

## Installation

First, you need to clone this repository.

    git clone https://github.com/yenyasinn/lexer_parser.git

Then, you need to build the images

    docker-compose build

Then, start the containers:

    docker-compose up -d

Once started, connect to the cli container of Drupal and install Drupal.

    docker-compose exec cli bash
    composer install
    drush si config_installer -y --account-name=admin --account-pass=admin

Site will be available by address http://drupal-varnish.drupal-decoupled-app.docker.amazee.io.

## Lexer & Parser service.

Now you can create Article page within Drupal.

Put in Body field of Article node any mathematical expression that uses basic operators (+, -, *, /).

E.g.: “10 + 20 - 30 + 15 * 5”

## PHP Unit tests.

To run PHP Unit tests that cover Lexer & Parser service run:

    docker-compose exec cli bash
    vendor/bin/phpunit -c web/core web/modules/custom/lexer_parser/tests/src/Unit
  

   

