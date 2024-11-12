Feature: Get coins from the API

  Scenario: Fetch all coins
    Given the database contains multiple coins
    When I send a GET request to "/api/coins"
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