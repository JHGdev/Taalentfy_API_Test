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


Al hacer las relaciones de la entidad Offer se han borrado las de User
He tenido que hacer consultas en la base de datos
    ALTER TABLE laboral_sector_assignments ADD laboral_sector_id_id INT NOT NULL;
    ALTER TABLE laboral_sector_assignments ADD CONSTRAINT FK_B0A5662FE06A9B8F FOREIGN KEY (laboral_sector_id_id) REFERENCES laboral_sector (id);
    CREATE INDEX IDX_B0A5662FE06A9B8F ON laboral_sector_assignments (laboral_sector_id_id);


Lo he agregado al ultimo archivo migrations





Agregamos las tablas para las respuestas de los usuarios y las restricciones de las ofertas
---
Actualizo migrations
    bin/console doctrine:migrations:diff
Aplico migrations
    bin/console doctrine:migrations:migrate
--- 



Explicar 
    Si existe se toma el id para crear el nuevo registro, si no, se crea un registro de sector laboral nuevo y se asocia al nuevo registro de sector laboral


Desactivo campos de restricciones, conocimientos y sector laboral en las recomendaciones


Configuracion de campos que se visualizan en cada controlador
    symfony_api/config/serializer/Entity


    bin/console doctrine:migrations:diff
    bin/console doctrine:migrations:migrate

    https://github.com/fzaninotto/Faker
    #composer require fzaninotto/faker