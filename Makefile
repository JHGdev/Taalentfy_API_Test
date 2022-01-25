create-docker:
	cd .docker
	docker-compose up -d --build
	cp .env.dev .env
	cd ..

#install:
#	composer install
#	mkdir -p src/Logs
#	chmod 777 src/Logs
#	chown -R 1000.1000 src/Logs

