include .env
$(shell touch .env.local)
include .env.local

export $(shell sed 's/=.*//' .env)
export $(shell sed 's/=.*//' .env.local)

DOCKER_COMPOSE?=docker compose

DOCKER_COMPOSE_CONFIG := -f docker-compose.yml -f docker-compose.$(APP_ENV).yml

stop: ## Остановить контейнеры
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) stop

down: ## Уронить контейнеры (удалить)
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) down

up: ## Поднять контейнеры
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) up -d --remove-orphans

upp: ## Поднять контейнеры с принудительной пересборкой
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) up -d --build --remove-orphans

armageddon: ## Удалит все неиспользованное
	docker system prune -a -f

docker-clean: ## Удалить кэш сборок builder
	docker builder prune -f

in-app: ## Войти в контейнер с приложением
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) exec app bash

in-nginx: ## Войти в контейнер nginx
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) exec nginx sh

in-sql: ## Обратиться напрямую к БД
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) exec mysql bash

sql: ## Обратиться напрямую к БД
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) exec -it mysql mysql -u root -p

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

check-code: ## Проверить коммит перед отправкой
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_CONFIG) exec -T app composer check

cache-clear: ## Очистка кэша проекта
	bin/console cache:clear
