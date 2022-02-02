<?php
namespace App\Controller\Api;

use App\Repository\OfferRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use PhpParser\Node\Expr\Cast\String_;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MiscController extends AbstractFOSRestController {


    private $logger; 
    private $em; 
    private $userRepository; 
    private $offerRepository; 
    


    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, UserRepository $userRepository, OfferRepository $offerRepository){
        
        $this->logger = $logger;
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->offerRepository = $offerRepository;
    }



    /**
     * @Rest\Post(path="/misc/upload")
     * @Rest\View(serializerGroups={"misc"},serializerEnableMaxDepthChecks=true)
     * 
     * Creacion masiva de usuarios y ofertas
     */
    public function postAction(Request $request){

        $users  = $request->get('users');
        $offers = $request->get('offers');
      
        $llamada = $this->forward('App\Controller\Api\UserController::createMassiveUsers', [
            'users_data'  => $users
        ]);


        $llamada = $this->forward('App\Controller\Api\OfferController::createMassiveOffers', [
            'offers_data'  => $offers
        ]);

    }



    /**
     * @Rest\Post(path="/misc/get_user_offers")
     * @Rest\View(serializerGroups={"misc"},serializerEnableMaxDepthChecks=true)
     * 
      * Devuelve N recomendaciones para un usuario determinado
     *
     */
    public function findRecommendedOffersFromUser(Request $request){

        $email    = $request->get('email');
        $n_offers = $request->get('n_offers');
        
        if (!$email || !$n_offers)
            return $this->sendResponse(204, null, 'Bad request data');


        $query = ['email' => $email];
        $user  = $this->userRepository->findOneBy($query);
            
        if (!$user)
            return $this->sendResponse(204, null, null);

        return $this->getNOffersFromUser($user->getId(), $n_offers);
    }



    /**
     * @Rest\Post(path="/misc/get_offer_users")
     * @Rest\View(serializerGroups={"misc"},serializerEnableMaxDepthChecks=true)
     * 
     * Devuelve N recomendaciones para una oferta determinada
     *
     */
    public function findRecommendedUsersFromOffer(Request $request){

        $title    = $request->get('title');
        $n_users = $request->get('n_users');
        
        if (!$title || !$n_users)
            return $this->sendResponse(400, null, 'Bad request data');


        $query = ['title' => $title];
        $offer  = $this->offerRepository->findOneBy($query);
            
        if (!$offer)
            return $this->sendResponse(204, null, null);
            

        return $this->getNUsersFromOffer($offer->getId(), $n_users);
        
        $sql  = $this->getSqlQuery('offer', $n_users);
        $conn = $this->em->getConnection();
        $stmt = $conn->prepare($sql);
        
        // Cambiamos :id en la consulta por el id del usuario
        $resultSet = $stmt->executeQuery(['id' => $offer->getId()]);
        $records   = $resultSet->fetchAllAssociative();
        
        return $this->getUsersFromRecomendations($records);
    }



    /**
     * @Rest\Post(path="/misc/get_Musers_Noffers")
     * @Rest\View(serializerGroups={"misc"},serializerEnableMaxDepthChecks=true)
     * 
      * Devuelve N recomendaciones para M usuarios
     *
     */
    public function getNRecommentadionsFromMUsers(Request $request){

        $users    = $request->get('users');
        $n_offers = $request->get('n_offers');
        
        if (!$users || empty($users) || !$n_offers)
            return $this->sendResponse(204, null, 'Bad request data');


        $result = [];
        foreach($users as $email){

            $query = ['email' => $email];
            $user  = $this->userRepository->findOneBy($query);
                
            if (!$user)
                $result[] = [ 
                        'user' => $email,
                        'ERROR' => 'User does not exists'
                    ];
            else
                $result[] = [ 
                        'user'   => $user->getEmail(),
                        'offers' => $this->getNOffersFromUser($user->getId(), $n_offers)
                    ];
        }

        return $result;
    }



    /**
     * @Rest\Post(path="/misc/get_Moffers_Nusers")
     * @Rest\View(serializerGroups={"misc"},serializerEnableMaxDepthChecks=true)
     * 
      * Devuelve N recomendaciones de usuarios para M ofertas
     *
     */
    public function getNRecommentadionsFromMOffers(Request $request){

        $offers  = $request->get('offers');
        $n_users = $request->get('n_users');
        
        if (!$offers || empty($offers) || !$n_users)
            return $this->sendResponse(204, null, 'Bad request data');


        $result = [];
        foreach($offers as $title){

            $query = ['title' => $title];
            $offer = $this->offerRepository->findOneBy($query);
                
            if (!$offer)
                $result[] = [ 
                        'offer' => $title,
                        'ERROR' => 'Offer does not exists'
                    ];
            else
                $result[] = [ 
                        'offer' => $offer->getTitle(),
                        'users' => $this->getNUsersFromOffer($offer->getId(), $n_users)
                    ];
        }

        return $result;
    }



    /**
     * Devuelve N ofertas para el usuario
     *
     * @param [type] $user_id
     * @param [type] $n_offers
     * @return void
     */
    private function getNOffersFromUser($user_id, $n_offers){

        $conn = $this->em->getConnection();
        $sql  = $this->getSqlQuery('user', $n_offers);
        $stmt = $conn->prepare($sql);
    
        // Cambiamos :id en la consulta por el id del usuario
        $resultSet = $stmt->executeQuery(['id' => $user_id]);
        $records   = $resultSet->fetchAllAssociative();
        
        return $this->getOffersFromRecomendations($records);
    }



    /**
     * Devuelve N ofertas para el usuario
     *
     * @param [type] $user_id
     * @param [type] $n_offers
     * @return void
     */
    private function getNUsersFromOffer($offer_id, $n_offers){

        $conn = $this->em->getConnection();
        $sql  = $this->getSqlQuery('offer', $n_offers);
        $stmt = $conn->prepare($sql);
    
        // Cambiamos :id en la consulta por el id del usuario
        $resultSet = $stmt->executeQuery(['id' => $offer_id]);
        $records   = $resultSet->fetchAllAssociative();
        
        return $this->getOffersFromRecomendations($records);
    }



    /**
     * Devuelve la consulta de las recomendaciones
     *
     * @param [type] $entity
     * @param [type] $n Pasamos como parametro el numero de elementos por que no deja pasarlo como parametro de la funcion "executeQuery"
     * @return string
     */
    private function getSqlQuery($entity, $n){

        // Tomamos el primer caracter para preparar el Where
        // user  -> u.id
        // offer -> o.id
        $entity_where = $entity[0];

        $sql = '
                    SELECT 
                        u.id                                                             as user_id, 
                        o.id                                                             as offer_id, 
                        if(taa.total_percent >= octa.minimun_percent, 1, 0)              as test_a_criteria,
                        if(lsa.laboral_sector_id_id = lsoa.laboral_sector_id_id, 1, 0)   as laboral_sector_criteria,
                        abs(octb.desired_percent_a - tab.percent_answer_a)  + 
                        abs(octb.desired_percent_b - tab.percent_answer_b)  + 
                        abs(octb.desired_percent_c - tab.percent_answer_c)               as test_b_criteria,
                        tabla_knowledges.total 								             as knowledge_criteria
                    
                        FROM	       user 					   			as u 
                            LEFT JOIN  laboral_sector_assignments 		    as lsa  ON (u.id = lsa.user_id_id)
                            LEFT JOIN  user_answers_test_a        			as taa  ON (u.id = taa.user_id_id)
                            LEFT JOIN  user_answers_test_b        			as tab  ON (u.id = tab.user_id_id)
                        
                        INNER JOIN  offer                      			    as o     
                            LEFT JOIN  offer_criteria_test_a      			as octa  ON (o.id = octa.offer_id_id)
                            LEFT JOIN  offer_criteria_test_b      			as octb  ON (o.id = octb.offer_id_id)
                            LEFT JOIN  laboral_sector_offer_assignments 	as lsoa  ON (o.id = lsoa.offer_id_id)
                        
                        LEFT JOIN (
                            SELECT user_id_id, offer_id_id, count(distinct(kasq.knowledge_id_id)) as total 
                                FROM 		knowledge_assignments       as kasq 
                                INNER JOIN  knowledge_offer_assignments koasq   ON (kasq.knowledge_id_id = koasq.knowledge_id_id)
                                GROUP BY user_id_id, offer_id_id
                        ) as tabla_knowledges ON (tabla_knowledges.user_id_id = u.id AND tabla_knowledges.offer_id_id = o.id) 
            
                        WHERE   o.status = 1
                            AND '. $entity_where .'.id = :id
                            
                        HAVING  laboral_sector_criteria = 1
                            AND test_a_criteria = 1
                        
                        ORDER BY test_b_criteria ASC,
                                 knowledge_criteria DESC
                            
                        LIMIT 0, '. $n .' ;';
        
        return $sql;

    }



    /**
     * Obtiene los ids de las ofertas, las carga y devuelve el listado de ofertas para el usuario
     *
     * @param [type] $records
     * @return void
     */
    private function getOffersFromRecomendations($records){
        
        $result_data = [];

        foreach($records as $record){
            $offer = $this->offerRepository->findOneBy(['id' => $record['offer_id']]);
            $result_data[] = $offer;
        }

        return $result_data;
    }



    /**
     * Obtiene los ids de los usuarios, los carga y devuelve el listado de usuarios para la oferta
     *
     * @param [type] $records
     * @return void
     */
    private function getUsersFromRecomendations($records){
        
        $result_data = [];

        foreach($records as $record){
            $user = $this->userRepository->findOneBy(['id' => $record['user_id']]);
            $result_data[] = $user;
        }

        return $result_data;
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
            $response_data['data'] = $messages;


        $response->setStatusCode($status_code);
        $response->setContent(json_encode($response_data));
        
        $response->send();
    }

}