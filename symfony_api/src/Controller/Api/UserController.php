<?php
namespace App\Controller\Api;

use App\Entity\Knowledge;
use App\Entity\KnowledgeAssignments;
use App\Entity\LaboralSector;
use App\Entity\LaboralSectorAssignments;
use App\Entity\User;
use App\Form\Type\UserFormType;
use App\Repository\KnowledgeRepository;
use App\Repository\LaboralSectorRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractFOSRestController
{

    private $logger; 
    
    public function __construct(LoggerInterface $logger){
        
        $this->logger = $logger;
    }


    /**
    * @Rest\Get(path="/users")
    * @Rest\View(serializerGroups={"user"},serializerEnableMaxDepthChecks=true)
    */
    public function getAction(UserRepository $userRepository)
    {
        $this->logger->info('Users listed');
        return $userRepository->findAll();
    }

    /**
     * @Rest\Post(path="/users")
     * @Rest\View(serializerGroups={"user"},serializerEnableMaxDepthChecks=true)
     */
    public function postAction( Request $request, 
                                EntityManagerInterface $em, 
                                LaboralSectorRepository $laboralSectorRepository, 
                                KnowledgeRepository $knowledge_repository)
    {
        $form = $request->get('user_form', '');
        $knowledge = $form['knowledge'];
        $laboral_sector = $form['laboral_sector'];

        
        $user = new User;
        $form = $this->createForm(UserFormType::class, $user);
        
        //Indicamos al formulario que maneje la peticion
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            if (!empty($laboral_sector))
                $this->setUserLaboralSector($em, $laboralSectorRepository, $laboral_sector, $user);
            
            
            if (!empty($knowledge))
                $this->setUserKnowledge($em, $knowledge_repository, $knowledge, $user);
            
            $actual_date = new \DateTime('now');
            $user->setCreated($actual_date->getTimestamp());

            $em->persist($user);
            $em->flush();
            
            return $user;

            $this->logger->info('User created');

            return $user;
        }

        return $form;


        // $email = $request->get('email', '');
        // $firstname = $request->get('firstname', '');
        // $lastname = $request->get('lastname', '');
        // $birth_date_input = $request->get('birth_date', '');
        // $laboral_sector = $request->get('laboral_sector', '');
        // $knowledge = $request->get('knowledge', '');

       
        // $birth_date = new \DateTime();
        // if(is_numeric($birth_date_input))
        //     $birth_date->setTimestamp($birth_date_input);
        // else
        //     $birth_date->createFromFormat("Y-m-d H:i", $birth_date_input);
            
        // $user = new User;
        // $user->setEmail($email)
        //      ->setFirstname($firstname)
        //      ->setLastname($lastname)      
        //      ->setBirthDate($birth_date);
        

        // // Validamos los datos que se han introducido en la entidad
        // // Se usa annotations en /src/entity para establecr las reglas
        // $errors = $validator->validate($user);

        //  if (count($errors) > 0) {
        //     $errorsString = (string) $errors;
        //     return $errorsString;
        // }    
            
        
        // if (!empty($laboral_sector))
        //     $this->setUserLaboralSector($em, $laboralSectorRepository, $laboral_sector, $user);

        // if (!empty($knowledge))
        //     $this->setUserKnowledge($em, $knowledge_repository, $knowledge, $user);

        // $em->persist($user);
        // $em->flush();

        $this->logger->info('User created');

        return $user;
    }



    private function setUserKnowledge(EntityManagerInterface $em, KnowledgeRepository $knowledge_repository, $knowledge_names, $user){
    
        $knowledge_array = explode(',', $knowledge_names);

        foreach($knowledge_array as $user_knowledge_name){
         
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