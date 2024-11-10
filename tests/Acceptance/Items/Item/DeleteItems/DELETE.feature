Feature: Delete an item

  Scenario: Successfully delete an item
    Given the database contains this item:
      """
      {
        "item_id": "1235",
        "product_name": "Water",
        "quantity": 10,
        "price": "0.65"
      }
      """
    When I send a DELETE request to "/api/items/1235"
    Then the status code should be 200
    And the response body should contain the message "Item deleted"


  Scenario: Try to delete an inexistent item
    Given the database contains this item:
      """
      {
        "item_id": "1234",
        "product_name": "Water",
        "quantity": 10,
        "price": "0.65"
      }
      """
    When I send a DELETE request to "/api/items/1235"
    Then the status code should be 500
