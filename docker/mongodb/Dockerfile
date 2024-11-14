# Usamos la imagen oficial de MongoDB como base
FROM mongo:latest

# Argumentos para personalizar el contenedor
ARG UID
ARG GID

RUN if ! getent group ${GID}; then groupadd -g ${GID} appuser; fi && \
    if ! id -u ${UID} &>/dev/null; then useradd -u ${UID} -g ${GID} -m appuser; fi

RUN mkdir -p /data/db && \
    chown -R ${UID}:${GID} /data/db || true && \
    chmod -R 777 /data/db

COPY init-mongo.sh /docker-entrypoint-initdb.d/init-mongo.sh
COPY init-mongo-windows.sh /docker-entrypoint-initdb.d/init-mongo-windows.sh
RUN chmod +x /docker-entrypoint-initdb.d/init-mongo.sh
RUN chmod +x /docker-entrypoint-initdb.d/init-mongo-windows.sh

USER appuser
