import React, { useState } from 'react';
import axios from 'axios';

function CreateItemForm({ setItems, setMensaje,loading,updateHttpRequestInfo,setIsLoadingData }) {
    const [product_name, setName] = useState('');
    const [price, setPrice] = useState('');
    const [quantity, setQuantity] = useState('');

    // Gestiona  crear producte
    const handleCreateItem = async (newItem) => {

        setIsLoadingData(true);
        const url = 'http://localhost:1000/api/items';
        try {
            const response = await axios.post(url, {
                product_name: newItem.product_name,
                price: newItem.price,
                quantity: newItem.quantity
            });
            if (response.status === 201) {

                updateHttpRequestInfo('POST', url, { newItem: newItem}, response.data);
                const updatedItems = await axios.get('http://localhost:1000/api/items');
                setItems(updatedItems.data);
                setMensaje('Product successfully created');
            }

            setIsLoadingData(false);
        } catch (error) {
            console.error('Error creating the product:', error);
            setMensaje('ERROR: creating the product');
        }
    };

    // Gestiona submit de crear producte
    const handleSubmit = (e) => {
        e.preventDefault();
        const newItem = { product_name, price, quantity };
        let newItemCreated=handleCreateItem(newItem);
    };

    return (
        <div className="container mt-4">
            <div className="card">
                <div className="card-header">
                    <h2 className="text-center">Create New Product</h2>
                </div>
                <div className="card-body">
                    <form onSubmit={handleSubmit}>
                        <div className="form-row d-flex align-items-center mb-3">
                            <div className="form-group col-md-4">
                                <label htmlFor="name">Name:</label>
                                <input
                                    type="text"
                                    id="name"
                                    className="form-control"
                                    value={product_name}
                                    onChange={(e) => setName(e.target.value)}
                                    required

                                />
                            </div>
                            <div className="form-group col-md-4">
                                <label htmlFor="price">Price:</label>
                                <input
                                    type="number"
                                    id="price"
                                    className="form-control"
                                    step="0.01"
                                    value={price}
                                    onChange={(e) => setPrice(e.target.value)}
                                    required

                                />
                            </div>
                            <div className="form-group col-md-4">
                                <label htmlFor="quantity">Quantity:</label>
                                <input
                                    type="number"
                                    id="quantity"
                                    className="form-control"
                                    value={quantity}
                                    onChange={(e) => setQuantity(e.target.value)}
                                    required
                                    disabled={loading}
                                />
                            </div>
                        </div>
                        <div className="text-center">
                            <button type="submit" disabled={loading} className="btn btn-primary">
                                Create Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}

export default CreateItemForm;
