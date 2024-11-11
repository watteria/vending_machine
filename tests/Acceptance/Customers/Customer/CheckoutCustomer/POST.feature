Feature: Customer Checkout
  In order to process customer checkout correctly
  As an API user
  I want to ensure that CheckoutCustomerController handles different scenarios

  Scenario: Successful checkout with sufficient money and in-stock product
    Given the database contains this customer:
    """
      {
        "customer_id": "3f487129-f7b9-4bbf-b6f3-7925c433d635",
        "id_product": "00fc1a51-c702-3e6e-95ea-b34f4669dcd3",
        "inserted_money": [
          {
            "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
            "quantity": 1,
            "coin_value": 0.25,
            "valid_for_change": 1
          },
          {
            "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
            "valid_for_change": 0,
            "quantity": 1,
            "coin_value": 1
          }
        ],
        "status": "PROCESSING",
        "remaining_machine_coins":  [
          {
            "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
            "valid_for_change": 1,
            "quantity": 20,
            "coin_value": 0.25
          },
          {
            "coin_id": "3a990a45-bd5c-41a7-82e8-c9b21a581220",
            "valid_for_change": 1,
            "quantity": 20,
            "coin_value": 0.05
          },
          {
            "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
            "valid_for_change": 0,
            "quantity": 12,
            "coin_value": 1
          },
          {
            "coin_id": "e48b2473-8562-432e-8305-4293be72056d",
            "valid_for_change": 1,
            "quantity": 20,
            "coin_value": 0.1
          }
        ]
      }
      """
    And I have the following JSON itemData:
      """
      {
        "customer_id": "3f487129-f7b9-4bbf-b6f3-7925c433d635",
        "id_product": "00fc1a51-c702-3e6e-95ea-b34f4669dcd3",
        "inserted_money": [
           {
            "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
            "quantity": 1,
            "coin_value": 0.25,
            "valid_for_change": 1
          },
          {
            "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
            "valid_for_change": 0,
            "quantity": 1,
            "coin_value": 1
          }
        ]
      }
      """
    And the database contains this coins:
      """
      [
        {
          "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.25
        },
        {
          "coin_id": "3a990a45-bd5c-41a7-82e8-c9b21a581220",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.05
        },
        {
          "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
          "valid_for_change": 0,
          "quantity": 12,
          "coin_value": 1
        },
        {
          "coin_id": "e48b2473-8562-432e-8305-4293be72056d",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.1
        }
      ]
      """
    Given the database contains this item:
      """
      {
        "item_id": "00fc1a51-c702-3e6e-95ea-b34f4669dcd3",
        "product_name": "Water",
        "quantity": 1,
        "price": "0.65"
      }
      """
    When I send a POST request to "/api/customers/checkout/3f487129-f7b9-4bbf-b6f3-7925c433d635"
    Then the response body should contain one of the following status:
    """
    {
      "status": ["return", "nothing_to_return"]
    }
    """
    And the status code should be 200

  Scenario: Error when product is out of stock
    Given the database contains this customer:
    """
      {
        "customer_id": "3f487129-f7b9-4bbf-b6f3-7925c433d635",
        "id_product": "00fc1a51-c702-3e6e-95ea-b34f4669dcd3",
        "inserted_money": [
          {
            "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
            "quantity": 1,
            "coin_value": 0.25,
            "valid_for_change": 1
          },
          {
            "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
            "valid_for_change": 0,
            "quantity": 1,
            "coin_value": 1
          }
        ],
        "status": "PROCESSING",
        "remaining_machine_coins":  [
          {
            "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
            "valid_for_change": 1,
            "quantity": 20,
            "coin_value": 0.25
          },
          {
            "coin_id": "3a990a45-bd5c-41a7-82e8-c9b21a581220",
            "valid_for_change": 1,
            "quantity": 20,
            "coin_value": 0.05
          },
          {
            "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
            "valid_for_change": 0,
            "quantity": 12,
            "coin_value": 1
          },
          {
            "coin_id": "e48b2473-8562-432e-8305-4293be72056d",
            "valid_for_change": 1,
            "quantity": 20,
            "coin_value": 0.1
          }
        ]
      }
      """

    And I have the following JSON itemData:
      """
      {
        "customer_id": "3f487129-f7b9-4bbf-b6f3-7925c433d635",
        "id_product": "00fc1a51-c702-3e6e-95ea-b34f4669dcd3",
        "inserted_money": [
           {
            "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
            "quantity": 1,
            "coin_value": 0.25,
            "valid_for_change": 1
          },
          {
            "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
            "valid_for_change": 0,
            "quantity": 1,
            "coin_value": 1
          }
        ]
      }
      """
    And the database contains this coins:
      """
      [
        {
          "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.25
        },
        {
          "coin_id": "3a990a45-bd5c-41a7-82e8-c9b21a581220",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.05
        },
        {
          "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
          "valid_for_change": 0,
          "quantity": 12,
          "coin_value": 1
        },
        {
          "coin_id": "e48b2473-8562-432e-8305-4293be72056d",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.1
        }
      ]
      """
    Given the database contains this item:
      """
      {
        "item_id": "00fc1a51-c702-3e6e-95ea-b34f4669dcd3",
        "product_name": "Water",
        "quantity": 0,
        "price": "0.65"
      }
      """
    When I send a POST request to "/api/customers/checkout/3f487129-f7b9-4bbf-b6f3-7925c433d635"
    Then the response body should contain the message "ERROR: This Product is out of stock"
    And the status code should be 200

  Scenario: Error when insufficient money is inserted
    Given the database contains this customer:
    """
      {
        "customer_id": "3f487129-f7b9-4bbf-b6f3-7925c433d635",
        "id_product": "00fc1a51-c702-3e6e-95ea-b34f4669dcd3",
        "inserted_money": [
          {
            "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
            "quantity": 1,
            "coin_value": 0.25,
            "valid_for_change": 1
          },
          {
            "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
            "valid_for_change": 0,
            "quantity": 1,
            "coin_value": 1
          }
        ],
        "status": "PROCESSING",
        "remaining_machine_coins":  [
          {
            "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
            "valid_for_change": 1,
            "quantity": 20,
            "coin_value": 0.25
          },
          {
            "coin_id": "3a990a45-bd5c-41a7-82e8-c9b21a581220",
            "valid_for_change": 1,
            "quantity": 20,
            "coin_value": 0.05
          },
          {
            "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
            "valid_for_change": 0,
            "quantity": 12,
            "coin_value": 1
          },
          {
            "coin_id": "e48b2473-8562-432e-8305-4293be72056d",
            "valid_for_change": 1,
            "quantity": 20,
            "coin_value": 0.1
          }
        ]
      }
      """

    And I have the following JSON itemData:
      """
      {
        "customer_id": "3f487129-f7b9-4bbf-b6f3-7925c433d635",
        "id_product": "00fc1a51-c702-3e6e-95ea-b34f4669dcd3",
        "inserted_money": [
           {
            "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
            "quantity": 1,
            "coin_value": 0.25,
            "valid_for_change": 1
          },
          {
            "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
            "valid_for_change": 0,
            "quantity": 1,
            "coin_value": 1
          }
        ]
      }
      """
    And the database contains this coins:
      """
      [
        {
          "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.25
        },
        {
          "coin_id": "3a990a45-bd5c-41a7-82e8-c9b21a581220",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.05
        },
        {
          "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
          "valid_for_change": 0,
          "quantity": 12,
          "coin_value": 1
        },
        {
          "coin_id": "e48b2473-8562-432e-8305-4293be72056d",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.1
        }
      ]
      """
    Given the database contains this item:
      """
      {
        "item_id": "00fc1a51-c702-3e6e-95ea-b34f4669dcd3",
        "product_name": "Water",
        "quantity": 1,
        "price": "6000000005"
      }
      """
    When I send a POST request to "/api/customers/checkout/3f487129-f7b9-4bbf-b6f3-7925c433d635"
    Then the response body should contain the message "ERROR: You haven't inserted enought money"
    And the status code should be 200

  Scenario: Error when no product is selected
    Given the database contains this customer:
    """
      {
        "customer_id": "3f487129-f7b9-4bbf-b6f3-7925c433d635",
        "id_product": "00fc1a51-c702-3e6e-95ea-b34f4669dcd3",
        "inserted_money": [
          {
            "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
            "quantity": 1,
            "coin_value": 0.25,
            "valid_for_change": 1
          },
          {
            "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
            "valid_for_change": 0,
            "quantity": 1,
            "coin_value": 1
          }
        ],
        "status": "PROCESSING",
        "remaining_machine_coins":  [
          {
            "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
            "valid_for_change": 1,
            "quantity": 20,
            "coin_value": 0.25
          },
          {
            "coin_id": "3a990a45-bd5c-41a7-82e8-c9b21a581220",
            "valid_for_change": 1,
            "quantity": 20,
            "coin_value": 0.05
          },
          {
            "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
            "valid_for_change": 0,
            "quantity": 12,
            "coin_value": 1
          },
          {
            "coin_id": "e48b2473-8562-432e-8305-4293be72056d",
            "valid_for_change": 1,
            "quantity": 20,
            "coin_value": 0.1
          }
        ]
      }
      """

    And I have the following JSON itemData:
      """
      {
        "customer_id": "3f487129-f7b9-4bbf-b6f3-7925c433d635",
        "id_product": "",
        "inserted_money": [
           {
            "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
            "quantity": 1,
            "coin_value": 0.25,
            "valid_for_change": 1
          },
          {
            "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
            "valid_for_change": 0,
            "quantity": 1,
            "coin_value": 1
          }
        ]
      }
      """
    And the database contains this coins:
      """
      [
        {
          "coin_id": "3991b21f-3d6f-43bd-899c-a53bc5d2da13",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.25
        },
        {
          "coin_id": "3a990a45-bd5c-41a7-82e8-c9b21a581220",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.05
        },
        {
          "coin_id": "e25b1559-1fca-4e5b-b6be-c4e761a13064",
          "valid_for_change": 0,
          "quantity": 12,
          "coin_value": 1
        },
        {
          "coin_id": "e48b2473-8562-432e-8305-4293be72056d",
          "valid_for_change": 1,
          "quantity": 20,
          "coin_value": 0.1
        }
      ]
      """
    Given the database contains this item:
      """
      {
        "item_id": "00fc1a51-c702-3e6e-95ea-b34f4669dcd3",
        "product_name": "Water",
        "quantity": 1,
        "price": "6000000005"
      }
      """
    When I send a POST request to "/api/customers/checkout/3f487129-f7b9-4bbf-b6f3-7925c433d635"
    And the response body should contain the message "ERROR: No Product Selected"
    And the status code should be 200