
# Set up the default (i.e. - first) make entry.
start: web

bash:
	docker-compose run --rm mfaidp bash

bashtests:
	docker-compose run --rm tests bash

behat:
	docker-compose run --rm tests bash -c "composer install --no-scripts && vendor/bin/behat --config=features/behat.yml --strict --stop-on-failure --append-snippets"

clean:
	docker-compose kill
	docker system prune -f

composer:
	docker-compose run --rm composer bash -c "composer install --no-scripts"

composerupdate:
	docker-compose run --rm composer bash -c "composer update --no-scripts"

enabledebug:
	docker-compose exec mfaidp bash -c "/data/enable-debug.sh"

ps:
	docker-compose ps

test: composer web
	@echo -------------------------------------------------------------------
	@echo Bringing up mfaidp takes a long time due to composer.
	@echo After this, you can use \"make behat\" to run the tests more quickly.
	@echo -------------------------------------------------------------------
	sleep 200 # Give composer time to install any new dependencies of this project
	make behat

web:
	docker-compose up -d mfaidp mfasp mfapwmanager
