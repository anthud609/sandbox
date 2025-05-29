Feature: User Authentication
  In order to access protected content
  As a user
  I need to be able to login and logout

  Background:
    Given I am on the homepage

  Scenario: User can access login page
    When I click "Login"
    Then I should be on "/login"
    And I should see "Login"
    And I should see "Email:"
    And I should see "Password:"

  Scenario: User can login with valid credentials
    Given I am on "/login"
    When I fill in "email" with "test@example.com"
    And I fill in "password" with "password123"
    And I press "Login"
    Then I should be on "/"
    And I should see "Welcome back, testuser!"
    And I should see "Logout"

  Scenario: User cannot login with invalid credentials
    Given I am on "/login"
    When I fill in "email" with "wrong@example.com"
    And I fill in "password" with "wrongpassword"
    And I press "Login"
    Then I should be on "/login"
    And I should see "Invalid email or password"

  Scenario: User cannot login with missing email
    Given I am on "/login"
    When I fill in "password" with "password123"
    And I press "Login"
    Then I should be on "/login"
    And I should see "Email and password are required"

  Scenario: User can logout
    Given I am logged in as "test@example.com" with password "password123"
    When I click "Logout"
    Then I should be on "/"
    And I should see "Welcome to the site"
    And I should see "Login"