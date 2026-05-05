@block @block_evaluation
Feature: The evaluation block allow you to see what evaluation there are
  In order to enable the evaluation block on the user dashboard
  As an admin
  I can add the evaluation block to all user's dashboard

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "users" exist:
      | username | firstname | lastname | email                | institution |
      | teacher1 | Teacher   | 1        | teacher1@example.com | MA          |
      | teacher2 | Teacher   | 1        | teacher2@example.com | MA          |
      | student1 | Student   | 1        | student1@example.com | STUD        |
      | student2 | Student   | 2        | student2@example.com | STUD        |

    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
      | teacher2 | C1     | editingteacher |
      | student2 | C1     | student        |

    And the following "activities" exist:
      | activity   | name                               | course | idnumber  | timeopen      | timeclose    |
      | feedback   | Lehrevaluation Dozent/in: teacher1 | C1     | feedback0 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher2 | C1     | feedback1 | ##yesterday## | ##tomorrow## |

  @javascript
  Scenario: Add the evaluation block to user default dashboard
    Given I log in as "admin"
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    And I add the "Evaluation Block" block
    And I configure the "Overview of evaluations" block
    And I set the following fields to these values:
      | Region | content |
      | Weight | -9      |
    And I press "Save changes"
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    And I press "Continue"
    And I log out
    When I log in as "student1"
    And I follow "Dashboard"
    Then I should see "Lehrevaluation Dozent/in: teacher1" in the ".block_evaluation" "css_element"
    And I should see "Lehrevaluation Dozent/in: teacher2" in the ".block_evaluation" "css_element"
    And I log out
    And I log in as "teacher1"
    And I follow "Dashboard"
    And I should see "Lehrevaluation Dozent/in: teacher1" in the ".block_evaluation" "css_element"
    But I should not see "Lehrevaluation Dozent/in: teacher2" in the ".block_evaluation" "css_element"
