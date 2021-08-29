<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AppBaseController extends AbstractController
{

    public function sendResponse($result, $message, $headers = ['content-type'=>'application/json'])
    {
        return new JsonResponse([
            'success' => true,
            'data' => $result,
            'message' => $message
        ], Response::HTTP_OK, $headers);
    }

    public function sendError($error, $code = 404, $headers = [])
    {
        return new JsonResponse([
            'success' => false,
            'message' => $error
        ],$code, $headers);
    }
}
