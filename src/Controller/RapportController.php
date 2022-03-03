<?php

namespace App\Controller;

use App\Entity\Rapport;
use App\Form\RapportType;
use App\Repository\PlanteRepository;
use App\Repository\PlantRepository;
use App\Repository\RapportRepository;
use App\Repository\SerreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RapportController extends AbstractController
{
    /**
     * @Route("/rapport", name="rapport")
     */
    public function index(RapportRepository $rapportRepository): Response
    {
        $rapports = $rapportRepository->findAll();//On récupère toute les serres
        return $this->render('rapport/index.html.twig', [
            'controller_name' => 'RapportController',
            'rapports' => $rapports,
        ]);
    }
     /**
     * @Route("/rapport/new", name="newRapport")
     */
    public function newRapport(EntityManagerInterface $entityManager, Request $request): Response
    {
        $entity = new Rapport;
        $entity_name ="Rapport";
        
        $form = $this->createForm(RapportType::class, $entity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($entity);
            $entityManager->flush();

            // $this->addFlash('sucess','La nouvelle serre a bien été ajouté!');

            return $this->redirectToRoute("rapport");
        }

        return $this->render('rapport/new.html.twig',[
            'form' => $form->createView(),
            'entity_name' => $entity_name,
        ]);
    }
    /**
     * @Route("/rapport/delete/{id}",name="deleteRapport", requirements={"id":"\d+"})
     */
    public function deleteRapport(Rapport $entity, Request $request, EntityManagerInterface $entityManager): Response{
        
        

        if($this->isCsrfTokenValid("delete".$entity->getId(), $request->get('token'))){

            $entityManager->remove($entity);
            $entityManager->flush();

            //$this->addFlash('success', 'La fiche du chat a bien été supprimé!');

            return $this->redirectToRoute("rapport");
        }

        return $this->render("rapport/delete.html.twig", [
            'entity' => $entity,
            
        ]);
    }
    /**
     * @Route("/serre/{id}/rapport", name="SerreRapport",requirements={"id":"\d+"})
     */
    public function SerreRapport(RapportRepository $rapportRepository,$id,SerreRepository $serreRepository): Response
    {
        $rapports = $rapportRepository->findBySerre($id);
        $serre = $serreRepository->findByID($id);
        return $this->render('rapport/index.html.twig', [
            'route_name' => 'SerreRapport',
            'rapports' => $rapports,
            'serre' => $serre,
        ]);
    }
     /**
     * @Route("/serre/{id}/rapport/new", name="newSerreRapport")
     */
    public function newSerreRapport(EntityManagerInterface $entityManager, Request $request,$id,SerreRepository $serreRepository): Response
    {
        $entity = new Rapport;
       
        $serre = $serreRepository->findByID($id);
        $entity_name ="Rapport pour ".$serre[0];
        $form = $this->createForm(RapportType::class, $entity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entity->setSerre($serre[0]);
            $entityManager->persist($entity);
            $entityManager->flush();

            // $this->addFlash('sucess','La nouvelle serre a bien été ajouté!');

            return $this->redirectToRoute("SerreRapport",['id' => $id]);
        }

        return $this->render('rapport/new.html.twig',[
            'form' => $form->createView(),
            'entity_name' => $entity_name,
        ]);
    }
     /**
     * @Route("/plant/{id}/rapport", name="PlantRapport",requirements={"id":"\d+"})
     */
    public function PlantRapport(RapportRepository $rapportRepository,$id,PlantRepository $plantRepository): Response
    {
        $rapports = $rapportRepository->findByPlant($id);
        $plant = $plantRepository->findByID($id);
        return $this->render('rapport/index.html.twig', [
            'route_name' => 'PlantRapport',
            'rapports' => $rapports,
            'plant' => $plant,
        ]);
    }
     /**
     * @Route("/plant/{id}/rapport/new", name="newPlantRapport")
     */
    public function newPlantRapport(EntityManagerInterface $entityManager, Request $request,$id,PlantRepository $plantRepository): Response
    {
        $entity = new Rapport;
        
        $plant = $plantRepository->findByID($id);
        $entity_name ="Rapport pour ".$plant[0];
        $form = $this->createForm(RapportType::class, $entity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entity->setPlant($plant[0]);
            $entityManager->persist($entity);
            $entityManager->flush();

            // $this->addFlash('sucess','La nouvelle serre a bien été ajouté!');

            return $this->redirectToRoute("PlantRapport",['id' => $id]);
        }

        return $this->render('rapport/new.html.twig',[
            'form' => $form->createView(),
            'entity_name' => $entity_name,
        ]);
    }
    /**
     * @Route("/plante/{id}/rapport", name="PlanteRapport",requirements={"id":"\d+"})
     */
    public function PlanteRapport(RapportRepository $rapportRepository,$id,PlanteRepository $planteRepository): Response
    {
        $rapports = $rapportRepository->findByPlante($id);
        $plante = $planteRepository->findByID($id);
        return $this->render('rapport/index.html.twig', [
            'route_name' => 'PlanteRapport',
            'rapports' => $rapports,
            'plante' => $plante,
        ]);
    }
     /**
     * @Route("/plante/{id}/rapport/new", name="newPlanteRapport")
     */
    public function newPlanteRapport(EntityManagerInterface $entityManager, Request $request,$id,PlanteRepository $planteRepository): Response
    {
        $entity = new Rapport;
      
        $plante = $planteRepository->findByID($id);
        $entity_name ="Rapport pour ".$plante[0];
        $form = $this->createForm(RapportType::class, $entity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entity->setPlante($plante[0]);
            $entityManager->persist($entity);
            $entityManager->flush();

            // $this->addFlash('sucess','La nouvelle serre a bien été ajouté!');

            return $this->redirectToRoute("PlanteRapport",['id' => $id]);
        }

        return $this->render('rapport/new.html.twig',[
            'form' => $form->createView(),
            'entity_name' => $entity_name,
        ]);
    }
}
