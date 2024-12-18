import React, { useEffect, useState } from 'react';
import axios from 'axios';
import ItemList from './components/ItemList';
import ItemSelection from './components/ItemSelection';
import LoginForm from './components/LoginForm';
import CoinManager from './components/CoinManager';
import CreateItemForm from './components/CreateItemForm';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import './App.css';


// Inici
function App() {
  // Items,Coins
  const [items, setItems] = useState([]);
  const [coins, setCoins] = useState([]);
  // Messages
  const [mensaje, setMensaje] = useState('');
  // User role - show/hide front/back
  const [accesoPermitido, setAccesoPermitido] = useState(false);
  const [password, setPassword] = useState('');
  const [mostrarFormulario, setMostrarFormulario] = useState(false);
  const [mostrarFormularioCompra, setMostrarFormularioCompra] = useState(true);
  const [customerToken, setCustomerToken] = useState(null);
  // Estados perque aixo tiri
  const [selectedItem, setSelectedItem] = useState(null);
  const [totalAcumulado, setTotalAcumulado] = useState(null);
  const [totalMonedas, setTotalMonedas] = useState(0);
  // UX
  const [fadeClass] = useState('fade-out');
  const [loading, setLoading] = useState(false);
  const [isLoadingData, setIsLoadingData] = useState(true);
  // Requet history
  const [httpRequestHistory, setHttpRequestHistory] = useState([]);

// Current Local Time
  const getCurrentTime = () => {
    const now = new Date();
    return now.toLocaleTimeString();
  };

  // Show HTTP Request Info
  const updateHttpRequestInfo = (method, url, requestData, responseData) => {
    const newRequest = {
      method,
      url,
      requestData,
      responseData,
      time: getCurrentTime(),
    };
    setHttpRequestHistory(prevState => [...prevState, newRequest]);
  };

  // Get all the intems in the vending machine
  const fetchItems = async () => {
    try {
      setLoading(true);
      setIsLoadingData(true);
      const url = 'http://localhost:1000/api/items';
      const response = await axios.get(url);
      setItems(response.data);
      updateHttpRequestInfo('GET', url, null, response.data);
    } catch (error) {
      console.error('ERROR: fetching items:', error);
    } finally {
      setLoading(false);
      setIsLoadingData(false);
    }
  };


  // Total coins in the machine
  const fetchTotalAcumulado = async () => {
    try {
      const url = 'http://localhost:1000/api/coins/total';
      const response = await axios.get(url);
      updateHttpRequestInfo('GET', url, null, response.data);
      setTotalAcumulado(response.data.total);
    } catch (error) {
      console.error('Error fetching the accumulated coins in machine:', error);
      setMensaje('ERROR:  fetching the accumulated coins in machine');
    }
  };


  // Initial loading
  const fetchInitialData = async () => {
    await fetchItems();
    await fetchTotalAcumulado();
    setIsLoadingData(false);
  };


  useEffect(() => {
    let initialData=fetchInitialData();
  }, []);


  // Gestiona el submit del password
  const handlePasswordSubmit = async (e) => {
    e.preventDefault();
    try {
      setIsLoadingData(true);
      const url = 'http://localhost:1000/api/password-verify';
      const response = await axios.post(url, { password: password });
      updateHttpRequestInfo('POST', url, { password: password }, response.data);
      setIsLoadingData(false);
      if (response.status === 200 && response.data.accesoPermitido) {
        setAccesoPermitido(true);
        setMensaje('Access granted');
      } else {
        setMensaje('Wrong password, just write the word "password" , not that difficult');
      }


    } catch (error) {
      console.error('Error on validation:', error);
      setMensaje('ERROR: Wrong password');
    }
  };

// Gestiona submit de insertar moneda
  const handleCustomerAction = async () => {
    const url = customerToken
        ? `http://localhost:1000/api/customers/${customerToken}`
        : 'http://localhost:1000/api/customers';

    const monedasFiltradas = coins.filter(coin => coin.quantity > 0);
    const monedasParaEnviar = monedasFiltradas.length > 0 ? monedasFiltradas : "";
    const itemParaEnviar = selectedItem ? selectedItem : "";

    try {
      setLoading(true);
      setIsLoadingData(true);
      const response = await axios.post(url, {
        inserted_money: monedasParaEnviar,
        id_product: itemParaEnviar.item_id
      });
      updateHttpRequestInfo('POST', url, {
        inserted_money: monedasParaEnviar,
        id_product: itemParaEnviar.item_id
      }, response.data);
      if (!customerToken && response.data.customer_id) {
        setCustomerToken(response.data.customer_id);
      }
      setTotalMonedas(response.data.totalMonedas || 0);
      setMensaje(`Total inserted coins: ${response.data.totalMonedas} €`);


    } catch (error) {
      console.error('ERROR: on send data:', error);
      setMensaje('ERROR: al on send data');
    } finally {
      setLoading(false);
      setIsLoadingData(false);
    }
  };


  // Gestion solicitar producte
  const handleCustomerSubmit = async () => {
    setIsLoadingData(true);
    const url = customerToken
        ? `http://localhost:1000/api/customers/checkout/${customerToken}`
        : 'http://localhost:1000/api/customers/checkout/';

    const monedasFiltradas = coins.filter(coin => coin.quantity > 0);
    const monedasParaEnviar = monedasFiltradas.length > 0 ? monedasFiltradas : "";
    const itemParaEnviar = selectedItem ? selectedItem : "";

    try {

      const response = await axios.post(url, {
        inserted_money: monedasParaEnviar,
        id_product: itemParaEnviar.item_id
      });
      updateHttpRequestInfo('POST', url, {
        inserted_money: monedasParaEnviar,
        id_product: itemParaEnviar.item_id
      }, response.data);
      if (!customerToken && response.data.customer_id) {
        setCustomerToken(response.data.customer_id);
      }
      setIsLoadingData(false);
      if (response.data.status === "return") {

        setMensaje(`
          <strong>Here's your product:</strong> ${selectedItem.product_name}. 
          <br />
          <strong>Here's your change:</strong>
          <ul>
              ${response.data.change_to_return.map(coin =>
              `<li>${coin.quantity} coins of ${coin.coin_value}€</li>`
          ).join('')}
          </ul>
      `);


        setCoins(response.data.coins_on_machine);
        setTotalAcumulado(response.data.machine_total);

        setMostrarFormularioCompra(false);
      }else  if (response.data.status === "nothing_to_return") {
        setMensaje(`
          <strong>Here's your product:</strong> ${selectedItem.product_name}. 
          <br />
          <strong>No change to return:</strong>
          
      `);


        setCoins(response.data.coins_on_machine);
        setTotalAcumulado(response.data.machine_total);

        setMostrarFormularioCompra(false);
      } else {
        setMensaje(`${response.data.message}`);
      }

    } catch (error) {

      setMensaje('ERROR: on sent data');
    } finally {
      setLoading(false);
    }
  };

  // Gestion de Logout
  const handleLogout = () => {
    setAccesoPermitido(false);
    setMensaje('Logged Out');
  };

  // Gestion de Retornar Canvi
  const handleReset = async () => {
    setIsLoadingData(true);
    const url = customerToken
        ? `http://localhost:1000/api/customers/reset/${customerToken}`
        : 'http://localhost:1000/api/customers/reset/';

    const monedasFiltradas = coins.filter(coin => coin.quantity > 0);
    const monedasParaEnviar = monedasFiltradas.length > 0 ? monedasFiltradas : "";
    const itemParaEnviar = selectedItem ? selectedItem : "";

    try {

      const response = await axios.post(url, {
        inserted_money: monedasParaEnviar,
        id_product: itemParaEnviar.item_id
      });
      updateHttpRequestInfo('POST', url, {
        inserted_money: monedasParaEnviar,
        id_product: itemParaEnviar.item_id
      }, response.data);
      if (!customerToken && response.data.customer_id) {
        setCustomerToken(response.data.customer_id);
      }
      setIsLoadingData(false);

      if (response.data.action === "return") {
        setMensaje(` 
          <strong>Here's your change:</strong>
          <ul>
              ${response.data.message.map(coin =>
            `<li>${coin.quantity} coins of ${coin.coin_value}€</li>`
        ).join('')}
          </ul>
      `);

        setMostrarFormularioCompra(false);
      } else {

        setMensaje(`${response.data.message}`);
      }

    } catch (error) {
      console.error('ERROR: on sent data:', error);
      setMensaje('ERROR: on sent data');
    } finally {
      setLoading(false);
    }
  };

  return (
      <div className="app-container d-flex">
        {isLoadingData && (
            <div className="loading-popup">
              <p>Loading...</p>
            </div>
        )}
        <div className="left-content w-70">
          <div className="App container mt-4">
            <h1 className="text-center mb-4">Vending Machine</h1>
            {mensaje && (
                <div
                    className={`alert  ${mensaje.includes("Here's your product") ? 'alert-success' : 'alert-info'} ${mensaje.includes('ERROR:') ? 'alert-danger' : ''} ${fadeClass}`}>
                  <div dangerouslySetInnerHTML={{__html: mensaje}}></div>
                  {!mostrarFormularioCompra && (
                      <button className="btn btn-primary mt-3" onClick={() => window.location.reload()}>
                        Buy another Item
                      </button>
                  )}
                </div>
            )}
            {mostrarFormularioCompra && (
                <>
                {!mostrarFormulario && (
                    <div className="text-center">
                      <p>To manage (edit/create/delete products, handle the change) click  on Open the Vending Machine.</p>
                      <button className="btn btn-primary" onClick={() => setMostrarFormulario(true)}>Open the Vending Machine</button>
                    </div>
                )}
                </>
            )}
            {mostrarFormularioCompra && (
                <>
                  {!accesoPermitido && (
                    <div className="text-center mt-4">
                      <button className="btn btn-success me-2" onClick={handleCustomerSubmit} disabled={loading}>
                        Request product
                      </button>
                      <button className="btn btn-secondary" onClick={handleReset} disabled={loading}>
                        Refund money
                      </button>
                    </div>
                  )}

                {mostrarFormulario && !accesoPermitido && (
                  <div className="mb-4">
                    <LoginForm
                        password={password}
                        setPassword={setPassword}
                        setMostrarFormulario={setMostrarFormulario}
                        handlePasswordSubmit={handlePasswordSubmit}
                        loading={loading}
                    />
                  </div>
               )}

                {accesoPermitido ? (
                    <>
                      <div className="text-center mt-4">
                        <button
                            onClick={handleLogout}
                            className="btn btn-warning"
                        >
                          Logout
                        </button>
                      </div>
                      <div className="mb-4">
                        <CreateItemForm
                            setItems={setItems}
                            setMensaje={setMensaje}
                            setIsLoadingData={setIsLoadingData}
                            loading={loading}
                            updateHttpRequestInfo={updateHttpRequestInfo}
                        />
                      </div>
                      <CoinManager
                          coins={coins}
                          setCoins={setCoins}
                          setMensaje={setMensaje}
                          accesoPermitido={accesoPermitido}
                          totalMonedas={totalMonedas}
                          handleCustomerAction={handleCustomerAction}
                          loading={loading}
                          fetchTotalAcumulado={fetchTotalAcumulado}
                          setIsLoadingData={setIsLoadingData}
                          updateHttpRequestInfo={updateHttpRequestInfo}
                          totalAcumulado={totalAcumulado}
                          setTotalAcumulado={setTotalAcumulado}
                      />
                      <ItemList
                          setIsLoadingData={setIsLoadingData}
                          items={items}
                          setItems={setItems}
                          setMensaje={setMensaje}
                          loading={loading}
                          setAccesoPermitido={setAccesoPermitido}
                          updateHttpRequestInfo={updateHttpRequestInfo}
                      />
                    </>
                  ) : (
                    <>
                      <ItemSelection
                          items={items}
                          selectedItem={selectedItem}
                          setSelectedItem={setSelectedItem}
                          loading={loading}
                          setLoading={setLoading}
                      />
                      <CoinManager
                          coins={coins}
                          setCoins={setCoins}
                          setMensaje={setMensaje}
                          accesoPermitido={accesoPermitido}
                          totalMonedas={totalMonedas}
                          handleCustomerAction={handleCustomerAction}
                          loading={loading}
                          setLoading={setLoading}
                          setIsLoadingData={setIsLoadingData}
                          updateHttpRequestInfo={updateHttpRequestInfo}
                          totalAcumulado={totalAcumulado}
                          setTotalAcumulado={setTotalAcumulado}
                      />
                    </>
                  )}

              </>
            )}
          </div>

        </div>

        <div className="right-content w-30 p-3">
          <h4>HTTP Request Info</h4>
          {httpRequestHistory.length === 0 ? (
              <p>No HTTP requests made yet</p>
          ) : (
              httpRequestHistory.map((request, index) => (
                  <div className="dropdown mb-3" key={index}>
                    <button className="btn btn-secondary dropdown-toggle w-100" type="button"
                            id={`dropdownMenuButton-${index}`} data-bs-toggle="dropdown" aria-expanded="false">
                      {request.method} - {request.url} - {request.time}
                    </button>
                    <ul className="dropdown-menu" aria-labelledby={`dropdownMenuButton-${index}`}>
                      <li className="dropdown-item"><strong>Request Data:</strong></li>
                      <li className="dropdown-item">
                        <pre>{JSON.stringify(request.requestData, null, 2)}</pre>
                      </li>
                      <li className="dropdown-item"><strong>Response Data:</strong></li>
                      <li className="dropdown-item">
                        <pre>{JSON.stringify(request.responseData, null, 2)}</pre>
                      </li>
                    </ul>
                  </div>
              ))
          )}
        </div>
      </div>
  );
}

export default App;
