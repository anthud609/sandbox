<?php
namespace Omni\Core\Modules\Auth\Tests\Feature\Bootstrap;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;

class FeatureContext extends MinkContext implements Context
{
    /**
     * @Given I am on the homepage
     */
    public function iAmOnTheHomepage()
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

    // Removed conflicting step definition - using MinkContext's built-in version
}