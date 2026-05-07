@block @block_evaluation
Feature: Basic tests for Evaluation block

  @javascript
  Scenario: Plugin block_evaluation appears in the list of installed additional plugins
    Given I log in as "admin"
    When I navigate to "Plugins > Plugins overview" in site administration
    And I follow "Additional plugins"
    Then I should see "Evaluation block"
    And I should see "block_evaluation"
