# Ivanhoe

A project by the [Praxis Program](http://praxis.scholarslab.org) at the
[Scholars' Lab](http://scholarslab.org).

## Login
Something about the login. Then implements the login.


## Development Setup

### Composer
[Composer](http://getcomposer.org/) is how all the cool kids are install
PHP packages these days. You'll need to read the [Getting Started](https://getcomposer.org/doc/00-intro.md) 
document for your system, but it boils down to this:

#### OS X

```bash
$ curl -sS https://getcomposer.org/installer | php
```

#### Windows
Download and run [Composer-Setup.ext](https://getcomposer.org/Composer-Setup.exe). 
This will set everything up for you.

### PHP_Codesniffer
With [Composer](http://getcomposer.org/) installed, run this from your
terminal:

```bash
$ composer global require 'squizlabs/php_codesniffer=*'
```


### Grunt

You will need to install [nodejs](http://nodejs.org/) in order to help
manage some of the dependencies. After this is installed, open your
terminal/console and navigate to the project directory, and install the
project dependencies:

```bash
$ cd path/to/project
$ npm install
```


