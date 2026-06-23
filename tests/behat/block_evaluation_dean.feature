@block @block_evaluation
Feature: The evaluation block allows the dean to see what evaluation there are
  In order to see the evaluations in my course category on dashboard
  As an dean
  I can see the evaluation block in my dashboard
  i can see How many course enrolments as participants and how many responses in the course evaluation

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
      | dean1    | Dean      | 1        | dean1@example.com |
      | dean2    | Dean      | 2        | dean2@example.com |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
      | student1 | Student   | 1        | student1@example.com |
      | student2 | Student   | 2        | student2@example.com |
    And the following "roles" exist:
      | name | shortname | description               | archetype |
      | Dean | dean      | Dean of a course category | manager   |
    And the following "role assigns" exist:
      | user  | role | contextlevel       | reference |
      | dean1 | dean | Category           | CAT1      |
      | dean2 | dean | Category           | CAT2      |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | teacher        |
      | student1 | C1     | student        |
      | student2 | C1     | student        |
    And the following "activities" exist:
      | activity   | name                               | course | idnumber  | timeopen      | timeclose    |
      | feedback   | Lehrevaluation Dozent/in: teacher1 | C1     | feedback0 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher2 | C1     | feedback1 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher3 | C2     | feedback2 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher4 | C2     | feedback3 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher5 | C3     | feedback4 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher6 | C3     | feedback5 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher7 | C4     | feedback6 | ##yesterday## | ##tomorrow## |
      | feedback   | Lehrevaluation Dozent/in: teacher8 | C4     | feedback7 | ##yesterday## | ##today## |
    And I am on the "Lehrevaluation Dozent/in: teacher1" "feedback activity" page logged in as admin
    And I navigate to "Questions" in current page administration
    And I add a "Multiple choice" question to the feedback with:
        | Question               | What is your favourite instrument |
        | Label                  | instrument1                       |
        | Multiple choice type   | Multiple choice - single answer   |
        | Multiple choice values | drums \nguitar \nhurdygurdy           |
    And I log out


  @javascript
  Scenario: Add the evaluation block to user default dashboard and view as dean1 and dean2
	in course C1 we have 2 enrolments as participants and 1 answer in Lehrevaluation Dozent/in: teacher1
	in all other courses we habe 0 enrolments as participants and 0 answers

	# student1 answer in Course "C1" the activity feedback with name "Lehrevaluation Dozent/in: teacher1"
    Given I am on the "Lehrevaluation Dozent/in: teacher1" "feedback activity" page logged in as student1
    When I follow "Answer the questions"
    And I set the field "drums" to "1"
    And I press "Submit your answers"
    And I press "Continue"
	And I log out
	# admin set parameters in settings "Evaluation Block"
	Given I log in as "admin"
    And I navigate to "Plugins > Blocks > Evaluation block" in site administration
    And I set the following fields to these values:
      | menus_block_evaluation_settings_timeopenmday     | 1                      |
      | menus_block_evaluation_settings_timeopenmon      | ## -1 month ## %B ##   |
      | menus_block_evaluation_settings_timeopenyear     | ## -0 year ## %Y ##    |
      | menus_block_evaluation_settings_timeopenhours    | 0                      |
      | menus_block_evaluation_settings_timeopenminutes  | 0                      |
      | menus_block_evaluation_settings_timeclosemday    | 1                      |
      | menus_block_evaluation_settings_timeclosemon     | ## +1 month ## %B ##   |
      | menus_block_evaluation_settings_timecloseyear    | ## -0 year ## %Y ##    |
      | menus_block_evaluation_settings_timeclosehours   | 0                      |
      | menus_block_evaluation_settings_timecloseminutes | 0                      |
      | s_block_evaluation_faqurl                        | https://www.moodle.org |
      | s_block_evaluation_settings_deanrolename         | dean                   |
    And I press "Save changes"
	# admin aktivate "Evaluation block" in Default-Dashboard
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
	# dean of studies "dean1" see all "Evaluation" in there coursecategories
	# Lehrevaluation Dozent/in: teacher1  2 enrolments 1 answer
	# Lehrevaluation Dozent/in: teacher2  2 enrolments 0 answer
	# Lehrevaluation Dozent/in: teacher3  0 enrolments 0 answer
	# Lehrevaluation Dozent/in: teacher4  0 enrolments 0 answer
	# no more Lehrevaluation
    When I log in as "dean1"
    And I follow "Dashboard"
    Then I should see "Dean of studies" in the ".block_evaluation" "css_element"
    And I should see "Lehrevaluation Dozent/in: teacher1" in the ".block_evaluation" "css_element"
	And I should see "Lehrevaluation Dozent/in: teacher2" in the ".block_evaluation" "css_element"
    But I should not see "Lehrevaluation Dozent/in: teacher5" in the ".block_evaluation" "css_element"
    And "a[target='_blank'][href='https://www.moodle.org']" "css_element" should exist
    And "Lehrevaluation Dozent/in: teacher1" row "Total participants" column of "evaldean" table should contain "2"
    And "Lehrevaluation Dozent/in: teacher1" row "Finished?" column of "evaldean" table should contain "1"
    And "Lehrevaluation Dozent/in: teacher2" row "Total participants" column of "evaldean" table should contain "2"
    And "Lehrevaluation Dozent/in: teacher2" row "Finished?" column of "evaldean" table should contain "0"
    And "Lehrevaluation Dozent/in: teacher3" row "Total participants" column of "evaldean" table should contain "0"
    And "Lehrevaluation Dozent/in: teacher3" row "Finished?" column of "evaldean" table should contain "0"
    And "Lehrevaluation Dozent/in: teacher4" row "Total participants" column of "evaldean" table should contain "0"
    And "Lehrevaluation Dozent/in: teacher4" row "Finished?" column of "evaldean" table should contain "0"
	And I log out
	# dean of studies "dean2" see all "Evaluation" in there coursecategories
	# Lehrevaluation Dozent/in: teacher5  0 enrolments 0 answer
	# Lehrevaluation Dozent/in: teacher6  0 enrolments 0 answer
	# Lehrevaluation Dozent/in: teacher7  0 enrolments 0 answer
	# Lehrevaluation Dozent/in: teacher8  0 enrolments 0 answer
	# no more Lehrevaluation
    And I log in as "dean2"
    And I follow "Dashboard"
    And I should see "Lehrevaluation Dozent/in: teacher5" in the ".block_evaluation" "css_element"
    And I should see "Lehrevaluation Dozent/in: teacher6" in the ".block_evaluation" "css_element"
    But I should not see "Lehrevaluation Dozent/in: teacher1" in the ".block_evaluation" "css_element"
    And I should not see "No feedbacks"
    And "a[target='_blank'][href='https://www.moodle.org']" "css_element" should exist
    And I log out
