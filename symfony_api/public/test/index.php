<?php

// TODO REVISAR SQL cuando las ofertas no tengan tests
//     testcriteria en el SELECT 
//     if (offer.test == null) -> testcriteria = 1

#composer require fzaninotto/faker


// Estos valores se usan para delimitar el numero de valores distintos que se van a generar.
// Para que se puedan dar coincidencias entre usuarios y ofertas
define('VALORES_DIFERENTES_SECTOR_LABORAL', 10);
define('VALORES_DIFERENTES_CONOCIMIENTOS',  20);


# When installed via composer
require_once 'vendor/autoload.php';


createGlobals();

echo "<pre>";
for($i = 0; $i <100; $i++){
    var_dump(createFakeUser());
    var_dump(createFakeOffer());
}
echo "</pre>";





function createGlobals(){

    Global $faker, $KNOWLEDGE_VALUES, $LABORAL_SECTOR_VALUES;

    $faker = Faker\Factory::create();

    $LABORAL_SECTOR_VALUES = [];
    for($i = 0; $i < VALORES_DIFERENTES_SECTOR_LABORAL; $i++){
        $LABORAL_SECTOR_VALUES[] = $faker->catchPhrase;
    }

    $KNOWLEDGE_VALUES = [];
    for($i = 0; $i < VALORES_DIFERENTES_CONOCIMIENTOS; $i++){
        $KNOWLEDGE_VALUES[] = $faker->catchPhrase;
    }
}


function createFakeUser(){
    
    Global $faker, $LABORAL_SECTOR_VALUES, $KNOWLEDGE_VALUES;
    
    $date = new DateTime($faker->date($format = 'Y-m-d', $max = 'now') );

    $user = [
        'email'      => $faker->safeEmail,
        'firstname'  => $faker->firstName,
        'lastname'   => $faker->lastName,
        'birth_date' => $date->getTimestamp()
    ];

    if ($faker->biasedNumberBetween(0, 1))
        $user['laboral_sector'] = $LABORAL_SECTOR_VALUES[$faker->biasedNumberBetween(0, VALORES_DIFERENTES_SECTOR_LABORAL -1)];

    while($faker->biasedNumberBetween(0, 1))
        $user['knoledge'] = $KNOWLEDGE_VALUES[$faker->biasedNumberBetween(0, VALORES_DIFERENTES_CONOCIMIENTOS -1)];

    if ($faker->biasedNumberBetween(0, 1))
        $user['test_a_result'] = $faker->biasedNumberBetween(10, 100);

    if ($faker->biasedNumberBetween(0, 1))
        $user['test_b_result'] = getTestBCritera();

    return $user;
}


function createFakeOffer(){
    
    Global $faker, $LABORAL_SECTOR_VALUES, $KNOWLEDGE_VALUES;
    
    $date  = new DateTime($faker->date($format = 'Y-m-d', $max = 'now') );

    $offer = [
        'title'              => $faker->company,
        'description'        => $faker->text,
        'incorporation_date' => $date->getTimestamp(),
        'status'             => $faker->biasedNumberBetween(0, 1)
    ];

    $offer['laboral_sector'] = $LABORAL_SECTOR_VALUES[$faker->biasedNumberBetween(0, VALORES_DIFERENTES_SECTOR_LABORAL -1)];
    
    for($i = 0; $i <= $faker->biasedNumberBetween(3, 6); $i++)
        $offer['knoledge'] = $KNOWLEDGE_VALUES[$faker->biasedNumberBetween(0, VALORES_DIFERENTES_CONOCIMIENTOS -1)];

    if ($faker->biasedNumberBetween(0, 1))
        $offer['test_a_criteria'] = $faker->biasedNumberBetween(10, 100);

    if ($faker->biasedNumberBetween(0, 1))
        $offer['test_b_criteria'] = getTestBCritera();


    return $offer;
}

  
function getTestBCritera(){

    Global $faker;

    $numbers   = [];
    $numbers[] = $faker->biasedNumberBetween(0, 100);
    $numbers[] = $faker->biasedNumberBetween(0, 100 - $numbers[0]);
    $numbers[] = 100 - $numbers[0] - $numbers[1];

    return $numbers;
}

?>