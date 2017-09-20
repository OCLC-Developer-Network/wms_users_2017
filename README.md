WMS User Group 2017 preconference 2017 Application
=============
This is a demonstration application written to how to interact with OCLC web service in PHP. It uses best programming practices like 
- dependency management
- object-oriented programming
- model view controller (MVC) code structures
- unit testing
- behavior driven functional testing
- continous integration 

## Installation

### Step 1: Install from GitHub

In a Terminal Window

```bash
$ cd {yourGitHomeDirectory}
$ git clone https://github.com/OCLC-Developer-Network/wms_users_2017.git
$ cd wms_users_2017
```

### Step 2: Use composer to install the dependencies

Mac:

```bash
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar install
```

Windows:

1. Download and run [Composer-Setup.exe](https://getcomposer.org/doc/00-intro.md#installation-windows)
2. Run this command in your wms_users_2017 directory

```bash
$ composer install
```

[Composer](https://getcomposer.org/doc/00-intro.md) is a dependency management library for PHP. It is used to install the required libraries for testing and parsing RDF data. The dependencies are configured in the file `composer.json`.

### Step 3: Configure your environment file with your WSKey/secret and other info

```bash
$ cp app/config/test_config.yml app/config/config.yml
$ vi app/config/config.yml
```

Edit the following values
- wskey
- secret
- principalID
- principalIDNS
- institution


## Usage

### Start the built-in PHP web server
```bash
$ php -S localhost:9090
```
### View the application
Point your web browser at the localhost address where these instructions will install it by default. 

[http://localhost:9090](http://localhost:9090)

## Running Tests

### Unit Tests
From the command line run

```bash
$ vendor/bin phpunit
```

### Behavior Driven Functional Tests

#### Start the built-in PHP server
```bash
$ php -S localhost:9090 features/test.php
```

#### Run tests
```bash
$ vendor/bin behat
```

## How this was built

For a step by step tutorial on this application see the [tutorial section](https://github.com/OCLC-Developer-Network/wms_users_2017/tree/master/tutorial)

