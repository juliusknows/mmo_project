include .env
$(shell touch .env.local)
include .env.local

export $(shell sed 's/=.*//' .env)
export $(shell sed 's/=.*//' .env.local)

DOCKER_COMPOSE?=docker compose

DOCKER_COMPOSE_CONFIG := -f docker-compose.$(APP_ENV).yml

stop: ## Остановить контейнеры
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) stop

up: ## Поднять контейнеры
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) up -d --remove-orphans

up+: ## Поднять контейнеры с консолью
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) up --remove-orphans

armageddon: ## Удалит все неиспользованное
	docker system prune -a -f

clean: ## Удалить кэш
	docker builder prune -f

in-app: ## Войти в контейнер с приложением
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) exec app bash

in-nginx: ## Войти в контейнер nginx
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) exec nginx sh

stan: ## Статический анализ кода - phpstan
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) exec -T app composer phpstan

psalm: ## Статический анализ кода - psalm
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) exec -T app composer psalm

fix: ## Внести автоматические правки по стилю кода
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) exec -T app composer phpcsfix

test: ## Проверить коммит перед отправкой
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) exec -T app composer check

help: ## Вывод доступных команд
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n"} /^[$$()% a-zA-Z_-]+:.*?##/ { printf "  \033[32m%-30s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

