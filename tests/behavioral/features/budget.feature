Feature:

  Scenario: Creating an expense category that does not exist
    Given budget has no "food" expense category
    When an expense "food" category is added
    Then budget should contain category "food"

  Scenario: Creating an expense category that already exists
    Given budget has "food" expense category
    When an expense "food" category is added
    Then adding expense category should have failed
    And budget should contain category "food"

