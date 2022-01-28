# Taalentfy_API_Test
Repositorio para practica de pruebas de taalentfy

# con Docker
Renombrar el archivo .docker/.env.dev a .docker/.env
Renombrar el archivo symfony_api/.env.dev a symfony_api/.env
Ejecutar 
make create-docker






############

Se instala annotations, recomendado por syumfony para asociar las rutas a los controladores
    composer require annotations

Instalamos monolog para registro de acciones
    composer require logger

Instalamos ORM para base de datos
    composer require symfony/orm-pack

Instalamos maker-bundle para facilitar la creacion de nuestras entidades de la base de datos
    composer require --dev symfony/maker-bundle 


Creamos las entidades
    bin/console make:entity
    Agregado tipo "timestamp" para los campos de fecha en /symfony_api/src/entity/User.php

Creamos archivos de migracion
    bin/console make:migration

Aplicamos para crear las tablas de la base de datos
    bin/console doctrine:migrations:migrate


Instalamos rest-bundle para facilitar el manejo de la api-rest
    composer require friendsofsymfony/rest-bundle

Instalamos un serializer necesario para rest-bundle
    composer require symfony/serializer-pack

Configuramos
/symfony_api/config/packages/fos_rest.yaml
/symfony_api/config/routes/api.yaml
/symfony_api/config/serializer/Entity/User.yaml
/symfony_api/config/packages/framework.yaml

Agregamos Validator y dependencias
    composer require symfony/validator twig doctrine/annotations

Agregamos formularios para facilitar el manejo de los datos de entrada y la salida de la validacion
    composer require symfony/form


Ejecutando bin/console doctrine:schema:update --dump-sql, veo que o se habian aplicado los cambios del timestamp
---
Actualizo migrations
 bin/console doctrine:migrations:diff
Aplico migrations
    bin/console doctrine:migrations:migrate
--- 
    Aplico a mano el down de /migrations/Version20220128200147.php
    No lo aplicaba el comando "bin/console doctrine:migrations:execute --down 'DoctrineMigrations\\Version20220128200147'"

Modifico base de datos para establecer fechas como bigint