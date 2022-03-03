<?php

namespace App\Controller;

use App\Entity\SensorData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SensorDataController extends AbstractController
{
    /**
     * @Route("/sensor/data", name="sensor_data")
     */
    public function index(): Response
    {
        return $this->render('sensor_data/index.html.twig', [
            'controller_name' => 'SensorDataController',
        ]);
    }

     /**
     * @Route("/sensorData/new/{sensor}/{location}/{value1}/{value2}/{value3}", name="newSensorData")
     */
    public function newSensorData(EntityManagerInterface $entityManager,$sensor,$location,$value1,$value2,$value3)
    {
        $entity = new SensorData;
        
        $entity->setSensor($sensor);
        $entity->setLocation($location);
        $entity->setValue1($value1);
        $entity->setValue2($value2);
        $entity->setValue3($value3);


        //On vérifie si le formulaire est valide et on transmet les donnée dans la base
        
            $entityManager->persist($entity);
            $entityManager->flush();

          
            $response = new Response(
                'Content',
                Response::HTTP_OK,
                ['content-type' => 'text/html']
            );
            return $response;
    
    }
}