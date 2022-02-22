# inquirer-php (immature)
![Code Coverage Badge](./coverage-badge.svg)

This aims to be something equivalent-_ish_ to [Inquirer.js](https://github.com/SBoudrias/Inquirer.js/) or 
[python-inquirer](https://github.com/magmax/python-inquirer).

## Status
This is in a **very** immature status — please do not use in anything serious (yet).

## Usage
```php
<?php

namespace Aaron\InquirerPhp;

require_once __DIR__ . '/../vendor/autoload.php';

$cli = new Cli();

$questions = [
    new Question\Text('name', 'What\'s your name?'),
    new Question\Confirm('continue', 'Should we continue?'),
    new Question\Select('tshirt_size', 'What\'s your t-shirt size?', ['s', 'm', 'l', 'xl']),
];

$answers = $cli->prompt($questions);

var_dump($answers);
```