Feature: Total Amount Calculation
  In order to verify that the API correctly calculates the total amount
  I want to ensure that the TotalAmountController returns the correct total in the JSON response

  Scenario: Calculate total amount from multiple coin data
    Given the database contains this coins:
      """
      [
        {
          "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.25
        },
        {
          "coin_id": "3a990a45-bd5c-41a7-82e8-c9b21a581220",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.05
        },
        {
          "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
          "valid_for_change": 0,
          "quantity": 12,
          "coin_value": 1
        },
        {
          "coin_id": "e48b2473-8562-432e-8305-4293be72056d",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.1
        }
      ]
      """
    When I send a GET request to "/api/coins/total"
    Then the response body should contain "total": "exists"
    And the status code should be 200
    And the response JSON should be equal to:
    """
      {
        "total": "20"
      }
      """
