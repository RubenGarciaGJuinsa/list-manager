<?php

use Almacen\Core\Application;
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
     * @Then Take Screenshot
     */

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

    protected function driverSupportsJavascript()
    {
        $driver = $this->getSession()->getDriver();

        return ($driver instanceof Selenium2Driver);
    }

    /**
     * @BeforeScenario @db_test
     */
    public function swapDatabase()
    {
        echo "Swap DB";
        $app = Application::getInstance();
        $app->setDbConfig([
            'class' => '\Kata\Database',
            'dbFile' => 'test_database.sqlite'
        ]);
        $app->initDb(true);

        /** @var \Almacen\core\Db\DbApplicationInterface $db */
        $db = \Almacen\Core\Db\Db::getInstance();

        $path = realpath(__DIR__.'/../../docker/migration/');
        $files = glob($path.'/*.sql');

        foreach ($files as $file) {
            $db->executeFile($file);
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
            $title = $titleElement->getHtml();
            if ( ! (bool)preg_match($pattern, $title)) {
                throw new Exception("Incorrect title! Expected:$pattern | Actual:$title ");
            }
        }
    }

    /**
     * @Then /^(?:|I )follow "([^"]*)" in the same row as "([^"]*)"$/
     */
    public function iFollowInTheSameRowAs($element, $sibling)
    {
        $page = $this->getSession()->getPage();
        $referenceElement = $page->find('named', ['content', $sibling]);
        $row = $referenceElement->find('xpath', 'ancestor::tr');
        $row->find('named', ['link', $element])->click();
    }

    /**
     * @Then /^(?:|I )confirm dialog$/
     */
    public function confirmDialog()
    {
        $this->getSession()->getDriver()->getWebDriverSession()->accept_alert();
    }


}
