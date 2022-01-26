<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController{

    
    
    /**
     * @Route("/user/list", name="user_list")
     */
    public function list(Request $request){
        
        $response = new JsonResponse();
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