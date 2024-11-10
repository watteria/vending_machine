Feature: Update a coin

  Scenario: Successfully update a coin
    Given I have the following JSON itemData:
      """
      {
        "coin_id": "123",
        "valid_for_change": 1,
        "quantity": 10,
        "coin_value": "0.65"
      }
      """
    And the database contains this coins:
      """
      [
        {
          "coin_id": "123a",
          "valid_for_change": 1,
          "quantity": 5,
          "coin_value": 0.65
        },
        {
          "coin_id": "456b",
          "valid_for_change": 1,
          "quantity": 5,
          "coin_value": 1.25
        },
        {
          "coin_id": "789c",
          "valid_for_change": 0,
          "quantity": 3,
          "coin_value": 2.00
        }
      ]
      """
    When I send a POST request to "/api/coins/123"
    Then the status code should be 201
    And the response body should contain the message "Coin updated"

  Scenario: Fail to update an coin due to missing valid_for_change
    Given I have the following JSON itemData:
      """
      {
        "coin_id": "123",
        "valid_for_change":  null,
        "quantity": 10,
        "coin_value": "0.65"
      }
      """
    When I send a POST request to "/api/coins/123"
    Then the status code should be 200
    And the response body should contain the message "value should not be blank"


  Scenario: Fail to update an coin due to missing quantity
    Given I have the following JSON itemData:
      """
      {
        "coin_id": "123",
        "valid_for_change": 1,
        "quantity": null,
        "coin_value": "0.65"
      }
      """
    When I send a POST request to "/api/coins/123"
    Then the status code should be 200
    And the response body should contain the message "value should not be blank"


  Scenario: Fail to update an coin due to missing coin_value
    Given I have the following JSON itemData:
      """
      {
        "coin_id": "123",
        "valid_for_change": 1,
        "quantity": 1,
        "coin_value": null
      }
      """
    When I send a POST request to "/api/coins/123"
    Then the status code should be 200
    And the response body should contain the message "value should not be blank"


  Scenario: Fail to update an item due to invalid coin_value
    Given I have the following JSON itemData:
      """
      {
        "coin_id": "123",
        "valid_for_change": 1,
        "quantity": 10,
        "coin_value": "invalidValue"
      }
      """
    When I send a POST request to "/api/coins/123"
    Then the status code should be 200
    And the response body should contain the message "is not a valid coin value"


  Scenario: Fail to update an item due to invalid quantity
    Given I have the following JSON itemData:
      """
      {
        "coin_id": "123",
        "valid_for_change": "1sdfsd0",
        "quantity": 10,
        "coin_value": "16.20"
      }
      """
    When I send a POST request to "/api/coins/123"
    Then the status code should be 200
    And the response body should contain the message "is not a valid interger"
