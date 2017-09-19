# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 15 - Continous Integration

1. Create a file called .travis.yml
2. Open file and add configuration information
	1. language code is in
	2. versions of php to test against
	3. install commands
	4. commands to run before script
	5. script(s) to run for test purposes
	6. notifications configuration

```php
language: php
# list any PHP version you want to test against
php:
# using major version aliases
# aliased to a recent 5.6.x version
- 5.6
#alias to a recent 7.0.x version
- 7.0

install:
    - composer install

before_script:
  - php -S localhost:9090 features/test.php &
  - sleep 3

# omitting "script:" will default to phpunit
script: 
    - vendor/bin/phpunit --configuration phpunit.xml
    - vendor/bin/behat

# configure notifications (email, IRC, campfire etc)
notifications:
    email:
        recipients:
        - coombsk@oclc.org
        on_success: always
        on_failure: always
```
3. Turn your project on in Travis-CI
    1. Login with a Github login
    2. Add a new repository
        1. Choose from your Github repositories
    3. Configure settings
        1. when to run builds
        2. Environment variables
    4. Activate