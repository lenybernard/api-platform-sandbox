# Executables (local)
DOCKER_COMPOSE_FILE =
DOCKER_COMP = docker compose $(DOCKER_COMPOSE_FILE)
DOCKER_OPTS=
# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php
DB_CONT = $(DOCKER_COMP) exec database

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP_CONT) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        = help build up start down logs sh composer vendor sf cc

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	$(DOCKER_COMP) build --pull

push:
	@$(DOCKER_COMP) push

pull:
	@$(DOCKER_COMP) pull

up: ## Start the docker hub in detached mode (no logs)
	$(DOCKER_COMP) up --detach ${DOCKER_OPTS}

prod:
	@$(DOCKER_COMP) -f docker-compose.yml up --detach

start: pull build up

restart:
	@$(DOCKER_COMP) restart

down:
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --follow $(DOCKER_OPT)

ps: ## Show process
	@$(DOCKER_COMP) ps -a

sh: ## Connect to the PHP FPM container
	@$(PHP_CONT) sh

stop:
	@$(DOCKER_COMP) stop

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-progress --no-scripts --no-interaction
vendor: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=c:c ## Clear the cache
cc: sf

MESSENGER_MEMORY_LIMIT = 1G
MESSENGER_TIME_LIMIT = 7200
MESSENGER_OPT = -vv
messenger-consume: PHP_OPTIONS = -dmemory_limit=$(MESSENGER_MEMORY_LIMIT)
messenger-consume:
	$(SYMFONY) messenger:consume async $(MESSENGER_OPT) --memory-limit=$(MESSENGER_MEMORY_LIMIT) --time-limit=$(MESSENGER_TIME_LIMIT)
messenger-stop:
	$(SYMFONY) messenger:stop $(MESSENGER_OPT)

open_browser:
	open https://localhost:$(HTTPS_PORT)

## Database
## -----
##
MIGRATION_OPTIONS=
db_drop:
	$(SYMFONY) doctrine:database:drop --force | true
db_create:
	$(SYMFONY) doctrine:database:create
db_reset: db_drop db_create migration_migrate
fixtures:
	$(SYMFONY) hautelook:fixtures:load --no-interaction --purge-with-truncate --no-bundles
.PHONY: fixtures

migration_diff:
	$(SYMFONY) doctrine:migrations:diff $(MIGRATION_OPTIONS)

migration_migrate:
	$(SYMFONY) doctrine:migrations:migrate -n $(MIGRATION_OPTIONS)

