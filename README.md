# ⛾ Watteria Vending Machine

Este repositorio contiene la prueba que me ha pedido anna y Julio de una máquina de vending.

### ⚠ Requisitos
- Docker
- Composer
- Node
- Make: Para utilizar un Makefile, necesitas tener "make" instalado. En Windows, deberás instalar GnuWin32. Los espacios y ciertos caracteres en el nombre de la ruta pueden causar problemas al ejecutar make, por lo que se recomienda instalarlo en `C:\GnuWin32`. Puedes descargarlo desde [aquí](https://gnuwin32.sourceforge.net/packages/make.htm).


### 🖳 Contenido:
- Contenedor NGINX 1.19 para el back.
- Contenedor PHP 8.2 para alojar la aplicación Symfony.
- Contenedor NGINX 1.19 para el front en REACT.
- Contenedor MySQL 8.0 para almacenar las bases de datos.
- Contenedor RabbitMQ 3 para gestionar mensajes asíncronos.
- Contenedor Supervisor para crear workers y manejar mensajes asíncronos con llamadas a command consumidor configurado en /docker/supervisor/workers.conf .



### 🖧 Links:
- Frontend (React): http://localhost:1002/
- Backend (Symfony): http://localhost:1000/
- RabbitMq ( Domain event Bus) : http://localhost:15672/ ( user: guest , pass: guest )


### 🛈 Instalación:
- Ejecuta `make install` para iniciar los contenedores, instalar dependencias, crear la base datos y poner datos iniciales

### 🛈 Ejecucion:
- Ejecuta `make init` se reinicia todo, base de datos incluida


### 🛈 Utiles:

#### Cerrar Todo
    Ejecuta `make down` para hacer down de contendores.

#### Recrear Base de datos
    Ejecuta `make recreate-db` para volver a  crear la estructura de la base de datos y poner datos iniciales.

#### ☑ Pruebas:
- Pruebas unitarias `make test-unit`
- Pruebas de aceptación `make test-acceptance-behat`


### 🖧 Links:
- Frontend (React): http://localhost:1002/ ( tarda un poco para iniciarse)
- Backend (Symfony): http://localhost:1001/
- RabbitMq ( Domain event Bus) : http://localhost:15672/ ( user: guest , pass: guest )
  Supervisor

#### Defined Routes:
    -sds


## Estrutura de directorios
### Estructura de Bounded Context / Módulos

```plaintext
src/
├── Context/
│   ├── Coins/
│   │   ├── Coin/
│   │   ├── Event/
│   ├── Customers/
│   │   ├── Customer/
│   │   ├── Event/
│   ├── Items/
│       ├── Event/
│       ├── Item/
├── SharedKernel/
```

### Estructura de  un Módulo ( más o menos se repite la idea en Customers y Items)

```plaintext
Coins/
├── Coin/
│   ├── Application/
│   │   ├── AllCoins/
│   │   │   ├── AllCoinsQuery.php  🔷Query
│   │   │   ├── AllCoinsQueryHandler.php 🔷Query Bus Handler
│   │   │   ├── AllCoinsResponse.php 🔷Query Bus Response
│   │   │   └── AllCoinsUseCase.php 🔷 Caso de uso 
│   │   ├── OnCustomerCheckout/
│   │   │   └── OnCustomerCheckout.php 🦻Subscripcion a evento
│   │   ├── TotalAmount/ ⬜ misma estructura que allcoins
│   │   ├── UpdateCoin/
│   │       ├── UpdateCoinCommand.php 🔶Command
│   │       ├── UpdateCoinCommandHandler.php  🔶Command Bus Handler
│   │       └── UpdateCoinUseCase.php 🔶 Caso de uso 
│   ├── Domain/
│   │   ├── Event/
│   │   │   └── CoinWasUpdated.php 🛜 Domain Event
│   │   ├── Repository/
│   │   │   └── CoinRepository.php 📂 Coin Repository
│   │   ├── Tools/
│   │   │   ├── MoneyChangeOnLimitedCoins.php ⬜ Servicio gestion de cambio
│   │   │   └── MoneyCounterFromJson.php ⬜ Servicio contador de monedas
│   │   ├── ValueObject/
│   │   │   └── CoinId.php.... 💲Value Objects
│   │   └── Coin.php 📦Agregate Root
│   ├── Infrastructure/
│   │   ├── Persistence/
│   │   │   ├── Doctrine/
│   │   │   │   ├── Fixture/
│   │   │   │   │   └── CoinFixture.php ☑️ Fixture para test con behat
│   │   │   │   ├── mapping/
│   │   │   │   │   └── Coin.Domain.Coin.orm.xml ⬜ Definicion en doctrine
│   │   │   │   │   └── CoinIdType.php.... ⬜ Definicion de type de  doctrine
│   │   │   └── MysqlDoctrineCoinRepository.php ⬜ Repository en mysql
│   │   ├── Symfony/
│   │   │   └── routes.yaml 🖧 Rutas de symfony
│   ├── UI/📥 User Interface
│       ├── Controller/ 📥 Controladores
│           ├── AllCoinsController.php 
│           ├── AvaiableChangeCoinsController.php
│           ├── TotalAmountController.php
│           └── UpdateCoinController.php
├── Event/
├── CoinsCommand.php 🔶Command Bus
├── CoinsDomainEvent.php 🛜 Domain Event
└── CoinsQuery.php 🔷Query Bus
```
### Estructura del Shared Kernel

```plaintext
SharedKernel/
├── Domain/ ⬜ Estructura para que funcionen Agregate, los buses de command/query, los eventos de dominio
├── Infrastructure/ ⬜ Estructura para que funcionen los buses,los controladores, el Rabbit
├── UI/
├── Command/
│   ├── ConfigureRabbitMqCommand.php ⬜ comando para que al arrancar configure los exchange si no existen
│   ├── ConsumeRabbitMqDomainEventsCommand.php ⬜ Consumidor de Rabbit
│   └── GenerateSupervisorRabbitMqConsumerFilesCommand.php ⬜ Ayuda a crear la config que esta en /docker/supervisor
├── Controller/
├── PasswordValidatorController.php ⬜ servicio que comprueva el password
```

### React
/frontend/vending_machine

### Estructura de Test

```plaintext

tests/
├── Acceptance/ ⬜ Behat
├── Unit/ ⬜ PhpUnit

```

## RabbitMq

### Connection
```plaintext
- http://localhost:15672/
- user: guest
- pass: guest
```


### Exchanges
#### domain_events_vending
    exchange inicial
#### retry-domain_events_vending
    exchange en caso de fallida inicial, lo intenta 5 veces ( .env -> RABBITMQ_MAX_RETRIES ) 
#### retry-domain_events_vending
    exchange si ha intentado 5 veces y ni por esas, se queda aqui y nadie lo consume

### Colas
#### coins.coin.application.on_customer_checkout
    cola de coin cuando se lanza el evento 	customer.checkout
#### retry.coins.coin.application.on_customer_checkout
    si falla lo intenta 5 veces en esta cola
#### dead_letter.coins.coin.application.on_customer_checkout
    si falla se queda en esta cola
#### items.item.application.on_customer_checkout
    cola de item cuando se lanza el evento 	customer.checkout
#### retry.items.item.application.on_customer_checkout
    si falla lo intenta 5 veces en esta cola
#### dead_letter.items.item.application.on_customer_checkout
    si falla se queda en esta cola 