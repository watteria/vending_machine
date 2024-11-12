Feature: Create a Customer

  Scenario: Successfully create an customer
    Given I have the following JSON itemData:
      """
      {
        "id_product":  "00fc1a51-c702-3e6e-95ea-b34f4669dcd3",
        "inserted_money": [
          {
            "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
            "quantity": 1,
            "coin_value": 0.25,
            "valid_for_change": true
          },
          {
            "coin_id": "3a990a45-bd5c-41a7-82e8-c9b21a581220",
            "quantity": 6,
            "coin_value": 0.05,
            "valid_for_change": false
          }
        ]
      }
      """
    And the database contains this coins:
      """
      [
        {
          "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
          "quantity": 20,
          "coin_value": 0.25,
          "valid_for_change": 1
        },
        {
          "coin_id": "3a990a45-bd5c-41a7-82e8-c9b21a581220",
          "quantity": 20,
          "coin_value": 0.05,
          "valid_for_change": 1
        },
        {
          "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
          "quantity": 12,
          "coin_value": 1,
          "valid_for_change": 0
        },
        {
          "coin_id": "e48b2473-8562-432e-8305-4293be72056d",
          "quantity": 20,
          "coin_value": 0.1,
          "valid_for_change": 1
        }
      ]
      """
    When I send a POST request to "/api/customers"
    Then the status code should be 201
    And the response body should contain the message "Customer created"
    And the response body should contain "customer_id": "exists"
    And the response body should contain "totalMonedas": "exists"
