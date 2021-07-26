test:
	./vendor/bin/phpunit
linters:
	./vendor/bin/phpcbf
	./vendor/bin/phpstan
all: linters test