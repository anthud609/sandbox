<?php

declare(strict_types=1);

namespace Omni\Core\Modules\Auth\Tests\Feature\Bootstrap;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;

class FeatureContext extends MinkContext implements Context
{
    /**
     * Navigate to the home page.
     *
     * @Given I am on the home page
     */
    public function iAmOnTheHomePage()
    {
        $this->visit('/');
    }

    /**
     * Log in using the provided email and password.
     *
     * @param mixed $email
     * @param mixed $password
     *
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
     * Click on the specified link.
     *
     * @param mixed $link
     *
     * @When I click :link
     */
    public function iClick($link)
    {
        $this->clickLink($link);
    }

    /**
     * Print the full page content for debugging purposes.
     *
     * @Then I should see the page content
     */
    public function iShouldSeeThePageContent()
    {
        echo "\n--- PAGE CONTENT ---\n";
        echo $this->getSession()->getPage()->getContent();
        echo "\n--- END PAGE CONTENT ---\n";
    }
}
