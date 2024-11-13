import React from 'react';
import axios from 'axios';

function ItemList({ items, setItems, setMensaje, loading,setAccesoPermitido,updateHttpRequestInfo  }) {


  // Gestiona canvi en un producte
  const handleUpdate = async (item_id, updatedItem) => {
    try {

      const url = `http://localhost:1000/api/items/${item_id}`;
      const response = await axios.post(url, {
        item_id: updatedItem.item_id,
        product_name: updatedItem.product_name,
        price: updatedItem.price,
        quantity: updatedItem.quantity
      });
      if (response.status === 201) {

        updateHttpRequestInfo('POST', url, { updatedItem: updatedItem }, response.data);
        setMensaje('Product updated');
        setItems(items.map(item => (item.item_id === item_id ? updatedItem : item)));
      }
      if (response.status === 200 && response.data.errors) {
        const errorMessages = response.data.errors.data.join(', ');
        setMensaje(errorMessages);
      }
    } catch (error) {
      console.error('ERROR: updating product:', error);
    }
  };


  const handleDelete = async (item_id,item_deleted) => {
    try {

      const url = `http://localhost:1000/api/items/${item_id}`;
      const response = await axios.delete(url,{
        data : {
          item_id: item_deleted.item_id,
          product_name: item_deleted.product_name,
          price: item_deleted.price,
          quantity: item_deleted.quantity
        }
      });
      if (response.status === 200) {
        setMensaje('Product deleted');
        updateHttpRequestInfo('DELETE', url,null, response.data);
        setItems(items.filter(item => item.item_id !== item_id));
      }
    } catch (error) {
      console.error('ERROR: deleting product:', error);
    }
  };

  const handleInputChange = (e, item_id, field) => {
    const value = e.target.value;
    setItems(items.map(item =>
        item.item_id === item_id ? { ...item, [field]: value } : item
    ));
  };



  return (
      <div className="container mt-4">
        <div className="row">
          {items.map((item) => (
              <div key={item.item_id} className="col-md-4 mb-4">
                <div className="card">
                  <div className="card-body">
                    <h3 className="card-title">{item.product_name} </h3>
                    <p className="card-text">Price: ${item.price} (<span
                        className="card-text"
                        style={{color: item.quantity === 0 ? 'red' : 'green'}}
                    >
                      {item.quantity === 0 ? 'Not Available' : item.quantity+' Available'}
                    </span>)</p>


                    <div className="form-group mb-3">
                      <label>Price:</label>
                      <input
                          disabled={loading}
                          type="number"
                          className="form-control"
                          step="0.01"
                          value={item.price}
                          onChange={(e) => handleInputChange(e, item.item_id, 'price')}
                      />
                    </div>

                    <div className="form-group mb-3">
                      <label>Quantity:</label>
                      <input
                          disabled={loading}
                          type="number"
                          className="form-control"
                          value={item.quantity}
                          onChange={(e) => handleInputChange(e, item.item_id, 'quantity')}
                          min="0" // Evita cantidades negativas
                      />
                    </div>

                    <div className="text-center">
                      <button
                          disabled={loading}
                          onClick={() => handleUpdate(item.item_id, item)}
                          className="btn btn-success me-2"
                      >
                        Save
                      </button>
                      <button
                          disabled={loading}
                          onClick={() => handleDelete(item.item_id,item)}
                          className="btn btn-danger"
                      >
                        Delete
                      </button>
                    </div>
                  </div>
                </div>
              </div>
          ))}
        </div>

      </div>
  );
}

export default ItemList;
