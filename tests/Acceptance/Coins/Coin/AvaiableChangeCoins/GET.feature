Feature: Get avaiable change coins from the API

  Scenario: Fetch all avaiable change coins
    Given the database contains this coins:
      """
      [
        {
          "coin_id": "a4e63075-8c72-4c33-925b-208f14022b99",
          "quantity": 10,
          "coin_value": 0.65,
          "valid_for_change": 1
        },
        {
          "coin_id": "8b99fa34-2a65-4224-be7d-9c185b36ff44",
          "quantity": 5,
          "coin_value": 1.25,
          "valid_for_change": 1
        },
        {
          "coin_id": "ef67534d-3c5d-4f70-bde3-85f74c947714",
          "quantity": 3,
          "coin_value": 2.00,
          "valid_for_change": 0
        }
      ]
      """
    When I send a GET request to "/api/coins/avaiable_change"
    Then the status code should be 200
    And the response body should contain a list of coin
    And each "coin" in the response should contain the fields:
      """
      {
        "coin_id": "",
        "quantity": 0,
        "coin_value": 0.0,
        "valid_for_change": true
      }
      """
    And the response JSON should contain exactly:
    """
      [
        {
          "coin_id": "a4e63075-8c72-4c33-925b-208f14022b99",
          "quantity": 10,
          "coin_value": 0.65,
          "valid_for_change": true
        },
        {
          "coin_id": "8b99fa34-2a65-4224-be7d-9c185b36ff44",
          "quantity": 5,
          "coin_value": 1.25,
          "valid_for_change": true
        }
      ]
      """