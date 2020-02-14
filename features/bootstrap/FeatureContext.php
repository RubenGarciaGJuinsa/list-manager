<?php

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
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
            if (!(bool) preg_match($pattern, $title)) {
                throw new Exception("Incorrect title! Expected:$pattern | Actual:$title ");
            }
        }
    }
}
