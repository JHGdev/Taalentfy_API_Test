# Taalentfy_API_Test
Repositorio para practica de pruebas de taalentfy

# con Docker
Renombrar el archivo .docker/.env.dev a .docker/.env
Renombrar el archivo symfony_api/.env.dev a symfony_api/.env
Ejecutar 
make create-docker






############3

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