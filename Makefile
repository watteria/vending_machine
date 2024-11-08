SHELL := /bin/bash
DOCKER_BE = ddd-skeleton-be

NAME_S := $(shell uname -s)

ifeq ($(UNAME_S),Linux)
    OS := Linux
    UID := $(shell id -u)
else ifeq ($(UNAME_S),Darwin)
    OS := MacOS
    UID := $(shell id -u)
else
    OS := Windows
    UID := 1000  # valor per a windows
endif

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
	set U_ID=$(UID) && docker-compose up -d
else
	U_ID=${UID} docker-compose up -d
endif

down: ## Stop the containers
ifeq ($(OS), Windows)
	set U_ID=$(UID) && docker-compose stop
else
	U_ID=${UID} docker-compose stop
endif

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) run

build: ## Rebuilds all the containers
	$(MAKE) copy-files
ifeq ($(OS), Windows)
	set U_ID=$(UID) && docker-compose build
else
	U_ID=${UID} docker-compose build
endif

copy-files: ## Creates a copy of .env and docker-compose.yml.dist file to use locally
ifeq ($(OS), Linux)
	cp -n .env .env.local || true
	cp -n docker-compose.yml.dist docker-compose.yml || true
else
	powershell -Command "if (!(Test-Path .env.local)) { Copy-Item .env .env.local }"
	powershell -Command "if (!(Test-Path docker-compose.yml)) { Copy-Item docker-compose.yml.dist docker-compose.yml }"
endif

# Backend commands
composer-install: ## Installs composer dependencies
ifeq ($(OS), Windows)
	set U_ID=$(UID) && docker exec --user $(UID) -it $(DOCKER_BE) composer install --no-scripts --no-interaction --optimize-autoloader
else
	U_ID=${UID} docker exec --user ${UID} -it ${DOCKER_BE} composer install --no-scripts --no-interaction --optimize-autoloader
endif

cli: ## ssh's into the be container
ifeq ($(OS), Windows)
	set U_ID=$(UID) && docker exec -it --user $(UID) $(DOCKER_BE) bash
else
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bash
endif

container-names: ## Change default container names (need param name)
	find . -type f -exec sed -i 's/ddd-skeleton/$(name)/g' {} +


init: ## Init tot de cop
	$(MAKE) down
	$(MAKE) build
	$(MAKE) up
	$(MAKE) composer-install