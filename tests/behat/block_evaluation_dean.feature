@block @block_evaluation
Feature: The evaluation block allows the dean to see what evaluation there are
  In order to see the evaluations in my course category on dashboard
  As an dean
  I can see the evaluation block in my dashboard

  Background:
    Given the following "categories" exist:
      | name  | category | idnumber |
      | Cat 1 | 0        | CAT1     |
      | Cat 2 | 0        | CAT2     |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | CAT1     |
      | Course 2 | C2        | CAT1     |
      | Course 3 | C3        | CAT2     |
      | Course 4 | C4        | CAT2     |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | dean1 | Dean   | 1        | dean1@example.com |
      | dean2 | Dean   | 1        | dean2@example.com |
    And the following "roles" exist:
      | name | shortname | description               | archetype |
      | Dean | dean      | Dean of a course category | manager   |
    And the following "role assigns" exist:
      | user  | role | contextlevel       | reference |
      | dean1 | dean | Category           | CAT1      |
      | dean2 | dean | Category           | CAT2      |
    And the following "activities" exist:
      | activity   | name                               | course | idnumber  | timeopen      | timeclose    |
      | feedback   | Lehrevaluation Dozent/in: teacher1 | C1     | feedback0 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher2 | C1     | feedback1 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher3 | C2     | feedback2 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher4 | C2     | feedback3 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher5 | C3     | feedback4 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher6 | C3     | feedback5 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher7 | C4     | feedback6 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher8 | C4     | feedback7 | ##yesterday## | ##tomorrow## |

  @javascript
  Scenario: Add the evaluation block to user default dashboard and view as dean
    Given I log in as "admin"
    And I navigate to "Plugins > Blocks > Evaluation block" in site administration
    And I set the following fields to these values:
      | menus_block_evaluation_settings_timeopenmday     | 1                      |
      | menus_block_evaluation_settings_timeopenmon      | 1                      |
      | menus_block_evaluation_settings_timeopenyear     | 2020                   |
      | menus_block_evaluation_settings_timeopenhours    | 0                      |
      | menus_block_evaluation_settings_timeopenminutes  | 0                      |
      | menus_block_evaluation_settings_timeclosemday    | 1                      |
      | menus_block_evaluation_settings_timeclosemon     | 1                      |
      | menus_block_evaluation_settings_timecloseyear    | 2030                   |
      | menus_block_evaluation_settings_timeclosehours   | 0                      |
      | menus_block_evaluation_settings_timecloseminutes | 0                      |
      | s_block_evaluation_faqurl                        | https://www.moodle.org |
    And I press "Save changes"
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    And I add the "Evaluation block" block
    And I configure the "Overview of evaluations" block
    And I set the following fields to these values:
      | Region | content |
      | Weight | -9      |
    And I press "Save changes"
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    And I press "Continue"
    And I log out
    When I log in as "dean1"
    And I follow "Dashboard"
    Then I should see "Dean of studies" in the ".block_evaluation" "css_element"
    And I should see "Lehrevaluation Dozent/in: teacher1" in the ".block_evaluation" "css_element"
    And I should see "Lehrevaluation Dozent/in: teacher2" in the ".block_evaluation" "css_element"
    But I should not see "Lehrevaluation Dozent/in: teacher5" in the ".block_evaluation" "css_element"
    And "a[target='_blank'][href='https://www.moodle.org']" "css_element" should exist
    And I log out
    And I log in as "dean2"
    And I follow "Dashboard"
    And I should see "Lehrevaluation Dozent/in: teacher5" in the ".block_evaluation" "css_element"
    And I should see "Lehrevaluation Dozent/in: teacher6" in the ".block_evaluation" "css_element"
    But I should not see "Lehrevaluation Dozent/in: teacher1" in the ".block_evaluation" "css_element"
    And "a[target='_blank'][href='https://www.moodle.org']" "css_element" should exist
