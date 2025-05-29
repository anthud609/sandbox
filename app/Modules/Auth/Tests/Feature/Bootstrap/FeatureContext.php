<?php
namespace Omni\Core\Modules\Auth\Tests\Feature\Bootstrap;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;

class FeatureContext extends MinkContext implements Context
{
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
     * @Then I should be on :path
     */
    public function iShouldBeOn($path)
    {
        $currentUrl = $this->getSession()->getCurrentUrl();
        $expectedUrl = rtrim($this->getMinkParameter('base_url'), '/') . $path;
        
        if ($currentUrl !== $expectedUrl) {
            throw new \Exception("Expected to be on '$expectedUrl' but was on '$currentUrl'");
        }
    }
}