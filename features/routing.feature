Feature: Public navigation
  Visit public site content
  As an anonimous user
  I am able to see all frontend sections

  Scenario: Visit home
    Given I am on "http://localserver"
    Then I should see "Site/Index view"

  Scenario: Visit task list
    Given I am on "http://localserver"
    Then I should see "Tasks"
    And I follow "Tasks"
    Then I should see "Create task"
    And the page title should be "Tasks list"

  Scenario: Visit create task page
    Given I am on "http://localserver"
    Then I should see "Tasks"
    And I follow "Tasks"
    Then I should see "Create task"
    And I follow "Create task"
    Then I should see "Guardar"

  Scenario: Visit edit task page
    Given I am on "http://localserver"
    Then I should see "Tasks"
    And I follow "Tasks"
    Then I follow "Edit task"
    Then I should see "Guardar"
    And the page title should be "^Edit Task:"