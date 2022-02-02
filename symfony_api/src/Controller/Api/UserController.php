<?php
namespace App\Controller\Api;

use App\Entity\Knowledge;
use App\Entity\KnowledgeAssignments;
use App\Entity\LaboralSector;
use App\Entity\LaboralSectorAssignments;
use App\Entity\User;
use App\Entity\UserAnswersTestA;
use App\Entity\UserAnswersTestB;
use App\Form\Type\UserFormType;
use App\Repository\KnowledgeRepository;
use App\Repository\LaboralSectorRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UserController extends AbstractFOSRestController{


    private $logger; 
    private $em; 
    private $laboralSectorRepository; 
    private $knowledgeRepository; 
    private $userRepository; 


    
    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, LaboralSectorRepository $laboralSectorRepository, KnowledgeRepository $knowledgeRepository, UserRepository $userRepository){
        
        $this->logger = $logger;
        $this->em = $em;
        $this->laboralSectorRepository = $laboralSectorRepository;
        $this->knowledgeRepository = $knowledgeRepository;
        $this->userRepository = $userRepository;
    }



    /**
    * @Rest\Get(path="/users")
    * @Rest\View(serializerGroups={"user"},serializerEnableMaxDepthChecks=true)
    *
    * Devuelve el listado de usuarios con sus campos
    */
    public function getAction()
    {
        $this->logger->info('Users listed');
        $users = $this->userRepository->findAll();

        if (empty($users))
            return $this->sendResponse(204, null, null);

        return $users;
    }



    /**
     * @Rest\Post(path="/users")
     * @Rest\View(serializerGroups={"user"},serializerEnableMaxDepthChecks=true)
     *
     * Recibe la llamada POST para crear un usuario
     */
    public function postAction( Request $request){
        
        $form = $request->get('user_form', '');
        
        // return $form;
        return $this->createUserFromFormData($form, $request);

    }



    /**
     * Crea un usuario con los datos recibidos de la llamada POST postAction
     *
     * @param [type] $form
     * @param Request $request
     * @return void
     */
    private function createUserFromFormData($form, Request $request){

        $email = $form['email'];
        $query = ['email' => $email];
        $user = $this->userRepository->findOneBy($query);

        if ($user)
            return $this->sendResponse(400, null, 'User already exists');


        $knowledge =           isset($form['knowledge']) ? $form['knowledge'] : null;
        $laboral_sector =      isset($form['laboral_sector']) ? $form['laboral_sector'] : null;
        $user_answers_test_a = isset($form['user_answers_test_a']) ? $form['user_answers_test_a'] : null;
        $user_answers_test_b = isset($form['user_answers_test_b']) ? $form['user_answers_test_b'] : null;

        $user = new User;
        $form = $this->createForm(UserFormType::class, $user);
        
        //Indicamos al formulario que maneje la peticion
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            if (!empty($laboral_sector))
                $this->setUserLaboralSector($laboral_sector, $user);
            
            if (!empty($knowledge))
                $this->setUserKnowledge($knowledge, $user);

            if (!empty($user_answers_test_a))
                $this->setUserAnswerTestA($user_answers_test_a, $user);

            if (!empty($user_answers_test_b)){
                $res = $this->setUserAnswersTestB($user_answers_test_b, $user);
                if ($res === false)
                    return $this->sendResponse(400, null, 'Bad Test B answers field request');
            }
            
            $actual_date = new \DateTime('now');
            $user->setCreated($actual_date->getTimestamp());

            $this->logger->info('User created');
            $this->em->persist($user);
            $this->em->flush();

            return $this->sendResponse(201, null, 'User created');
        }

        return $form;
    }

    

    /**
     * Carga masiva de usuarios
     *
     * @param [Object] $users_data 
     * @return void
     */
    public function createMassiveUsers($users_data){
        $messages = [];
        foreach ($users_data as $user_data){
            $data[] = [
                        'user'   => $user_data['email'],
                        'result' => $this->createUserFromJsonData($user_data)
            ];
        }
        return $this->sendResponse(200, 'OK', $data);
    }



    /**
     * Crea usuario con los datos recibidos por la funcion de carga masiva /api/misc/upload
     *
     * @param [type] $user_data
     * @return void
     */
    private function createUserFromJsonData($user_data){

        $email = $user_data['email'];
        $query = ['email' => $email];
        $user = $this->userRepository->findOneBy($query);

        if ($user)
            return 'User already exists';


        $user = new User;
        $user->setEmail($user_data['email']);
        $user->setFirstname($user_data['firstname']);
        $user->setLastname($user_data['lastname']);
        $user->setBirthDate($user_data['birth_date']);
                                                
        if (!empty($user_data['laboral_sector']))
            $this->setUserLaboralSector($user_data['laboral_sector'], $user);

        if (!empty($user_data['knowledge']))
            $this->setUserKnowledge($user_data['knowledge'], $user);

        if (!empty($user_data['user_answers_test_a']))
            $this->setUserAnswerTestA($user_data['user_answers_test_a'], $user);

        if (!empty($user_data['user_answers_test_b'])){
            $res = $this->setUserAnswersTestB($user_data['user_answers_test_b'], $user);
            if ($res === false)
                return 'Bad Test B answers field request';
        }

        $actual_date = new \DateTime('now');
        $user->setCreated($actual_date->getTimestamp());

        $this->logger->info('User created by massive load');
        $this->em->persist($user);
        $this->em->flush();

        return 'User created';
        
    }



    /**
     * Establece los conocimientos del usuario
     *
     * @param [type] $knowledge_names
     * @param [type] $user
     * @return void
     */
    private function setUserKnowledge($knowledge_names, $user){
    
        foreach($knowledge_names as $user_knowledge_name){
         
            $query = ['name' => $user_knowledge_name];

            $knowledge = $this->knowledgeRepository->findOneBy($query);

            if (!$knowledge){
                $knowledge = new Knowledge();
                $knowledge->setName($user_knowledge_name);

                $this->em->persist($knowledge);
            }

            $knowledgeAssignment = new KnowledgeAssignments();
            $knowledgeAssignment->setUserId($user)
                                ->setKnowledgeId($knowledge);
            
            $this->em->persist($knowledgeAssignment);

        }
    }



    /**
     * Establece el sector laboral del usuario
     *
     * @param [type] $laboral_sector_name
     * @param [type] $user
     * @return void
     */
    private function setUserLaboralSector($laboral_sector_name, $user){
       
        $query = ['name' => $laboral_sector_name];
       
        $laboral_sector = $this->laboralSectorRepository->findOneBy($query);

        if (!$laboral_sector){
            $laboral_sector = new LaboralSector();
            $laboral_sector->setName($laboral_sector_name);

            $this->em->persist($laboral_sector);
        }

        $laboralSectorAssignment = new LaboralSectorAssignments();
        $laboralSectorAssignment->setUserId($user)
                                ->setLaboralSectorId($laboral_sector);
        
        $this->em->persist($laboralSectorAssignment);
    }



    /**
     * Establece la respuesta del usuario para el test A
     *
     * @param [type] $value
     * @param [type] $offer
     * @return void
     */
    private function setUserAnswerTestA($value, $user){
        $user_answers_test_a = new UserAnswersTestA();
        $user_answers_test_a->setTotalPercent($value);
        $user_answers_test_a->setUserId($user);
        $this->em->persist($user_answers_test_a);
    }



    /**
     * Establece la respuesta del usuario para el test A
     *
     * @param [type] $value
     * @param [type] $offer
     * @return void
     */
    private function setUserAnswersTestB($value, $user){
        $user_answers_test_b = new UserAnswersTestB();
        
        if (count($value) != 3 || ($value[0] + $value[1] + $value[2]) != 100)
            return false;

        $user_answers_test_b->setPercentAnswerA($value[0]);
        $user_answers_test_b->setPercentAnswerB($value[1]);
        $user_answers_test_b->setPercentAnswerC($value[2]);
        $user_answers_test_b->setUserId($user);
        $this->em->persist($user_answers_test_b);
    }



    /**
     * Establece la señal de salida
     *
     * @param [type] $status_code
     * @param [type] $data
     * @param [type] $messages
     * @return void
     */
    private function sendResponse($status_code, $data, $messages = null){

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
       
        
        $response_data = [];
        $response_data['code'] = $status_code;
        if ($data != null) 
            $response_data['data'] = $data;
        if ($messages != null) 
            $response_data['message'] = $messages;


        $response->setStatusCode($status_code);
        $response->setContent(json_encode($response_data));
        
        $response->send();
    }



    /**
     * Chequea que no haya valores nulos en los campos requeridos
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