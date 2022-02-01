<?php
namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;


class MiscController extends AbstractFOSRestController 
{

    private $logger; 
    private $em; 
    
    public function __construct(LoggerInterface $logger, EntityManagerInterface $em){
        
        $this->logger = $logger;
        $this->em = $em;
    }


     /**
     * @Rest\Post(path="/misc/upload")
     * @Rest\View(serializerGroups={"misc"},serializerEnableMaxDepthChecks=true)
     * 
     * Creacion masiva de usuarios y ofertas
     */
    public function postAction(Request $request){

        $users = $request->get('users');
        $offers = $request->get('offers');
      
        $llamada = $this->forward('App\Controller\Api\UserController::createMassiveUsers', [
            'users_data'  => $users
        ]);


        $llamada = $this->forward('App\Controller\Api\OfferController::createMassiveOffers', [
            'offers_data'  => $offers
        ]);

    }



    public function findRecommendedOffersToUser(string $email)
    {
        $conn = $this->em->getConnection();

        $sql = '
            SELECT * FROM product p
            WHERE p.price > :price
            ORDER BY p.price ASC
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['price' => $price]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
}