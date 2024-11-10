Feature: Update an item

  Scenario: Successfully update an item
    Given the database contains this item:
      """
      {
        "item_id": "123a",
        "product_name": "Water",
        "quantity": 20,
        "price": "0.65"
      }
      """
    And I have the following JSON itemData:
      """
      {
        "item_id": "123a",
        "product_name": "Water",
        "quantity": 10,
        "price": "0.65"
      }
      """
    When I send a POST request to "/api/items/123a"
    Then the status code should be 201
    And the response body should contain the message "Item updated"

  Scenario: Fail to update an item due to missing name
    Given the database contains this item:
      """
      {
        "item_id": "123b",
        "product_name": "Water",
        "quantity": 20,
        "price": "0.65"
      }
      """
    And I have the following JSON itemData:
      """
      {
        "item_id": "123b",
        "product_name": null,
        "quantity": 10,
        "price": "invalidPrice"
      }
      """
    When I send a POST request to "/api/items/123b"
    Then the status code should be 200
    And the response body should contain the message "This value should not be blank."

  Scenario: Fail to update an item due to invalid price
    Given the database contains this item:
      """
      {
        "item_id": "123c",
        "product_name": "Water",
        "quantity": 20,
        "price": "0.65"
      }
      """
    And I have the following JSON itemData:
      """
      {
        "item_id": "123c",
        "product_name": "Water",
        "quantity": 10,
        "price": null
      }
      """
    When I send a POST request to "/api/items/123c"
    Then the status code should be 200
    And the response body should contain the message "The value invalidPrice is not a valid price value."


