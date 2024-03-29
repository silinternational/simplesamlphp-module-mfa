
# Set up the default (i.e. - first) make entry.
start: web

bash:
	docker-compose run --rm mfaidp bash

bashtests:
	docker-compose run --rm tests bash

behat:
	docker-compose run --rm tests bash -c "vendor/bin/behat --config=features/behat.yml --strict --stop-on-failure --append-snippets"

clean:
	docker-compose kill
	docker-compose rm -f

copyJsLib:
	cp ./node_modules/@simplewebauthn/browser/dist/bundle/index.umd.min.js ./www/simplewebauthn/browser.js
	cp ./node_modules/@simplewebauthn/browser/LICENSE.md ./www/simplewebauthn/LICENSE.md

deps:
	docker-compose run --rm composer bash -c "composer install --no-scripts"
	docker-compose run --rm node npm install --ignore-scripts
	make copyJsLib

depsupdate:
	docker-compose run --rm composer bash -c "composer update --no-scripts"
	docker-compose run --rm node npm update --ignore-scripts
	make copyJsLib

enabledebug:
	docker-compose exec mfaidp bash -c "/data/enable-debug.sh"

ps:
	docker-compose ps

test: deps web
	@echo -------------------------------------------------------------------
	@echo Bringing up mfaidp takes a long time due to composer.
	@echo After this, you can use \"make behat\" to run the tests more quickly.
	@echo -------------------------------------------------------------------
	docker-compose run --rm mfasp bash -c "whenavail mfaidp 80 200 echo mfaidp ready"
	make behat

web:
	docker-compose up -d mfaidp mfasp mfapwmanager
