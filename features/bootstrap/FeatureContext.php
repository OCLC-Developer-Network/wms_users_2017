<?php
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;

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
