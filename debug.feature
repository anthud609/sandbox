Feature: Debug Homepage
  Background:
    Given I am on homepage
    Then I should see the page content

  Scenario: Check what's on homepage
    Then I should see "Welcome"