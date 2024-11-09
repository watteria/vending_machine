# vending_machine


### ⚠ Requisitos
- Docker
- Composer
- Node
- Make: Para utilizar un Makefile, necesitas tener "make" instalado. En Windows, deberás instalar GnuWin32. Los espacios y ciertos caracteres en el nombre de la ruta pueden causar problemas al ejecutar make, por lo que se recomienda instalarlo en `C:\GnuWin32`. Puedes descargarlo desde [aquí](https://gnuwin32.sourceforge.net/packages/make.htm).


### 🖳 Contenido:
- Contenedor NGINX 1.19 para manejar solicitudes HTTP.
- Contenedor PHP 8.2 para alojar la aplicación Symfony.
- Contenedor MySQL 8.0 para almacenar las bases de datos.
- Contenedor RabbitMQ 3 para gestionar mensajes asíncronos.
- Contenedor Supervisor para crear workers y manejar mensajes asíncronos con llamadas a command consumidor configurado en /docker/supervisor/workers.conf .



### 🖧 Links:
- Frontend (React): http://localhost:1002/ ( tarda un poco para iniciarse)
- Backend (Symfony): http://localhost:1001/
- RabbitMq ( Domain event Bus) : http://localhost:15672/ ( user: guest , pass: guest )
  Supervisor