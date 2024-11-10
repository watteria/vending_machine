Feature: Get avaiable change coins from the API

  Scenario: Fetch all avaiable change coins
    Given the database contains this coins:
      """
      [
        {
          "coin_id": "123h",
          "valid_for_change": 1,
          "quantity": 10,
          "coin_value": 0.65
        },
        {
          "coin_id": "456i",
          "valid_for_change": 1,
          "quantity": 5,
          "coin_value": 1.25
        },
        {
          "coin_id": "789j",
          "valid_for_change": 0,
          "quantity": 3,
          "coin_value": 2.00
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
        "valid_for_change": 1,
        "quantity": 0,
        "coin_value": 0.0
      }
      """
    And the response JSON should be equal to:
    """
      [
        {
          "coin_id": "123h",
          "valid_for_change": 1,
          "quantity": 10,
          "coin_value": 0.65
        },
        {
          "coin_id": "456i",
          "valid_for_change": 1,
          "quantity": 5,
          "coin_value": 1.25
        }
      ]
      """