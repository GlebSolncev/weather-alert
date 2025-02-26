.PHONY: build run

build:
	@echo "Building Docker containers..."
	docker-compose build

run: build
	@echo "Starting Docker containers..."
	docker-compose up -d

setup:
	@echo "Setup and start project"
	docker-compose up --build

.PHONY: clean

clean:
	@echo "Cleaning up Docker containers, networks, images, and volumes..."
	docker-compose down --volumes --rmi all --remove-orphans

# Helper for stopping and removing containers if needed
.PHONY: stop

stop:
	@echo "Stopping Docker containers..."
	docker-compose stop

# Rebuild and rerun the project
.PHONY: rebuild

rebuild: clean run
	@echo "Rebuilding and restarting the project..."