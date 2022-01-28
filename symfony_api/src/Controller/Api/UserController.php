<?php
namespace App\Controller\Api;

use App\Entity\Knowledge;
use App\Entity\KnowledgeAssignments;
use App\Entity\LaboralSector;
use App\Entity\LaboralSectorAssignments;
use App\Entity\User;
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
    public function postAction(Request $request, EntityManagerInterface $em, LaboralSectorRepository $laboralSectorRepository, KnowledgeRepository $knowledge_repository)
    {
        $email = $request->get('email', null);
        $firstname = $request->get('firstname', null);
        $lastname = $request->get('lastname', null);
        $birth_date_timestamp = $request->get('birth_date', null);
        $laboral_sector = $request->get('laboral_sector', null);
        $knowledge = $request->get('knowledge', null);

        $error_messages = $this->checkDataInputCreate($email, $firstname, $lastname, $birth_date_timestamp);

        if (!empty($error_messages)){
            return null;
        }

        $birth_date = new \DateTime(); 
        $birth_date->setTimestamp($birth_date_timestamp);

        $user = new User;
        $user->setEmail($email)
             ->setFirstname($firstname)
             ->setLastname($lastname)      
             ->setBirthDate($birth_date);
        
        $em->persist($user);

        if (!empty($laboral_sector)){
            
            $query = ['name' => $laboral_sector];
            if ($resultado = $laboralSectorRepository->findOneBy($query)){
                $laboralSectorAssignment = new LaboralSectorAssignments();
                $laboralSectorAssignment->setUserId($user)
                                        ->setLaboralSectorId($resultado);
                
                $em->persist($laboralSectorAssignment);
            }else{
                $laboral_sector_new = new LaboralSector();
                $laboral_sector_new->setName($laboral_sector);

                $em->persist($laboral_sector_new);

                $laboralSectorAssignment = new LaboralSectorAssignments();
                $laboralSectorAssignment->setUserId($user)
                                        ->setLaboralSectorId($laboral_sector_new);
                
                $em->persist($laboralSectorAssignment);
            }
        }


        if (!empty($knowledge)){
            
            $knowledge_array = explode(',', $knowledge);

            foreach($knowledge_array as $user_knowledge_name){
             
                $query = ['name' => $user_knowledge_name];
                if ($resultado = $knowledge_repository->findOneBy($query)){
                    $knowledgeAssignment = new KnowledgeAssignments();
                    $knowledgeAssignment->setUserId($user)
                                            ->setKnowledgeId($resultado);
                    
                    $em->persist($knowledgeAssignment);
                }else{
                    $knowledge_new = new Knowledge();
                    $knowledge_new->setName($user_knowledge_name);

                    $em->persist($knowledge_new);

                    $knowledgeAssignment = new KnowledgeAssignments();
                    $knowledgeAssignment->setUserId($user)
                                        ->setKnowledgeId($knowledge_new);
                    
                    $em->persist($knowledgeAssignment);
                }
            }
        }


        $em->flush();

        $this->logger->info('User created');

        return $user;
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