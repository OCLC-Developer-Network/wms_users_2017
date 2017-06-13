<?php
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;
use OCLC\Auth\AccessToken;

use PHPUnit_Framework_Assert as Assert;

/**
 * Features context.
 */
class FeatureContext extends RawMinkContext implements Context
{
	
	/** @AfterStep */
	public function errors(AfterStepScope $scope)
	{
		if (99 === $scope->getTestResult()->getResultCode()) {
			print static::showError($scope->getEnvironment()->getContext('Behat\MinkExtension\Context\MinkContext')->getSession());
		}
	}
	
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     */
    public function __construct()
    {
    	
    }
    
    /**
     * @Given I am authenticated
     */
    public function iAmAuthenticated(){
    	
    }
    
    /**
     * @Given I am not following redirects
     */
    public function iAmNotFollowingRedirects(){
    	$this->getSession()->getDriver()->getClient()->followRedirects(false);
    }

    /**
     * @Then /^I should see the following in the repeated "([^"]*)" element within the context of the "([^"]*)" element:$/
     */
    public function assertRepeatedElementContainsText($element, $parentElement, TableNode $table)
    {
        $elementSelector = $parentElement . ' ' . $element;
        $elements = $this->getSession()->getPage()->findAll('css', $elementSelector);
    
        foreach ($table->getHash() as $n => $repeatedElement) {
            $singleElements = $elements[$n];
    
            Assert::assertEquals(
                $singleElements->getText(),
                $repeatedElement['text']
            );
        }
    }
    
    /**
     * @Then /^I should see that "([^"]*)" in "([^"]*)" is selected$/
     */
    public function inShouldBeSelected($optionValue, $select) {
        $selectElement = $this->getSession()->getPage()->find('named', array('select', "\"{$select}\""));
        $optionElement = $selectElement->find('named', array('option', "\"{$optionValue}\""));
        //it should have the attribute selected and it should be set to selected
        assertTrue($optionElement->hasAttribute("selected"));
        assertTrue($optionElement->getAttribute("selected") == "selected");
    }
    
    /**
     * @Then /^the current url should be "([^"]*)"$/
     */
    public function theCurrentURLShouldBe($url)
    {
    	$response_url = $this->getSession()->getCurrentUrl();
    	Assert::assertEquals($url, $response_url);
    }
    
    /**
     * @Then /^the response is a redirect$/
     */
    public function theResponseIsARedirect()
    {
    	Assert::assertEquals($this->getSession()->getStatusCode(), "302");
    }
    
    /**
     * @Then /^the response has a header "([^"]*)" with a value of "([^"]*)"$/
     */
    public function theResponseHasHeaderWithValue($header, $value)
    {
    	$headers = $this->getSession()->getResponseHeaders();
    	Assert::assertEquals($headers['Location'][0], $value);
    }
    
    /**
     * @Then /^the response has a parameter "([^"]*)"$/
     */
    public function theResponseHasAParameter($parameterName)
    {
    	$parsed_response_url = parse_url($this->getSession()->getCurrentUrl());
    	parse_str($parsedUrl['query'], $responseParameters);
    	
    	Assert::assertArrayHasKey($parameterName, $responseParameters);
    }
    
    /**
     * @Then /^the "([^"]*)" parameter equals "([^"]*)"$/
     */
    public function theParameterEquals($parameterName, $parameterValue)
    {
    	$parsed_response_url = parse_url($this->getSession()->getCurrentUrl());
    	parse_str($parsedUrl['query'], $responseParameters);
    	
    	$parameterValue = $this->replacePlaceHolder($parameterValue);
    	Assert::assertEquals($parameterValue, $responseParameters[$parameterName]);
    }
    
    private static function showError($session) {
    	if ($session){
    		$errorMessage = "FAILED: \n";
    		$errorMessage .= "URL: " . $session->getCurrentUrl(). "\n";
    		$errorMessage .= "Response: " . $session->getPage()->getContent() . "\n";
    	} else {
    		$errorMessage = 'Last Page Information Unknown';
    	}
    	return $errorMessage;
    }
}
