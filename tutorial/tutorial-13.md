# A Beginner's Guide to Working with WorldShare APIs
## OCLC WMS Global Community + User Group Meeting 2017: Pre-Conference Workshop
### Tutorial Part 13 - Automating Acceptance testing

#### Configure the test environment
1. Create a file behat.yml
2. Open behat.yml
3. Add configuration information
    1. setup test suite
        1. path to features
        2. contexts to use
    2. Enable extensions
        1. base_url for site
        2. browser driver
```php
# behat.yml     
default:
  suites:
    default:
      path: %paths.base%/features
      contexts:
        - FeatureContext
        - Behat\MinkExtension\Context\MinkContext  
  extensions:
    Behat\MinkExtension:
      base_url: http://localhost:9090/
      sessions:
        default:
            goutte: ~
```
4. Create a directory called features
5. In the features directory create folder called bootstrap
6. In features/bootstrap create FeatureContext.php
7. Open FeatureContext.php
8. Add use statements for class you want to call
```php
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
```
9. Create Feature Context class which extends RawMinkContext and implements Context
```php
class FeatureContext extends RawMinkContext implements Context
{
```
10. Add a constructor
```php
    public function __construct()
    {
        
    }
```

#### Create and use mocks
1. In features directory create folder called mocks
2. Copy test_mocks from Github into this directory
3. In features directory create file test.php 
```php
<?php
require_once(__DIR__ . '/../vendor/autoload.php');
\VCR\VCR::turnOn();
\VCR\VCR::configure()->setCassettePath(__DIR__ . '/mocks');
\VCR\VCR::configure()->enableRequestMatchers(array('method', 'url', 'host'));
\VCR\VCR::insertCassette('test_mocks');

session_start();

// instantiate the App object
global $config_file; 
$config_file = file_get_contents(__DIR__ . '/../app/config/test_config.yml');
$config = require __DIR__ . '/../app/settings.php';

$app = new \Slim\App($config);

// Set up dependencies
require __DIR__ . '/../app/dependencies.php';
// Register middleware
require __DIR__ . '/../app/middleware.php';
// Register routes
require __DIR__ . '/../app/routes.php';

// Get container
$container = $app->getContainer();
    
// Run application
$app->run();
```

#### Writing Tests
1. Write a feature to test rendering the Search form
    1. In features directory, create file searchForm.feature
    2. Add test code
    ```
    Feature: View Search Form
      As a library cataloger
      I want to view the search form
      so that search for an OCLC Number
      
      Scenario: Successfully View Search form
        When I go to "/"
        Then I should see "Search by OCLC Number" in the "h1" element
        And I should see 1 "form" elements
        And I should see 1 "input[name=oclcnumber]" elements
        And I should see 1 "input[name=search]" elements
    ```    
2. Write a feature to test submitting a Search via the form
    1. In features directory, create file submitSearchForm.feature
    2. Add test code
    ```
    Feature: Submit Search Form
      As a library cataloger
      I want submit a search for an OCLC Number
      so I can view the associated MARC record
      
      Scenario: Successfully Submit Search
        When I go to "/"
        And I fill in "oclcnumber" with "70775700"
        And I press "Search"
        Then I should see "Dogs and cats" in the "div#content > h1" element
        And I should see "Raw MARC" in the "div#record > h4" element 
        And I should see 1 "div#raw_record pre" elements
    ```    
3. Write a feature to test viewing a specific bibliographic record
    1. In features directory, create file viewBib.feature
    2. Add test code
    ```
    Feature: View Bib Record
      As a library cataloger
      I want to view a bib record
      so that I can examine its properties
      
      Scenario: Successfully View Bib
        When I go to "/bib/70775700"
        Then I should see "Dogs and cats" in the "div#content > h1" element
        And I should see "Raw MARC" in the "div#record > h4" element 
        And I should see 1 "div#raw_record pre" elements
    ```
4. Write a feature to test for viewing errors
    1. In features directory, create file viewError.feature
    2. Add test code
    ```
    @error
    Feature: View Error
      As a library cataloger
      I want to view a usable error message when something fails
      so that I can tell support what is wrong
      
      Scenario: Unsuccessfully View Bib - Invalid Token
        When I go to "/bib/401"
        Then I should see "System Error" in the "div#content > h1" element
        And I should see "Status - 401" in the "div#error_content > p#status" element
        And I should see "Message - AccessToken {tk_12345} is invalid" in the "div#error_content > p#message" element
        And I should see "Authorization header: Bearer tk_12345" in the "div#error_content > p#detail" element
    
      Scenario: Unsuccessfully View Bib - Expired Token
        When I go to "/bib/403"
        Then I should see "System Error" in the "div#content > h1" element
        And I should see "Status - 401" in the "div#error_content > p#status" element
        And I should see "Message - AccessToken {tk_12345} has expired" in the "div#error_content > p#message" element
        And I should see "Authorization header: Bearer tk_12345" in the "div#error_content > p#detail" element  
        
      Scenario: Unsuccessfully View Bib - Unknown OCLC Number
        When I go to "/bib/9999999999"
        Then I should see "System Error" in the "div#content > h1" element
        And I should see "Status - 404" in the "div#error_content > p#status" element
        And I should see "Unable to locate resource: 9999999999." in the "div#error_content > p#message" element
    ```
#### Running Tests
1. Start the built-in PHP server
```bash
$ php -S localhost:9090 features/test.php
```
2. Run tests
```bash
$ vendor/bin behat
```

**[on to Part 14](tutorial-14.md)**

**[back to Part 12](tutorial-12.md)**