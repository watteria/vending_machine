Feature: Get items from the API

  Scenario: Fetch all items
    Given the database contains multiple items
    When I send a GET request to "/api/items"
    Then the status code should be 200
    And the response body should contain a list of item
    And each "item" in the response should contain the fields:
    """
      {
        "item_id": "",
        "product_name": "",
        "quantity": 0,
        "price": 0.0
      }
    """
