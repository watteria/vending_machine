Feature: Update a coin

  Scenario: Successfully update a coin
    Given I have the following JSON itemData:
      """
      {
        "coin_id": "dcf175ba-51c0-4af8-bd43-b644647d26f8",
        "quantity": 10,
        "coin_value": "0.65",
        "valid_for_change": true
      }
      """
    And the database contains this coins:
      """
      [
        {
          "coin_id": "dcf175ba-51c0-4af8-bd43-b644647d26f8",
          "quantity": 5,
          "coin_value": 0.65,
          "valid_for_change": 1
        },
        {
          "coin_id": "8ce8181e-bfab-421e-8512-ff0f4fc33480",
          "quantity": 5,
          "coin_value": 1.25,
          "valid_for_change": 1
        },
        {
          "coin_id": "169e0801-11e0-4e90-a248-804fa8641d63",
          "quantity": 3,
          "coin_value": 2.00,
          "valid_for_change": 0
        }
      ]
      """
    When I send a POST request to "/api/coins/dcf175ba-51c0-4af8-bd43-b644647d26f8"
    Then the status code should be 201
    And the response body should contain the message "Coin updated"

  Scenario: Fail to update an coin due to missing valid_for_change
    Given I have the following JSON itemData:
      """
      {
        "coin_id": "dcf175ba-51c0-4af8-bd43-b644647d26f8",
        "quantity": 10,
        "coin_value": "0.65",
        "valid_for_change":  null
      }
      """
    When I send a POST request to "/api/coins/dcf175ba-51c0-4af8-bd43-b644647d26f8"
    Then the status code should be 200
    And the response body should contain the message "value should not be blank"


  Scenario: Fail to update an coin due to missing quantity
    Given I have the following JSON itemData:
      """
      {
        "coin_id": "afdf5da8-9f0e-4b14-8507-fa902f78ce50",
        "quantity": null,
        "coin_value": "0.65",
        "valid_for_change": true
      }
      """
    When I send a POST request to "/api/coins/afdf5da8-9f0e-4b14-8507-fa902f78ce50"
    Then the status code should be 200
    And the response body should contain the message "value should not be blank"


  Scenario: Fail to update an coin due to missing coin_value
    Given I have the following JSON itemData:
      """
      {
        "coin_id": "dcde56ad-926c-4bc0-a1b0-633dcbb51087",
        "quantity": 1,
        "coin_value": null,
        "valid_for_change": true
      }
      """
    When I send a POST request to "/api/coins/dcde56ad-926c-4bc0-a1b0-633dcbb51087"
    Then the status code should be 200
    And the response body should contain the message "value should not be blank"


  Scenario: Fail to update an item due to invalid coin_value
    Given I have the following JSON itemData:
      """
      {
        "coin_id": "c8d98267-d5b8-40b9-a690-6303e1ec1fbd",
        "quantity": 10,
        "coin_value": "invalidValue",
        "valid_for_change": true
      }
      """
    When I send a POST request to "/api/coins/c8d98267-d5b8-40b9-a690-6303e1ec1fbd"
    Then the status code should be 200
    And the response body should contain the message "is not a valid coin value"


  Scenario: Fail to update an item due to invalid quantity
    Given I have the following JSON itemData:
      """
      {
        "coin_id": "0f103be6-a23a-4888-ac60-5e7e5865beaa",
        "quantity": 10,
        "coin_value": "16.20",
        "valid_for_change": "1sdfsd0"
      }
      """
    When I send a POST request to "/api/coins/0f103be6-a23a-4888-ac60-5e7e5865beaa"
    Then the status code should be 200
    And the response body should contain the message "is not a valid boolean"
