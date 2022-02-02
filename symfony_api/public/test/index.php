<?php

#composer require fzaninotto/faker

# When installed via composer
require_once 'vendor/autoload.php';

echo "<pre>";
for($i = 0; $i <100; $i++){
    var_dump(createFakeUser());
    var_dump(createFakeOffer());
}
echo "</pre>";

function createFakeUser(){
    
    $faker = Faker\Factory::create();
    
    $date = new DateTime($faker->date($format = 'Y-m-d', $max = 'now') );

    $user = [
        'email'      => $faker->safeEmail,
        'firstname'  => $faker->firstName,
        'lastname'   => $faker->lastName,
        'birth_date' => $date->getTimestamp()
    ];

    if ($faker->biasedNumberBetween(0, 1))
    $user['laboral_sector'] = $faker->catchPhrase;

    while($faker->biasedNumberBetween(0, 1))
        $user['knoledge'] = $faker->jobTitle;

    if ($faker->biasedNumberBetween(0, 1))
        $user['test_a_result'] = $faker->biasedNumberBetween(10, 100);

    if ($faker->biasedNumberBetween(0, 1))
        $user['test_b_result'] = getTestBCritera();

    return $user;
}


function createFakeOffer(){
    
    $faker = Faker\Factory::create();
    
    $date = new DateTime($faker->date($format = 'Y-m-d', $max = 'now') );

    $user = [
        'title'              => $faker->company,
        'description'        => $faker->text,
        'incorporation_date' => $date->getTimestamp(),
        'status'             => $faker->biasedNumberBetween(0, 1)
    ];

    if ($faker->biasedNumberBetween(0, 1))
        $user['laboral_sector'] = $faker->catchPhrase;
    
    if ($faker->biasedNumberBetween(0, 1))
        for($i = 0; $i <= $faker->biasedNumberBetween(3, 6); $i++){
            $user['knoledge'] = $faker->jobTitle;
        }

    if ($faker->biasedNumberBetween(0, 1))
        $user['test_a_criteria'] = $faker->biasedNumberBetween(10, 100);

    if ($faker->biasedNumberBetween(0, 1))
        $user['test_b_criteria'] = getTestBCritera();


    return $user;
}

  
function getTestBCritera(){

    $faker = Faker\Factory::create();

    $numbers   = [];
    $numbers[] = $faker->biasedNumberBetween(0, 100);
    $numbers[] = $faker->biasedNumberBetween(0, 100 - $numbers[0]);
    $numbers[] = 100 - $numbers[0] - $numbers[1];

    return $numbers;
}

?>