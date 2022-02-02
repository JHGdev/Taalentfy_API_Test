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


class OfferController extends AbstractFOSRestController{


    private $logger; 
    private $em;
    private $laboralSectorRepository;
    private $knowledgeRepository;
    private $offerRepository;

    
    
    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, LaboralSectorRepository $laboralSectorRepository, KnowledgeRepository $knowledgeRepository, OfferRepository $offerRepository){
        
        $this->logger = $logger;
        $this->em = $em;
        $this->laboralSectorRepository = $laboralSectorRepository;
        $this->offerRepository = $offerRepository;
        $this->knowledgeRepository = $knowledgeRepository;
    }



    /**
    * @Rest\Get(path="/offers")
    * @Rest\View(serializerGroups={"offer"},serializerEnableMaxDepthChecks=true)
    *
    * Devuelve el listado de ofertas con sus respectivos campos
    *
    */
    public function getAction()
    {
        $this->logger->info('Users listed');
        $offers = $this->offerRepository->findAll();
        
        if (empty($offers))
            return $this->sendResponse(204, null, null);

        return $offers;
    }



    /**
     * @Rest\Post(path="/offers")
     * @Rest\View(serializerGroups={"offer"},serializerEnableMaxDepthChecks=true)
     * 
     * Recibe la llamada POST para crear una oferta
     */
    public function postAction(Request $request){

        $form = $request->get('offer_form', '');
        return $this->createOfferFromFormData($form, $request);
    }



    /**
     * Crea un usuario con los datos recibidos de la llamada POST postAction
     *
     * @param [type] $form
     * @param [type] $request
     * @return void
     */
    private function createOfferFromFormData($form, $request){

        $title = $form['title'];
        $query = ['title' => $title];
        $offer = $this->offerRepository->findOneBy($query);

        if ($offer)
            return $this->sendResponse(400, null, 'Offer already exists');



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
                //$this->setOfferLaboralSector($this->em, $this->laboralSectorRepository, $laboral_sector, $offer);
                $this->setOfferLaboralSector($laboral_sector, $offer);
                
            if (!empty($knowledge)){
                $res = $this->setOfferKnowledge($knowledge, $offer);
                if ($res === false)
                    return $this->sendResponse(400, null, 'Bad knowledges field request');
            }

            if (!empty($test_a_criteria))
                $this->setOfferTestACriteria($test_a_criteria, $offer);
                
            if (!empty($test_b_criteria)){
                $res = $this->setOfferTestBCriteria($test_b_criteria, $offer);
                if ($res === false)
                    return $this->sendResponse(400, null, 'Bad Test B answers field request');
            }


            $this->em->persist($offer);
            $this->em->flush();
            
            $this->logger->info('Offer created');
            return $this->sendResponse(201, $offer->getId(), 'Offer created');

        }

        return $form;

    }



    /**
     * Carga masiva de ofertas
     *
     * @param [type] $offers_data
     * @return void
     */
    public function createMassiveOffers($offers_data){


        $data = [];
        foreach ($offers_data as $offer_data){
            $data[] = [
                        'offer'  => $offer_data['title'],
                        'result' => $this->createOfferFromJsonData($offer_data)
            ];
        }
        return $this->sendResponse(200, 'OK', $data);
    }



    /**
     * Crea la oferta con los datos recibidos por la funcion de carga masiva "/api/misc/upload"
     *
     * @param [type] $offer_data
     * @return void
     */
    private function createOfferFromJsonData($offer_data){

        $title = $offer_data['title'];
        $query = ['title' => $title];
        $offer = $this->offerRepository->findOneBy($query);

        if ($offer)
            return 'Offer already exits';

        
        $knowledge = $offer_data['knowledge'];
        $laboral_sector = $offer_data['laboral_sector'];
        $test_a_criteria = $offer_data['test_a_criteria'];
        $test_b_criteria = $offer_data['test_b_criteria'];
        
        $offer = new Offer;
        $offer->setTitle($title);
        $offer->setDescription($offer_data['description']);
        $offer->setIncorporationDate($offer_data['incorporation_date']);
        $offer->setStatus($offer_data['status']);

        
        if (!empty($laboral_sector))
            $this->setOfferLaboralSector($laboral_sector, $offer);
            
        if (!empty($knowledge)){
            $res = $this->setOfferKnowledge($knowledge, $offer);
            if ($res === false)
                return 'Bad knowledges field request';
        }

        if (!empty($test_a_criteria))
            $this->setOfferTestACriteria($test_a_criteria, $offer);
            
        if (!empty($test_b_criteria)){
            $res = $this->setOfferTestBCriteria($test_b_criteria, $offer);
            if ($res === false)
                return 'Bad Test B answers field request';
        }


        $this->em->persist($offer);
        $this->em->flush();
        
        $this->logger->info('Offer created');
        return 'Offer created';

    }




    /**
     * Establecemos laos conocimientos de la oferta
     *
     * @param [type] $knowledge_names
     * @param [type] $offer
     * @return void
     */ 
    private function setOfferKnowledge($knowledge_names, $offer){

        $knowledge_count = count($knowledge_names);
        if ($knowledge_count < 3 || $knowledge_count > 6)
            return false;

        foreach($knowledge_names as $offer_knowledge_name){
         
            $query = ['name' => $offer_knowledge_name];

            $knowledge = $this->knowledgeRepository->findOneBy($query);

            if (!$knowledge){
                $knowledge = new Knowledge();
                $knowledge->setName($offer_knowledge_name);

                $this->em->persist($knowledge);
            }

            $knowledgeOfferAssignment = new KnowledgeOfferAssignments();
            $knowledgeOfferAssignment->setOfferId($offer)
                                     ->setKnowledgeId($knowledge);
            
            $this->em->persist($knowledgeOfferAssignment);
        }
    }



    /**
     * Establece el sector laboral de la oferta
     * Si existe se toma el id para crear el nuevo registro, si no, se crea un registro de sector laboral nuevo y se asocia al nuevo registro de sector laboral
     *
     * @param [type] $laboral_sector_name
     * @param [type] $offer
     * @return void
     */
    private function setOfferLaboralSector($laboral_sector_name, $offer){
       
        $query = ['name' => $laboral_sector_name];
       
        $laboral_sector = $this->laboralSectorRepository->findOneBy($query);

        if (!$laboral_sector){
            $laboral_sector = new LaboralSector();
            $laboral_sector->setName($laboral_sector_name);

            $this->em->persist($laboral_sector);
        }

        $laboralSectorOfferAssignment = new LaboralSectorOfferAssignments();
        $laboralSectorOfferAssignment->setOfferId($offer)
                                ->setLaboralSectorId($laboral_sector);
        
        $this->em->persist($laboralSectorOfferAssignment);
    }


    
    /**
     * Establece los criterios de la oferta para el test A
     *
     * @param EntityManagerInterface $em
     * @param [type] $criteria_value
     * @param [type] $offer
     * @return void
     */
    private function setOfferTestACriteria($criteria_value, $offer){
        $test_a_criteria = new OfferCriteriaTestA();
        $test_a_criteria->setMinimunPercent($criteria_value);
        $test_a_criteria->setOfferId($offer);
        $this->em->persist($test_a_criteria);
    }



    /**
     * Establece los criterios de la oferta para el test B
     *
     * @param [type] $criteria_value
     * @param [type] $offer
     * @return void
     */
    private function setOfferTestBCriteria($criteria_values, $offer){
        $test_b_criteria = new OfferCriteriaTestB();
       
        if (count($criteria_values) != 3 || ($criteria_values[0] + $criteria_values[1] + $criteria_values[2]) != 100)
            return false;

        $test_b_criteria->setDesiredPercentA($criteria_values[0]);
        $test_b_criteria->setDesiredPercentB($criteria_values[1]);
        $test_b_criteria->setDesiredPercentC($criteria_values[2]);
        $test_b_criteria->setOfferId($offer);
        $this->em->persist($test_b_criteria);
    }



    /**
     * Establece la respuesta de salida de las llamadas
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