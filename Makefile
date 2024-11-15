SHELL := /bin/bash
DOCKER_BE = ddd-skeleton-be

UNAME_S := $(shell uname -s)

ifeq ($(UNAME_S),Linux)
    OS := Linux
else ifeq ($(UNAME_S),Darwin)
    OS := MacOS
else
    OS := Windows
endif

UID := 1002
GID := 1002

# Colours
greenColour=\e[0;32m\033[1m
close=\033[0m\e[0m
redColour=\e[0;31m\033[1m
blueColour=\e[0;34m\033[1m
yellowColour=\e[0;33m\033[1m
purpleColour=\e[0;35m\033[1m
turquoiseColour=\e[0;36m\033[1m
grayColour=\e[0;37m\033[1m

help: ## Show this help message
	@echo
	@echo "$(greenColour)======= Usage:$(close) make $(purpleColour)[target]$(close) $(greenColour)======= $(close)"
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

up: ## Start the containers
	$(MAKE) copy-files
ifeq ($(OS), Windows)
	set U_ID=$(UID)  && set G_ID=$(GID) && docker-compose up -d
else
	U_ID=${UID} G_ID=${GID} docker-compose up -d
endif

down: ## Stop the containers
ifeq ($(OS), Windows)
	set U_ID=$(UID) &&  set G_ID=$(GID) && docker-compose stop
else
	U_ID=${UID} G_ID=${GID} docker-compose stop
endif

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) run

build: ## Rebuilds all the containers
	$(MAKE) copy-files
ifeq ($(OS), Windows)
	set U_ID=$(UID) && set G_ID=$(GID) && docker-compose build
else
	U_ID=${UID} G_ID=${GID} docker-compose build
endif

copy-files: ## Creates a copy of .env and docker-compose.yml.dist file to use locally
ifeq ($(OS), Windows)
	powershell -Command "if (!(Test-Path .env.local)) { Copy-Item .env .env.local }"
	powershell -Command "if (!(Test-Path docker-compose.yml)) { Copy-Item docker-compose.yml.dist docker-compose.yml }"
else
	cp -n .env .env.local || true
	cp -n docker-compose.yml.dist docker-compose.yml || true
endif

# Backend commands
composer-install: ## Installs composer dependencies
ifeq ($(OS), Windows)
	set U_ID=$(UID) && set G_ID=$(GID) && docker exec --user $(UID) -it $(DOCKER_BE) composer install --no-scripts --no-interaction --optimize-autoloader
else
	U_ID=${UID}  G_ID=$(GID)  docker exec --user ${UID} -it ${DOCKER_BE} composer install --no-scripts --no-interaction --optimize-autoloader
endif

cli: ## ssh's into the be container
ifeq ($(OS), Windows)
	set U_ID=$(UID) && set G_ID=$(GID) && docker exec -it --user $(UID) $(DOCKER_BE) bash
else
	U_ID=${UID} G_ID=${GID} docker exec -it --user ${UID} ${DOCKER_BE} bash
endif

container-names: ## Change default container names (need param name)
	find . -type f -exec sed -i 's/ddd-skeleton/$(name)/g' {} +


reset-symfony-test-cache: ## Clear testing cache
	docker exec --user ${UID} -it ${DOCKER_BE} bin/console cache:clear --env=test

recreate-db: ## Recreate database
ifeq ($(OS), Windows)
	docker exec --user $(UID) -it ddd-skeleton-mongodb bash  /docker-entrypoint-initdb.d/init-mongo-windows.sh
else
	docker exec --user $(UID) -it ddd-skeleton-mongodb bash  /docker-entrypoint-initdb.d/init-mongo.sh
endif



load-db-fixtures: ## Load fixtures
	docker exec --user ${UID} -it ${DOCKER_BE} bin/console d:f:load -n

symfony-warmup:
	docker exec --user ${UID} -it ${DOCKER_BE} bash -c "php /appdata/www/bin/console cache:warmup --env=prod"

fix-permissions:
	docker exec --user root -it ${DOCKER_BE} bash -c "mkdir -p /appdata/www/var/cache /appdata/www/var/log && chmod -R 777 /appdata/www/var/cache /appdata/www/var/log"
	docker exec --user root -it ddd-skeleton-mongodb chmod -R 777 /data/db



test-unit: ## Execute unit tests
	$(MAKE) recreate-db
	docker exec --user ${UID} -it ${DOCKER_BE} bin/phpunit
	$(MAKE) recreate-db


test-acceptance-behat: ## Execute behat tests
	$(MAKE) recreate-db
	make reset-symfony-test-cache
	docker exec --user ${UID} -it ${DOCKER_BE} vendor/bin/behat
	$(MAKE) recreate-db

npm-install: ## Ejecuta npm install en /frontend/vending_machine
	cd frontend/vending_machine && npm config set strict-ssl false && npm install --force --loglevel verbose

install: ## Init tot de cop
	$(MAKE) npm-install
	$(MAKE) down
	$(MAKE) build
	$(MAKE) up
	$(MAKE) composer-install
	$(MAKE) symfony-warmup
	$(MAKE) wait-for-mongo
	$(MAKE) fix-permissions


open-browser:
ifeq ($(OS), Windows)
	start chrome  "http://localhost:1002/"
else ifeq ($(OS), MacOS)
	open "http://localhost:1002/"
else
	xdg-open "http://localhost:1002/"
endif

wait-for-mongo:
	@echo "Esperando a que MongoDB esté listo..."
	@until docker exec ddd-skeleton-mongodb mongosh --host localhost --port 27017 -u root -p rootpassword --authenticationDatabase admin --eval "db.stats()"; do \
		echo "MongoDB no esta listo, esperando..."; \
		sleep 5; \
	done
	@echo "MongoDB está listo."

init: ## Init tot de cop
	$(MAKE) down
	$(MAKE) build
	$(MAKE) up
	$(MAKE) wait-for-mongo
	$(MAKE) recreate-db
	$(MAKE) open-browser

