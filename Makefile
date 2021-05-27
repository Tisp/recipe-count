all: build test

BIN_NAME=recipe-calculator
BIN_DIR=./bin

PHONY: build test

build:
	docker build -t $(BIN_NAME) .
	chmod +x $(BIN_DIR)/$(BIN_NAME)

test:
	docker run --rm -it --entrypoint '/app/vendor/bin/phpunit' $(BIN_NAME)
