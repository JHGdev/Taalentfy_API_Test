<?php

namespace App\Controller;

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
     * @Route("/user/list", name="user_list")
     */
    public function list(Request $request){
        
        $response = new JsonResponse();
        $this->logger->info('List action called');
        $response->setData([
            $request,
            'succes' => true,
            'data'   => [
                'texto' => 'AAAAAAAAA'
            ], 
            'title'  => $request->get('asd')
        ]);
        return $response;

    }

}