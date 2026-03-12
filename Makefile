build:
	docker compose -f ./docker/docker-compose.yml build

start:
	docker compose -f ./docker/docker-compose.yml up -d --remove-orphans

stop:
	docker compose -f ./docker/docker-compose.yml down --remove-orphans

shell:
	docker compose -f ./docker/docker-compose.yml exec -u www-data app bash
