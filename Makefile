.PHONY: tests

tests:
	./vendor/bin/phpunit --coverage-html coverage tests/
