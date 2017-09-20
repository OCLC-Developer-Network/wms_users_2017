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

#### Create mocks
1. In features directory create folder called mocks
2. Copy test_mocks from Github into this directory
3. In features directory create file test.php 

#### Writing Tests
1. Write a test for rendering the Search form
```
  Scenario: Successfully View Search form
    When I go to "/"
    Then I should see "Search by OCLC Number" in the "h1" element
    And I should see 1 "form" elements
    And I should see 1 "input[name=oclcnumber]" elements
    And I should see 1 "input[name=search]" elements
```    
2. Write a test for submitting a Search via the form
```
  Scenario: Successfully Submit Search
    When I go to "/"
    And I fill in "oclcnumber" with "70775700"
    And I press "Search"
    Then I should see "Dogs and cats" in the "div#content > h1" element
    And I should see "Raw MARC" in the "div#record > h4" element 
    And I should see 1 "div#raw_record pre" elements
```    
3. Write a test for viewing a specific bibliographic record
```
  Scenario: Successfully View Bib
    When I go to "/bib/70775700"
    Then I should see "Dogs and cats" in the "div#content > h1" element
    And I should see "Raw MARC" in the "div#record > h4" element 
    And I should see 1 "div#raw_record pre" elements
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