# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 13

#### Unit testing code
1. Make sure you are in the base project directory
2. Run the unit tests 
```bash
$ vendor/bin phpunit
```

#### Testing our application by hand via a web browser
1. Make sure you are in the base project directory
2. Start the built-in PHP web server
```bash
$ php -S localhost:9090
```
3. Go to web browser to view application

#### Automating Acceptance testing
1. Create a directory called features
2. In the features directory create folder called bootstrap
3. In features/bootstrap create FeatureContext.php
4. Creating mocks
    1. In features directory create folder called mocks
    2. Create your mocks 
    - Copy test_mocks from Github into this directory
    3. In features directory create file test.php 
5. Writing Tests
    1. Write a test for rendering the Search form
    2. Write a test for submitting a Search via the form
    3. Write a test for viewing a specific bibliographic record
6. Running Tests
    1. Start the built-in PHP server
    ```bash
    $ php -S localhost:9090 features/test.php
    ```
    2. Run tests
    ```bash
    $ vendor/bin behat
    ```
