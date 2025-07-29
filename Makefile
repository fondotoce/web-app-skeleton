PROJECT_NAME=web-skeleton

API_IMAGE_NAME=api
API_PATH=api

APP_IMAGE_NAME=api
APP_PATH=api

FRONTEND_IMAGE_NAME=web-interface
FRONTEND_PATH=web-interface
FRONTEND_ANGULAR_PATH=web-interface-angular
FRONTEND_VUEJS_PATH=web-interface-vuejs

API_PHP_CLI=api-php-cli
APP_PHP_CLI=app-php-cli
APP_NODE_CLI=web-interface-node-cli
APP_NODE_ANGULAR_CLI=web-interface-angular-node-cli
APP_NODE_VUEJS_CLI=web-interface-vuejs-node-cli

EXTERNAL_NETWORK = skeleton.dev

init: init-ci frontend-ready

init-ci: docker-down-clear \
	api-clear app-clear frontend-clear frontend-angular-clear frontend-vuejs-clear \
	docker-pull docker-build docker-up \
	api-init app-init frontend-init frontend-angular-init frontend-vuejs-init

up: docker-up
down: docker-down
restart: down up
logs: docker-logs
state: docker-ps

##########
## Docker
##########
docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build --pull

docker-logs:
	docker compose logs -f

docker-ps:
	docker compose ps

docker-network:
	docker network create $(EXTERNAL_NETWORK) || true

##########
## API
##########
api-clear:
	docker run --rm -v ${PWD}/${API_PATH}:/app -w /app alpine sh -c 'rm -rf var/cache/* var/log/* var/test/*'

api-init: api-deps-install api-wait-db

api-permission:
	docker run --rm -v ${PWD}/${API_PATH}:/app -w /app alpine chmod 777 var/cache var/log var/test

api-deps-install:
	docker compose run --rm ${API_PHP_CLI} composer install

api-deps-update:
	docker compose run --rm ${API_PHP_CLI} composer update

api-wait-db:
	docker compose run --rm ${API_PHP_CLI} wait-for-it api-mysql:3306 -t 30

api-migrations:
	docker compose run --rm ${API_PHP_CLI} php ./bin/console migrations:migrate -- --no-interaction
#	docker compose run --rm ${API_PHP_CLI} composer app migrations:migrate -- --no-interaction

api-fixtures:
	docker compose run --rm ${API_PHP_CLI} composer app fixtures:load

api-backup:
	docker compose run --rm api-mysql-backup

api-check: api-validate-schema api-lint api-analyze api-test

api-validate-schema:
	docker compose run --rm ${API_PHP_CLI} composer app orm:validate-schema -- -v

api-lint:
	docker compose run --rm ${API_PHP_CLI} composer lint
	docker compose run --rm ${API_PHP_CLI} composer rector -- --dry-run
	docker compose run --rm ${API_PHP_CLI} composer php-cs-fixer fix -- --dry-run --diff

api-lint-fix:
	docker compose run --rm ${API_PHP_CLI} composer rector
	docker compose run --rm ${API_PHP_CLI} composer php-cs-fixer fix

api-analyze:
	docker compose run --rm ${API_PHP_CLI} composer psalm -- --no-diff

api-analyze-diff:
	docker compose run --rm ${API_PHP_CLI} composer psalm

api-test:
	docker compose run --rm ${API_PHP_CLI} composer test

api-test-coverage:
	docker compose run --rm ${API_PHP_CLI} composer test-coverage

api-test-unit:
	docker compose run --rm ${API_PHP_CLI} composer test -- --testsuite=unit

api-test-unit-coverage:
	docker compose run --rm ${API_PHP_CLI} composer test-coverage -- --testsuite=unit

api-test-functional:
	docker compose run --rm ${API_PHP_CLI} composer test -- --testsuite=functional

api-test-functional-coverage:
	docker compose run --rm ${API_PHP_CLI} composer test-coverage -- --testsuite=functional

# !API

##########
## APP
##########

app-clear:
	docker run --rm -v ${PWD}/${APP_PATH}:/app -w /app alpine sh -c 'rm -rf var/cache/* var/log/* var/test/*'

app-init: app-wait-db

app-permission:
	docker run --rm -v ${PWD}/${APP_PATH}:/app -w /app alpine chmod 777 var/cache var/log var/test

app-deps-install:
	docker compose run --rm ${APP_PHP_CLI} composer install

app-deps-update:
	docker compose run --rm ${APP_PHP_CLI} composer update

app-wait-db:
	docker compose run --rm ${APP_PHP_CLI} wait-for-it api-mysql:3306 -t 30

app-migrations:
	docker compose run --rm ${APP_PHP_CLI} php ./bin/console migrations:migrate -- --no-interaction
#	docker compose run --rm ${APP_PHP_CLI} composer app migrations:migrate -- --no-interaction

app-fixtures:
	docker compose run --rm ${APP_PHP_CLI} composer app fixtures:load

app-backup:
	docker compose run --rm app-mysql-backup

app-check: app-validate-schema app-lint app-analyze app-test

app-validate-schema:
	docker compose run --rm ${APP_PHP_CLI} composer app orm:validate-schema -- -v

app-lint:
	docker compose run --rm ${APP_PHP_CLI} composer lint
	docker compose run --rm ${APP_PHP_CLI} composer rector -- --dry-run
	docker compose run --rm ${APP_PHP_CLI} composer php-cs-fixer fix -- --dry-run --diff

app-lint-fix:
	docker compose run --rm ${APP_PHP_CLI} composer rector
	docker compose run --rm ${APP_PHP_CLI} composer php-cs-fixer fix

app-analyze:
	docker compose run --rm ${APP_PHP_CLI} composer psalm -- --no-diff

app-analyze-diff:
	docker compose run --rm ${APP_PHP_CLI} composer psalm

app-test:
	docker compose run --rm ${APP_PHP_CLI} composer test

app-test-coverage:
	docker compose run --rm ${APP_PHP_CLI} composer test-coverage

app-test-unit:
	docker compose run --rm ${APP_PHP_CLI} composer test -- --testsuite=unit

app-test-unit-coverage:
	docker compose run --rm ${APP_PHP_CLI} composer test-coverage -- --testsuite=unit

app-test-functional:
	docker compose run --rm ${APP_PHP_CLI} composer test -- --testsuite=functional

app-test-functional-coverage:
	docker compose run --rm ${APP_PHP_CLI} composer test-coverage -- --testsuite=functional

# !APP

##########
## Frontend
##########
frontend-clear:
	docker run --rm -v ${PWD}/${FRONTEND_PATH}:/app -w /app alpine sh -c 'rm -rf .ready build'

frontend-init: frontend-deps-install

frontend-deps-install:
	docker compose run --rm ${APP_NODE_CLI} yarn install

frontend-deps-update:
	docker compose run --rm ${APP_NODE_CLI} yarn update

frontend-ready:
	docker run --rm -v ${PWD}/${FRONTEND_PATH}:/app -w /app alpine touch .ready

##########
## Frontend Angular
##########
frontend-angular-clear:
	docker run --rm -v ${PWD}/${FRONTEND_ANGULAR_PATH}:/app -w /app alpine sh -c 'rm -rf .ready build'

frontend-angular-init: frontend-angular-deps-install

frontend-angular-deps-install:
	docker compose run --rm ${APP_NODE_ANGULAR_CLI} yarn install

frontend-angular-deps-update:
	docker compose run --rm ${APP_NODE_ANGULAR_CLI} yarn update

frontend-angular-ready:
	docker run --rm -v ${PWD}/${FRONTEND_ANGULAR_PATH}:/app -w /app alpine touch .ready

##########
## Frontend VueJs
##########
frontend-vuejs-clear:
	docker run --rm -v ${PWD}/${FRONTEND_VUEJS_PATH}:/app -w /app alpine sh -c 'rm -rf .ready build'

frontend-vuejs-init: frontend-vuejs-deps-install

frontend-vuejs-deps-install:
	docker compose run --rm ${APP_NODE_VUEJS_CLI} yarn install

frontend-vuejs-deps-update:
	docker compose run --rm ${APP_NODE_VUEJS_CLI} yarn update

frontend-vuejs-ready:
	docker run --rm -v ${PWD}/${FRONTEND_VUEJS_PATH}:/app -w /app alpine touch .ready

##########
## Build API
##########
build: build-frontend build-api

build-frontend:
	docker --log-level=debug build --pull --file=frontend/docker/production/nginx/Dockerfile --tag=${REGISTRY}/${FRONTEND_IMAGE_NAME}:${IMAGE_TAG} ${FRONTEND_PATH}

build-api:
	docker --log-level=debug build --pull --file=api/docker/production/nginx/Dockerfile --tag=${REGISTRY}/"${API_IMAGE_NAME}-nginx":${IMAGE_TAG} ${API_PATH}
	docker --log-level=debug build --pull --file=api/docker/production/php-fpm/Dockerfile --tag=${REGISTRY}/"${API_IMAGE_NAME}-php-fpm":${IMAGE_TAG} ${API_PATH}
	docker --log-level=debug build --pull --file=api/docker/production/php-cli/Dockerfile --tag=${REGISTRY}/"${API_IMAGE_NAME}-php-cli":${IMAGE_TAG} ${API_PATH}
	docker --log-level=debug build --pull --file=api/common/postgres-backup/php-cli/Dockerfile --tag=${REGISTRY}/"${API_IMAGE_NAME}-postgres-backup":${IMAGE_TAG} ${API_PATH}

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make build

push: push-frontend push-api

push-frontend:
	docker push ${REGISTRY}/${FRONTEND_IMAGE_NAME}:${IMAGE_TAG}

push-api:
	docker push ${REGISTRY}/"${API_IMAGE_NAME}-nginx":${IMAGE_TAG}
	docker push ${REGISTRY}/"${API_IMAGE_NAME}-php-fpm":${IMAGE_TAG}
	docker push ${REGISTRY}/"${API_IMAGE_NAME}-php-cli":${IMAGE_TAG}
	docker push ${REGISTRY}/"${API_IMAGE_NAME}-postgres-backup":${IMAGE_TAG}

###########
### Build APP
###########
#build: build-frontend build-api
#
#build-frontend:
#	docker --log-level=debug build --pull --file=frontend/docker/production/nginx/Dockerfile --tag=${REGISTRY}/${FRONTEND_IMAGE_NAME}:${IMAGE_TAG} ${FRONTEND_PATH}
#
#build-api:
#	docker --log-level=debug build --pull --file=api/docker/production/nginx/Dockerfile --tag=${REGISTRY}/"${API_IMAGE_NAME}-nginx":${IMAGE_TAG} ${API_PATH}
#	docker --log-level=debug build --pull --file=api/docker/production/php-fpm/Dockerfile --tag=${REGISTRY}/"${API_IMAGE_NAME}-php-fpm":${IMAGE_TAG} ${API_PATH}
#	docker --log-level=debug build --pull --file=api/docker/production/php-cli/Dockerfile --tag=${REGISTRY}/"${API_IMAGE_NAME}-php-cli":${IMAGE_TAG} ${API_PATH}
#	docker --log-level=debug build --pull --file=api/common/postgres-backup/php-cli/Dockerfile --tag=${REGISTRY}/"${API_IMAGE_NAME}-postgres-backup":${IMAGE_TAG} ${API_PATH}
#
#try-build:
#	REGISTRY=localhost IMAGE_TAG=0 make build
#
#push: push-frontend push-api
#
#push-frontend:
#	docker push ${REGISTRY}/${FRONTEND_IMAGE_NAME}:${IMAGE_TAG}
#
#push-api:
#	docker push ${REGISTRY}/"${API_IMAGE_NAME}-nginx":${IMAGE_TAG}
#	docker push ${REGISTRY}/"${API_IMAGE_NAME}-php-fpm":${IMAGE_TAG}
#	docker push ${REGISTRY}/"${API_IMAGE_NAME}-php-cli":${IMAGE_TAG}
#	docker push ${REGISTRY}/"${API_IMAGE_NAME}-postgres-backup":${IMAGE_TAG}

##########
## Test
##########

##########
## Deploy
##########
deploy:
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'docker network create --driver=overlay skeleton.net || true'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'rm -rf site_${BUILD_NUMBER} && mkdir site_${BUILD_NUMBER}'

	envsubst < compose-prod.yml > compose-prod-env.yml
	scp -o StrictHostKeyChecking=no -P ${PORT} compose-prod-env.yml deploy@${HOST}:site_${BUILD_NUMBER}/compose.yml
	rm -f compose-prod-env.yml

	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'mkdir site_${BUILD_NUMBER}/secrets'
	scp -o StrictHostKeyChecking=no -P ${PORT} ${API_DB_PASSWORD_FILE} deploy@${HOST}site_${BUILD_NUMBER}/secrets/api_db_password
	scp -o StrictHostKeyChecking=no -P ${PORT} ${API_MAILER_PASSWORD_FILE} deploy@${HOST}site_${BUILD_NUMBER}/secrets/api_mailer_password
	scp -o StrictHostKeyChecking=no -P ${PORT} ${SENTRY_DSN_FILE} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/sentry_dsn
	scp -o StrictHostKeyChecking=no -P ${PORT} ${JWT_ENCRYPTION_KEY_FILE} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/jwt_encryption_key
	scp -o StrictHostKeyChecking=no -P ${PORT} ${JWT_PUBLIC_KEY} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/jwt_public_key
	scp -o StrictHostKeyChecking=no -P ${PORT} ${JWT_PRIVATE_KEY} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/jwt_private_key
	scp -o StrictHostKeyChecking=no -P ${PORT} ${BACKUP_AWS_SECRET_ACCESS_KEY_FILE} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/backup_aws_secret_access_key

	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker stack deploy --compose-file compose.yml ${PROJECT_NAME} --with-registry-auth --prune'

deploy-clear:
	rm -f compose-prod-env.yml

rollback:
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker stack deploy --compose-file compose.yml ${PROJECT_NAME} --with-registry-auth --prune'

###
### System
###

bash:
	docker compose run --rm ${API_PHP_CLI} bash

bash-app:
	docker compose run --rm ${APP_PHP_CLI} bash

node:
	docker compose run --rm ${APP_NODE_CLI} bash

node-angular:
	docker compose run --rm ${APP_NODE_ANGULAR_CLI} bash

node-vuejs:
	docker compose run --rm ${APP_NODE_VUEJS_CLI} bash