<?php
namespace Omni\Core\Modules\Auth\Tests\Feature\Bootstrap;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;

class FeatureContext extends MinkContext implements Context
{
    /**
     * @Given I am on the home page
     */
    public function iAmOnTheHomePage()
    {
        $this->visit('/');
    }

    /**
     * @Given I am logged in as :email with password :password
     */
    public function iAmLoggedInAs($email, $password)
    {
        $this->visit('/login');
        $this->fillField('email', $email);
        $this->fillField('password', $password);
        $this->pressButton('Login');
    }

    /**
     * @When I click :link
     */
    public function iClick($link)
    {
        $this->clickLink($link);
    }

    /**
     * Debug method to see page content
     * @Then I should see the page content
     */
    public function iShouldSeeThePageContent()
    {
        echo "\n--- PAGE CONTENT ---\n";
        echo $this->getSession()->getPage()->getContent();
        echo "\n--- END PAGE CONTENT ---\n";
    }
}