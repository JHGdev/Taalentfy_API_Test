<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController{


    private $logger; 
    
    public function __construct(LoggerInterface $logger){
        
        $this->logger = $logger;
    }




    /**
     * @Route("/users", name="users_list")
     */
    public function list(Request $request, UserRepository $userRepository){
        
        $response = new JsonResponse();
        $this->logger->info('List action called');

        $users = $userRepository->findAll();
        
        $users_array = array();
        foreach($users as $user){
            $users_array[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'birth_date' => $user->getBirthDate(),
                'created' => $user->getCreated()
            ];
        }

        $response->setData([
            'succes' => true,
            'data'   => $users_array
        ]);
        return $response;

    }


    /**
     * @Route("/user", name="user_create")
     */
    public function createUser(Request $request, EntityManagerInterface $em){
        
        $response = new JsonResponse();

        $email = $request->get('email', null);
        $firstname = $request->get('firstname', null);
        $lastname = $request->get('lastname', null);
        $birth_date_timestamp = $request->get('birth_date', null);

        $error_messages = $this->checkDataInputCreate($email, $firstname, $lastname, $birth_date_timestamp);

        if (!empty($error_messages)){
            $response->setData([
                'succes' => false,
                'error'  => $error_messages,
                'data'   => null
            ]);
            return $response;
        }

        $birth_date = new \DateTime(); 
        $birth_date->setTimestamp($birth_date_timestamp);

        $user = new User();
        $user->setEmail($email)
             ->setFirstname($firstname)
             ->setLastname($lastname)      
             ->setBirthDate($birth_date);

        $em->persist($user);
        $em->flush();

        $this->logger->info('User created');

        $response->setData([
            'succes' => true,
            'data'   => [
                'id'    => $user->getId(),
                'email' => $user->getEmail(),
                'bd' => $birth_date
            ]
        ]);
        return $response;
    }


    /**
     * Chequea que no haya valores nulos 
     *
     * @param [type] $email
     * @param [type] $firstname
     * @param [type] $lastname
     * @param [type] $birth_date
     * @return void
     */
    private function checkDataInputCreate($email, $firstname, $lastname, $birth_date){

        $error_messages = array();

        if (empty($email))
            $error_messages[] = 'Email cannot be null';
        
        if (empty($firstname))
            $error_messages[] = 'Firstname cannot be null';

        if (empty($lastname))
            $error_messages[] = 'Lastname cannot be null';

        if (empty($birth_date))
            $error_messages[] = 'Birth date cannot be null';

        return $error_messages;
    }

}