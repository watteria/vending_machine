Feature: Delete an item

  Scenario: Successfully delete an item
    Given the database contains this item:
      """
      {
        "item_id": "3a990a45-bd5c-41a7-82e8-c9b21a581220",
        "product_name": "Water",
        "quantity": 10,
        "price": "0.65"
      }
      """
    When I send a DELETE request to "/api/items/3a990a45-bd5c-41a7-82e8-c9b21a581220"
    Then the status code should be 200
    And the response body should contain the message "Item deleted"


  Scenario: Try to delete an inexistent item
    Given the database contains this item:
      """
      {
        "item_id": "e48b2473-8562-432e-8305-4293be72056d",
        "product_name": "Water",
        "quantity": 10,
        "price": "0.65"
      }
      """
    When I send a DELETE request to "/api/items/3a990a45-bd5c-41a7-82e8-c9b21a581220"
    Then the status code should be 500
