Feature: Total Amount Calculation
  In order to verify that the API correctly calculates the total amount
  I want to ensure that the TotalAmountController returns the correct total in the JSON response

  Scenario: Calculate total amount from multiple coin data
    Given the database contains this coins:
      """
      [
        {
          "coin_id": "123e",
          "valid_for_change": 1,
          "quantity": 10,
          "coin_value": 0.65
        },
        {
          "coin_id": "456f",
          "valid_for_change": 1,
          "quantity": 5,
          "coin_value": 1.25
        },
        {
          "coin_id": "789g",
          "valid_for_change": 0,
          "quantity": 3,
          "coin_value": 2.00
        }
      ]
      """
    When I send a GET request to "/api/coins/total"
    Then the response body should contain "total": "exists"
    And the status code should be 200
    And the response JSON should be equal to:
    """
      {
        "total": "18.75"
      }
      """
