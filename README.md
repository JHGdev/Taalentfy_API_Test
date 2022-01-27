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


????
composer require --dev symfony/maker-bundle