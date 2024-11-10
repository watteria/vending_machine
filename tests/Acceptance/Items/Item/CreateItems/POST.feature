Feature: Create an item

  Scenario: Successfully create an item
    Given I have the following JSON itemData:
      """
      {
        "product_name": "Water",
        "quantity": 10,
        "price": "0.65"
      }
      """
    When I send a POST request to "/api/items"
    Then the status code should be 201
    And the response body should contain the message "Item created"

  Scenario: Fail to create an item due to missing name
    Given I have the following JSON itemData:
      """
      {
        "product_name": null,
        "quantity": 10,
        "price": "0.65"
      }
      """
    When I send a POST request to "/api/items"
    Then the status code should be 200
    And the response body should contain the message "This value should not be blank."

  Scenario: Fail to create an item due to invalid price
    Given I have the following JSON itemData:
      """
      {
        "product_name": "Water",
        "quantity": 10,
        "price": "invalidPrice"
      }
      """
    When I send a POST request to "/api/items"
    Then the status code should be 200
    And the response body should contain the message "The value invalidPrice is not a valid price value."
