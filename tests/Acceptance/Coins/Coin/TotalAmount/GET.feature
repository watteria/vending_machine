Feature: Total Amount Calculation
  In order to verify that the API correctly calculates the total amount
  I want to ensure that the TotalAmountController returns the correct total in the JSON response

  Scenario: Calculate total amount from multiple coin data
    Given the database contains this coins:
      """
      [
        {
          "coin_id": "a9525521-7d5a-4159-b448-cf1c73cd1192",
          "quantity": 20,
          "coin_value": 0.25,
          "valid_for_change": 1
        },
        {
          "coin_id": "bf9d169b-605f-49b3-a462-fb4a131e2fde",
          "quantity": 20,
          "coin_value": 0.05,
          "valid_for_change": 1
        },
        {
          "coin_id": "b942d9a9-2d03-409c-838e-4d790b73c29f",
          "quantity": 12,
          "coin_value": 1,
          "valid_for_change": 0
        },
        {
          "coin_id": "28b406c2-3115-44ba-b435-02989232940f",
          "quantity": 20,
          "coin_value": 0.1,
          "valid_for_change": 1
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
