Feature: Test Task
  Create, update and delete tasks
  As an anonimous user
  I am able to use the task CRUD

  Background:
    Given I am on "http://localserver"
    Then print current URL
    Then I should see "Tasks"
    And I follow "Tasks"

    @create_task
    @javascript
  Scenario: Add task
    Then I should see "Create task"
    And I follow "Create task"
