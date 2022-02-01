<?php
namespace App\Controller\Api;

use App\Entity\Offer;
use App\Entity\Knowledge;
use App\Entity\KnowledgeOfferAssignments;
use App\Entity\LaboralSector;
use App\Entity\LaboralSectorOfferAssignments;
use App\Entity\OfferCriteriaTestA;
use App\Entity\OfferCriteriaTestB;
use App\Form\Type\OfferFormType;
use App\Repository\KnowledgeRepository;
use App\Repository\LaboralSectorRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OfferController extends AbstractFOSRestController
{

    private $logger; 
    
    public function __construct(LoggerInterface $logger){
        
        $this->logger = $logger;
    }


    /**
    * @Rest\Get(path="/offers")
    * @Rest\View(serializerGroups={"offer"},serializerEnableMaxDepthChecks=true)
    */
    public function getAction(OfferRepository $userRepository)
    {
        $this->logger->info('Users listed');
        return $userRepository->findAll();
    }

    /**
     * @Rest\Post(path="/offers")
     * @Rest\View(serializerGroups={"offer"},serializerEnableMaxDepthChecks=true)
     */
    public function postAction( Request $request, 
                                EntityManagerInterface $em, 
                                LaboralSectorRepository $laboralSectorRepository, 
                                KnowledgeRepository $knowledge_repository)
    {
        $form = $request->get('offer_form', '');
        $knowledge = $form['knowledge'];
        $laboral_sector = $form['laboral_sector'];
        $test_a_criteria = $form['test_a_criteria'];
        $test_b_criteria = $form['test_b_criteria'];
        
        $offer = new Offer;
        $form = $this->createForm(OfferFormType::class, $offer);
        
        //Indicamos al formulario que maneje la peticion
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            if (!empty($laboral_sector))
                $this->setOfferLaboralSector($em, $laboralSectorRepository, $laboral_sector, $offer);
                
            if (!empty($knowledge)){
                $res = $this->setOfferKnowledge($em, $knowledge_repository, $knowledge, $offer);
                if ($res === false)
                    return $this->sendResponse(400, null, 'Bad knowledges field request');
            }

            if (!empty($test_a_criteria))
                $res = $this->setOfferTestACriteria($em, $test_a_criteria, $offer);
                
            if (!empty($test_b_criteria))
                $res = $this->setOfferTestBCriteria($em, $test_b_criteria, $offer);



            $em->persist($offer);
            $em->flush();
            
            $this->logger->info('Offer created');
            return $this->sendResponse(201, $offer->getId(), 'Offer created');

        }

        return $form;

    }


    /**
     * Establecemos laos conocimientos de la oferta
     *
     * @param EntityManagerInterface $em
     * @param KnowledgeRepository $knowledge_repository
     * @param [type] $knowledge_names
     * @param [type] $offer
     * @return void
     */ 
    private function setOfferKnowledge(EntityManagerInterface $em, KnowledgeRepository $knowledge_repository, $knowledge_names, $offer){

        $knowledge_count = count($knowledge_names);
        if ($knowledge_count < 3 || $knowledge_count > 6)
            return false;

        foreach($knowledge_names as $offer_knowledge_name){
         
            $query = ['name' => $offer_knowledge_name];

            $knowledge = $knowledge_repository->findOneBy($query);

            if (!$knowledge){
                $knowledge = new Knowledge();
                $knowledge->setName($offer_knowledge_name);

                $em->persist($knowledge);
            }

            $knowledgeOfferAssignment = new KnowledgeOfferAssignments();
            $knowledgeOfferAssignment->setOfferId($offer)
                                     ->setKnowledgeId($knowledge);
            
            $em->persist($knowledgeOfferAssignment);
        }
    }


    /**
     * Establece el sector laboral de la oferta
     * Si existe se toma el id para crear el nuevo registro, si no, se crea un registro de sector laboral nuevo y se asocia al nuevo registro de sector laboral
     *
     * @param EntityManagerInterface $em
     * @param LaboralSectorRepository $laboralSectorRepository
     * @param [type] $laboral_sector_name
     * @param [type] $offer
     * @return void
     */
    private function setOfferLaboralSector(EntityManagerInterface $em, LaboralSectorRepository $laboralSectorRepository, $laboral_sector_name, $offer){
       
        $query = ['name' => $laboral_sector_name];
       
        $laboral_sector = $laboralSectorRepository->findOneBy($query);

        if (!$laboral_sector){
            $laboral_sector = new LaboralSector();
            $laboral_sector->setName($laboral_sector_name);

            $em->persist($laboral_sector);
        }

        $laboralSectorOfferAssignment = new LaboralSectorOfferAssignments();
        $laboralSectorOfferAssignment->setOfferId($offer)
                                ->setLaboralSectorId($laboral_sector);
        
        $em->persist($laboralSectorOfferAssignment);
    }

    
    /**
     * Establece los criterios de la oferta para el test A
     *
     * @param EntityManagerInterface $em
     * @param [type] $criteria_value
     * @param [type] $offer
     * @return void
     */
    private function setOfferTestACriteria(EntityManagerInterface $em, $criteria_value, $offer){
        $test_a_criteria = new OfferCriteriaTestA();
        $test_a_criteria->setMinimunPercent($criteria_value);
        $test_a_criteria->setOfferId($offer);
        $em->persist($test_a_criteria);
    }


    /**
     * Establece los criterios de la oferta para el test B
     *
     * @param EntityManagerInterface $em
     * @param [type] $criteria_value
     * @param [type] $offer
     * @return void
     */
    private function setOfferTestBCriteria(EntityManagerInterface $em, $criteria_values, $offer){
        $test_b_criteria = new OfferCriteriaTestB();
        $test_b_criteria->setDesiredPercentA($criteria_values[0]);
        $test_b_criteria->setDesiredPercentB($criteria_values[1]);
        $test_b_criteria->setDesiredPercentC($criteria_values[2]);
        $test_b_criteria->setOfferId($offer);
        $em->persist($test_b_criteria);
    }


    /**
     * Establece la respuesta de error 
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