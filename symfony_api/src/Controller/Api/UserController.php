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

class UserController extends AbstractFOSRestController
{

    private $logger; 
    private $em; 
    private $laboralSectorRepository; 
    private $knowledge_repository; 
    
    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, LaboralSectorRepository $laboralSectorRepository, KnowledgeRepository $knowledge_repository){
        
        $this->logger = $logger;
        $this->em = $em;
        $this->laboralSectorRepository = $laboralSectorRepository;
        $this->knowledge_repository = $knowledge_repository;
    }


    /**
    * @Rest\Get(path="/users")
    * @Rest\View(serializerGroups={"user"},serializerEnableMaxDepthChecks=true)
    */
    public function getAction(UserRepository $userRepository)
    {
        $this->logger->info('Users listed');
        $users = $userRepository->findAll();

        if (empty($users))
            return $this->sendResponse(204, null, null);

        return $users;
    }

    /**
     * @Rest\Post(path="/users")
     * @Rest\View(serializerGroups={"user"},serializerEnableMaxDepthChecks=true)
     */
    public function postAction( Request $request, 
                                EntityManagerInterface $em, 
                                LaboralSectorRepository $laboralSectorRepository, 
                                KnowledgeRepository $knowledge_repository){
        
        $form = $request->get('user_form', '');
        
        return $this->createUserFromFormData($form, $request, $em, $laboralSectorRepository, $knowledge_repository);

    }


    private function createUserFromFormData($form,
                                            Request $request, 
                                            EntityManagerInterface $em, 
                                            LaboralSectorRepository $laboralSectorRepository, 
                                            KnowledgeRepository $knowledge_repository){
        $knowledge = $form['knowledge'];
        $laboral_sector = $form['laboral_sector'];
        $user_answers_test_a = $form['user_answers_test_a'];
        $user_answers_test_b = $form['user_answers_test_b'];

        $user = new User;
        $form = $this->createForm(UserFormType::class, $user);
        
        //Indicamos al formulario que maneje la peticion
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            if (!empty($laboral_sector))
                $this->setUserLaboralSector($em, $laboralSectorRepository, $laboral_sector, $user);
            
            if (!empty($knowledge))
                $this->setUserKnowledge($em, $knowledge_repository, $knowledge, $user);

            if (!empty($user_answers_test_a))
                $this->setUserAnswerTestA($em, $user_answers_test_a, $user);

            if (!empty($user_answers_test_b)){
                $res = $this->setUserAnswersTestB($em, $user_answers_test_b, $user);
                if ($res === false)
                    return $this->sendResponse(400, null, 'Bad Test B answers field request');
            }
            
            $actual_date = new \DateTime('now');
            $user->setCreated($actual_date->getTimestamp());

            $this->logger->info('User created');
            $em->persist($user);
            $em->flush();

            return $this->sendResponse(201, null, 'User created');
        }

        return $form;
    }


    public function createMassiveUsers($users_data){
        $messages = [];
        foreach ($users_data as $user_data){
            $data[] = [
                        'user'   => $user_data['email'],
                        'result' => $this->createUserFromJsonData($user_data)
            ];
        }

        //return $messages;
        return $this->sendResponse(200, 'Users imported', $data);
    }

    private function createUserFromJsonData($user_data){

        $user = new User;

        $user->setEmail($user_data['email']);
        $user->setFirstname($user_data['firstname']);
        $user->setLastname($user_data['lastname']);
        $user->setBirthDate($user_data['birth_date']);
                                                
        if (!empty($user_data['laboral_sector']))
            $this->setUserLaboralSector($this->em, $this->laboralSectorRepository, $user_data['laboral_sector'], $user);

        if (!empty($user_data['knowledge']))
            $this->setUserKnowledge($this->em, $this->knowledge_repository, $user_data['knowledge'], $user);

        if (!empty($user_data['user_answers_test_a']))
            $this->setUserAnswerTestA($this->em, $user_data['user_answers_test_a'], $user);

        if (!empty($user_data['user_answers_test_b'])){
            $res = $this->setUserAnswersTestB($this->em, $user_data['user_answers_test_b'], $user);
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


    private function setUserKnowledge(EntityManagerInterface $em, KnowledgeRepository $knowledge_repository, $knowledge_names, $user){
    
        foreach($knowledge_names as $user_knowledge_name){
         
            $query = ['name' => $user_knowledge_name];

            $knowledge = $knowledge_repository->findOneBy($query);

            if (!$knowledge){
                $knowledge = new Knowledge();
                $knowledge->setName($user_knowledge_name);

                $em->persist($knowledge);
            }

            $knowledgeAssignment = new KnowledgeAssignments();
            $knowledgeAssignment->setUserId($user)
                                ->setKnowledgeId($knowledge);
            
            $em->persist($knowledgeAssignment);

        }
    }


    private function setUserLaboralSector(EntityManagerInterface $em, LaboralSectorRepository $laboralSectorRepository, $laboral_sector_name, $user){
       
        $query = ['name' => $laboral_sector_name];
       
        $laboral_sector = $laboralSectorRepository->findOneBy($query);

        if (!$laboral_sector){
            $laboral_sector = new LaboralSector();
            $laboral_sector->setName($laboral_sector_name);

            $em->persist($laboral_sector);
        }

        $laboralSectorAssignment = new LaboralSectorAssignments();
        $laboralSectorAssignment->setUserId($user)
                                ->setLaboralSectorId($laboral_sector);
        
        $em->persist($laboralSectorAssignment);
    }


     /**
     * Establece la respuesta del usuario para el test A
     *
     * @param EntityManagerInterface $em
     * @param [type] $value
     * @param [type] $offer
     * @return void
     */
    private function setUserAnswerTestA(EntityManagerInterface $em, $value, $user){
        $user_answers_test_a = new UserAnswersTestA();
        $user_answers_test_a->setTotalPercent($value);
        $user_answers_test_a->setUserId($user);
        $em->persist($user_answers_test_a);
    }



    /**
     * Establece la respuesta del usuario para el test A
     *
     * @param EntityManagerInterface $em
     * @param [type] $value
     * @param [type] $offer
     * @return void
     */
    private function setUserAnswersTestB(EntityManagerInterface $em, $value, $user){
        $user_answers_test_b = new UserAnswersTestB();
        
        if (count($value) != 3 || ($value[0] + $value[1] + $value[2]) != 100)
            return false;

        $user_answers_test_b->setPercentAnswerA($value[0]);
        $user_answers_test_b->setPercentAnswerB($value[1]);
        $user_answers_test_b->setPercentAnswerC($value[2]);
        $user_answers_test_b->setUserId($user);
        $em->persist($user_answers_test_b);
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


    
    /**
     * Establece la respuesta de salida
     */
    private function sendResponse($status_code, $data, $messages = null){

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
       
        
        $response_data = [];
        $response_data['code'] = $status_code;
        if ($data != null) 
            $response_data['data'] = $data;
        if ($messages != null) 
            $response_data['data'] = $messages;


        $response->setStatusCode($status_code);
        $response->setContent(json_encode($response_data));
        
        $response->send();
    }


}