@block @block_evaluation
Feature: The evaluation block allow you to see what evaluation there are and Add the evaluation block to user default dashboard
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
      | teacher2 | Teacher   | 2        | teacher2@example.com | MA          |
      | student1 | Student   | 1        | student1@example.com | STUD        |
      | student2 | Student   | 2        | student2@example.com | STUD        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
      | teacher2 | C1     | editingteacher |
      | student2 | C1     | student        |
    And the following "activities" exist:
      | activity | name                               | course | idnumber  | timeopen      | timeclose    |
      | feedback | Lehrevaluation Dozent/in: teacher1 | C1     | feedback0 | ##yesterday## | ##tomorrow## |
      | feedback | Lehrevaluation Dozent/in: teacher2 | C1     | feedback1 | ##yesterday## | ##tomorrow## |
    And I log in as "admin"
    And I navigate to "Plugins > Blocks > Evaluation block" in site administration
    And I set the following fields to these values:
      | menus_block_evaluation_settings_timeopenmday     | 1    |
      | menus_block_evaluation_settings_timeopenmon      | 1    |
      | menus_block_evaluation_settings_timeopenyear     | 2020 |
      | menus_block_evaluation_settings_timeopenhours    | 0    |
      | menus_block_evaluation_settings_timeopenminutes  | 0    |
      | menus_block_evaluation_settings_timeclosemday    | 1    |
      | menus_block_evaluation_settings_timeclosemon     | 1    |
      | menus_block_evaluation_settings_timecloseyear    | 2030 |
      | menus_block_evaluation_settings_timeclosehours   | 0    |
      | menus_block_evaluation_settings_timecloseminutes | 0    |
    And I press "Save changes"
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    And I add the "Evaluation block" block
    And I configure the "Overview of evaluations" block
    And I set the following fields to these values:
      | Region | content |
      | Weight | -9      |
    And I press "Save changes"
    And I wait until the page is ready
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    And I press "Continue"
    And I log out

  @javascript
  Scenario: Teacher sees correct evaluation link and data with zero responses
    #teacher1 only sees own evaluations
    When I log in as "teacher1"
    Then I should see "Trainer"
    And I should see "Lehrevaluation Dozent/in: teacher1" in the "#evalteach" "css_element"
    And I should not see "Lehrevaluation Dozent/in: teacher2" in the "#evalteach" "css_element"
    #Link for evaluation correct
    And the "href" attribute of "#evalteach a" "css_element" should contain "/mod/feedback/view.php"
    #2 students enrolled, no filled out evaluations
    And I should see "2" in the "#evalteach" "css_element"
    And I should see "0" in the "#evalteach" "css_element"
    And I log out

  @javascript
  Scenario: Teacher sees evaluations from multiple courses correctly
    Given the following "courses" exist:
      | fullname | shortname | category |
      | Course 2 | C2        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C2     | editingteacher |
      | student1 | C2     | student        |
    And the following "activities" exist:
      | activity | name                               | course | idnumber  | timeopen      | timeclose    |
      | feedback | Lehrevaluation Dozent/in: teacher1 | C2     | feedback2 | ##yesterday## | ##tomorrow## |
    When I log in as "teacher1"
    Then I should see "Trainer"
    #both evaluations visible after second course has been created
    And I should see "Course 1" in the "#evalteach" "css_element"
    And I should see "Course 2" in the "#evalteach" "css_element"
    And I should not see "Lehrevaluation Dozent/in: teacher2" in the "#evalteach" "css_element"
    And I should see "1" in the "#evalteach" "css_element"
    And I log out
