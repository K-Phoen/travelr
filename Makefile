.PHONY: build

deps: vendor/autoload.php npm

# we only do `install`, as composer.json may change
# without wanting to update dependencies.
composer.lock: composer.json
	composer install
	touch $@

# make sure deps get installed even if
# composer.lock exists initially (or
# after `git pull`)
vendor/autoload.php: composer.lock
	composer install
	touch $@

npm:
	docker run -it -v $(shell pwd):/usr/src/app -w /usr/src/app --rm node:9 npm install

build:
	docker run -it -v $(shell pwd):/usr/src/app -w /usr/src/app --rm node:9 npm run build
	./bin/travelr build

serve:
	php -S 127.0.0.1:8080 -t web
