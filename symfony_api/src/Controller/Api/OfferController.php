<?php
namespace App\Controller\Api;

use App\Entity\Offer;
use App\Entity\Knowledge;
use App\Entity\KnowledgeOfferAssignments;
use App\Entity\LaboralSector;
use App\Entity\LaboralSectorOfferAssignments;
use App\Form\Type\OfferFormType;
use App\Repository\KnowledgeRepository;
use App\Repository\LaboralSectorRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;


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
        
        $offer = new Offer;
        $form = $this->createForm(OfferFormType::class, $offer);
        
        //Indicamos al formulario que maneje la peticion
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            
            if (!empty($laboral_sector))
                $this->setOfferLaboralSector($em, $laboralSectorRepository, $laboral_sector, $offer);
            
                
            if (!empty($knowledge))
                $this->setOfferKnowledge($em, $knowledge_repository, $knowledge, $offer);
                
            $em->persist($offer);
            $em->flush();
            
            $this->logger->info('Offer created');
            return $offer;

        }

        return $form;

    }



    private function setOfferKnowledge(EntityManagerInterface $em, KnowledgeRepository $knowledge_repository, $knowledge_names, $offer){
    
        $knowledge_array = explode(',', $knowledge_names);

        foreach($knowledge_array as $user_knowledge_name){
         
            $query = ['name' => $user_knowledge_name];

            $knowledge = $knowledge_repository->findOneBy($query);

            if (!$knowledge){
                $knowledge = new Knowledge();
                $knowledge->setName($user_knowledge_name);

                $em->persist($knowledge);
            }

            $knowledgeOfferAssignment = new KnowledgeOfferAssignments();
            $knowledgeOfferAssignment->setOfferId($offer)
                                    ->setKnowledgeId($knowledge);
            
            $em->persist($knowledgeOfferAssignment);

        }
    }


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