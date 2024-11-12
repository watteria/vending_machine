import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';

function ItemSelection({ items, selectedItem, setSelectedItem,loading  }) {
    const handleSelectItem = (item) => {
        setSelectedItem(item);  // Cambiar el producto seleccionado
    };

    return (
        <div className="container mt-4">
            <div className="row">
                {items.map((item) => (
                    <div key={item.item_id} className="col-md-4 mb-4">
                        <div className="card">
                            <div className="card-body">
                                <h3 className="card-title">{item.product_name}</h3>
                                <p className="card-text">Price: ${item.price} (<span style={{ color: item.quantity === 0 ? 'red' : 'green' }}>
                                    {item.quantity === 0 ? 'Not Available' : item.quantity+' Available'}</span>)
                                </p>
                                {selectedItem && selectedItem.item_id === item.item_id ? (
                                    <p className="text-success">Selected</p>
                                ) : (
                                    <button
                                        className="btn btn-primary"
                                        onClick={() => handleSelectItem(item)}

                                        disabled={loading || item.quantity === 0}
                                    >
                                        Selects
                                    </button>
                                )}
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}

export default ItemSelection;
