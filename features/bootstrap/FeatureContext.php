<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context
{
    /**
     * @AfterStep
     */
    public function takeScreenShotAfterFailedStep(AfterStepScope $scope)
    {
        if ($scope->getTestResult()->getResultCode() === 99) {
            $this->takeScreenShot('test_failed_');
        }
    }

    /**
     * @Given /^the page title should be (?P<pattern>"(?:[^"]|\\")*")$/
     */
    public function thePageTitleShouldBe($pattern)
    {
        $titleElement = $this->getSession()->getPage()->find('css', 'head title');
        if ($titleElement === null) {
            throw new Exception('Page title element was not found!');
        } else {
            $title = $titleElement->getText();
            if ( ! (bool)preg_match($pattern, $title)) {
                throw new Exception("Incorrect title! Expected:$pattern | Actual:$title ");
            }
        }
    }

    protected function driverSupportsJavascript()
    {
        $driver = $this->getSession()->getDriver();

        return ($driver instanceof Selenium2Driver);
    }

    public function takeScreenShot(string $filename = null)
    {
        if (empty($filename)) {
            $filename = 'test_';
        }
        $filename .= date('dmyHis');

        if ($this->driverSupportsJavascript()) {
            $this->saveScreenshot($filename.'.png', realpath(__DIR__.'/../../screenshots'));
        }
    }
}
