<?php
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Context\Context;

use PHPUnit_Framework_Assert as Assert;

\VCR\VCR::turnOn();
\VCR\VCR::configure()->setCassettePath(__DIR__ . '/../mocks');
\VCR\VCR::configure()->enableRequestMatchers(array('method', 'url', 'host'));

/**
 * Features context.
 */
class FeatureContext implements Context
{
	
	/** @BeforeScenario */
	public function createMock(BeforeScenarioScope $scope)
	{
		$tags = $scope->getScenario()->getTags();
		//find tags that start vcr_
		$mocks = array_filter($tags, function($tag)
		{
			return(strpos($tag, 'vcr_'));
		});
		if ($mocks){
			//load that cassette
			\VCR\VCR::insertCassette($mocks[0]);
		}
	}
	
	/** @AfterScenario */
	public function teardownMock(AfterScenarioScope $scope)
	{
		\VCR\VCR::eject();
	}
	
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
