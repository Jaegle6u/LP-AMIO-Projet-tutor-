<?php

namespace App\Controller;

use App\Repository\ArrosageRepository;
use App\Repository\SensorDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("api/arrosage.{_format}/{id}", defaults={"_format": "json"},requirements={"id":"\d+"})
     */
    public function apiArrosage(string $_format, ArrosageRepository $arrosageRepository, SerializerInterface $serializer,$id): Response
    {
        

        $entities = $arrosageRepository->findByID($id);

        return new Response(
            $serializer->serialize($entities, $_format, ['json_encode_options' => \JSON_PRETTY_PRINT]), 
            Response::HTTP_OK, 
            ['content-type' => 'application/'.$_format]
        );
    }

     /**
     * @Route("api/temperature.{_format}/{id}", defaults={"_format": "json"},requirements={"id":"\d+"})
     */
    public function apiTemperature(string $_format, SensorDataRepository $sensorDataRepository, SerializerInterface $serializer,$id): Response
    {
        

        $temp = $sensorDataRepository->findAllTempOf($id);

        return new Response(
            $serializer->serialize($temp, $_format, ['json_encode_options' => \JSON_PRETTY_PRINT]), 
            Response::HTTP_OK, 
            ['content-type' => 'application/'.$_format]
        );
    }
    /**
     * @Route("api/humidite.{_format}/{id}", defaults={"_format": "json"},requirements={"id":"\d+"})
     */
    public function apiHumidite(string $_format, SensorDataRepository $sensorDataRepository, SerializerInterface $serializer,$id): Response
    {
        

        $hum = $sensorDataRepository->findAllHumOf($id);

        return new Response(
            $serializer->serialize($hum, $_format, ['json_encode_options' => \JSON_PRETTY_PRINT]), 
            Response::HTTP_OK, 
            ['content-type' => 'application/'.$_format]
        );
    }
     /**
     * @Route("api/humidite_sol.{_format}/{id}", defaults={"_format": "json"},requirements={"id":"\d+"})
     */
    public function apiHumiditeSol(string $_format, SensorDataRepository $sensorDataRepository, SerializerInterface $serializer,$id): Response
    {
        

        $humSol = $sensorDataRepository->findAllHumSolOf($id);

        return new Response(
            $serializer->serialize($humSol, $_format, ['json_encode_options' => \JSON_PRETTY_PRINT]), 
            Response::HTTP_OK, 
            ['content-type' => 'application/'.$_format]
        );
    }
}
