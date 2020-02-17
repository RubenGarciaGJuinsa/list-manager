Feature: Test Task
  Create, update and delete tasks
  As an anonimous user
  I am able to use the task CRUD

  Background:
    Given I am on "http://localserver"
    Then print current URL
    Then I should see "Tasks"
    And I follow "Tasks"


  @task_crud
  @create_task
  @javascript
  Scenario: Add task
    Then I should see "Create task"
    And I follow "Create task"
    When I fill in "Introducir nombre..." with "__BDD_TEST__Nombre de ejemplo"
    And I press "Guardar"
    Then I should see "Tarea creada correctamente!"
    Then I should see "Tasks"
    And I follow "Tasks"
    And I should see "__BDD_TEST__Nombre de ejemplo"

  @task_crud
  @edit_task
  @javascript
  Scenario: Edit task
    Then I should see "__BDD_TEST__Nombre de ejemplo"
    Then I follow "Edit task" in the same row as "__BDD_TEST__Nombre de ejemplo"
    Then the page title should be "Edit Task\: __BDD_TEST__Nombre de ejemplo"
    When I fill in "Introducir nombre..." with "__BDD_TEST__Nombre de ejemplo modificado"
    And I press "Guardar"
    Then I should see "Tarea editada correctamente!"
    Then I should see "Tasks"
    And I follow "Tasks"
    And I should see "__BDD_TEST__Nombre de ejemplo modificado"

  @task_crud
  @delete_task
  @javascript
  Scenario: Delete task
    Then I should see "__BDD_TEST__Nombre de ejemplo modificado"
    Then I follow "Delete task" in the same row as "__BDD_TEST__Nombre de ejemplo modificado"
    And I confirm dialog
    Then I should not see "__BDD_TEST__Nombre de ejemplo modificado"

  @task_crud
  @error_creating_task
  @javascript
  Scenario: Add task
    Then I should see "Create task"
    And I follow "Create task"
    And I press "Guardar"
    Then I should see "The name is required"
    When I fill in "Introducir nombre..." with "12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890"
    And I press "Guardar"
    Then I should see "The max length of the name is 255 characters"

