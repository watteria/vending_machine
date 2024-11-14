import React, {  useEffect } from 'react';
import axios from 'axios';
import 'bootstrap/dist/css/bootstrap.min.css';

function CoinManager({ coins, setCoins, setMensaje, accesoPermitido, totalMonedas, handleCustomerAction, loading,fetchTotalAcumulado, setIsLoadingData,updateHttpRequestInfo ,totalAcumulado,setTotalAcumulado}) {


    // Obtenir monedes ( si es client cuantitat=0 , si es admin cuantitat=cantidad vending)
    const fetchCoins = async () => {
        try {
            setIsLoadingData(true);
            const url = 'http://localhost:1000/api/coins';
            const response = await axios.get(url);
            updateHttpRequestInfo('GET', url, null, response.data);
            const coinsData = response.data;
            const updatedCoins = accesoPermitido ? coinsData : coinsData.map(coin => ({
                ...coin,
                quantity: 0
            }));
            setCoins(updatedCoins);
            setIsLoadingData(false);
        } catch (error) {
            console.error('Error fetching coins:', error);
        }
    };

    // carga inicial / al entrar o salir del back
    useEffect(() => {

            let returned_coins=fetchCoins();

    }, [accesoPermitido]);

    // Gestiona si cambien la quantitat desde el input
    const handleCoinChange = (coin_id, key, value) => {
        setCoins(prevState => {
            return prevState.map(coin => coin.coin_id === coin_id ? { ...coin, [key]: value } : coin);
        });
    };

    // Gestiona si cambien la quantitat desde el + o -
    const handleIncrement = (coin, increment) => {
        const newQuantity = (coin.quantity || 0) + increment;
        if (newQuantity >= 0) {
            handleCoinChange(coin.coin_id, 'quantity', newQuantity);
        }
    };

    // Gestiona si le dan a save
    const handleSave = async (coin) => {
        setIsLoadingData(true);
        const url = `http://localhost:1000/api/coins/${coin.coin_id}`;
        const coinToSave = {
            ...coin,
            valid_for_change: Boolean(coin.valid_for_change)
        };
        try {
            const response = await axios.post(url, coinToSave);
            updateHttpRequestInfo('POST', url, coinToSave, response.data);
            setMensaje(response.data.message || 'Coin successfully saved');
        } catch (error) {
            console.error('Error saving the coin:', error);
            setMensaje('ERROR: saving the coin');
        }
        fetchTotalAcumulado();
        setIsLoadingData(false);
    };

    const sortedCoins = [...coins].sort((a, b) => a.coin_value - b.coin_value);

    return (
        <div className="coin-grid d-flex flex-row flex-wrap">

            <div className="total-monedas container text-center">

                {totalAcumulado !== null && (
                    <h4>
                        Total accumulated in the machine: {totalAcumulado}&euro;
                    </h4>
                )}
                <h4>Total inserted: {totalMonedas} &euro;</h4>
            </div>
            {sortedCoins.map((coin) => (
                <div key={coin.coin_id} className="coin-card p-3 m-2 d-flex flex-column align-items-center">
                    <div className="quantity-controls d-flex align-items-center mb-3">
                        <button onClick={() => handleIncrement(coin, -1)} disabled={loading}>-</button>
                        <input
                            type="number"
                            disabled={loading}
                            className="form-control mx-2 text-center"
                            style={{width: '60px'}}
                            value={coin.quantity}
                            onChange={(e) => handleCoinChange(coin.coin_id, 'quantity', parseInt(e.target.value))}
                        />
                        <button onClick={() => handleIncrement(coin, 1)} disabled={loading}>+</button>
                    </div>
                    <div
                        className="coin-circle d-flex justify-content-center align-items-center mb-3"
                        style={{
                            width: `${coin.coin_value * 100 + 50}px`,
                            height: `${coin.coin_value * 100 + 50}px`,
                            borderRadius: '50%',
                            backgroundColor: '#f0f0f0',
                        }}
                    >
                        <span>{coin.coin_value} €</span>
                    </div>
                    {accesoPermitido && (
                        <>
                            <label className="mb-3">
                                <input
                                    disabled={loading}
                                    type="checkbox"
                                    checked={coin.valid_for_change}
                                    onChange={() => handleCoinChange(coin.coin_id, 'valid_for_change', !coin.valid_for_change)}

                                />
                                &nbsp;Valid for change
                            </label>
                            <button className="btn btn-primary" onClick={() => handleSave(coin)} disabled={loading}>
                                Save
                            </button>
                        </>
                    )}
                </div>
            ))}
            {!accesoPermitido && (
                <div className="insert-coins-button text-center mt-4">
                    <button className="btn btn-primary" onClick={handleCustomerAction} disabled={loading}>
                        Insert coins
                    </button>
                </div>
            )}


        </div>
    );
}

export default CoinManager;
