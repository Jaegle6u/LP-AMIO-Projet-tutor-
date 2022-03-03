<?php

namespace App\Controller;

use App\Entity\Plant;
use App\Form\PlantType;
use App\Repository\SensorDataRepository;
use App\Repository\SerreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlantController extends AbstractController
{
    /**
     * @Route("/serre/{id}", name="serre", requirements={"id":"\d+"})
     */
    public function index(SerreRepository $serreRepository,$id,SensorDataRepository $sensorDataRepository): Response
    {
         /*On recherche la serre ou va etre cree le plant grace à l'id transmis dans l'url*/
         $serre = $serreRepository->findByID($id);
       
        return $this->render('plant/index.html.twig', [
            'controller_name' => 'PlantController',
            'serre'=>$serre,
            
            
        ]);
    }

     /**
     * @Route("/serre/{id}/new", name="newPlant",requirements={"id":"\d+"})
     */
    public function newPlant(EntityManagerInterface $entityManager, Request $request,$id,SerreRepository $serreRepository): Response
    {
        $entity = new Plant;

      

       /*On recherche la serre ou va etre cree le plant grace à l'id transmis dans l'url*/
        $serre = $serreRepository->findByID($id);

         /*Pour afficher le nom de l'entité dans le titre du formulaire*/
        $entity_name ="Plant dans ".$serre[0]->getNom();
        
        
        /*Recupere donnée formulaire*/
        $form = $this->createForm(PlantType::class, $entity);
        $form->handleRequest($request);

        //On ajoute la Serre
        $entity->setSerre($serre[0]);

        //On vérifie si le formulaire est valide et on transmet les donnée dans la base
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($entity);
            $entityManager->flush();

            /*Pour toast (pas encore implementer) */
            $this->addFlash('sucess','Le nouveau plant a bien été ajouté!');

            return $this->redirectToRoute("serre",['id' => $id]);
        }

        return $this->render('plant/new.html.twig',[
            'form' => $form->createView(),
            'entity_name' => $entity_name,
            'serre' => $serre,
        ]);
    }
     /**
     * @Route("/serre/delete/{id}/{id_serre}",name="deletePlant", requirements={"id":"\d+" , "id_serre":"\d+"})
     */
    public function deletePlant(Plant $entity, Request $request, EntityManagerInterface $entityManager,$id,$id_serre): Response{
        

        if($this->isCsrfTokenValid("delete".$entity->getId(), $request->get('token'))){
            $entityManager->remove($entity);
            $entityManager->flush();

            //$this->addFlash('success', 'La fiche du chat a bien été supprimé!');

            return $this->redirectToRoute("serre",['id' => $id_serre]);
        }

        return $this->render("plant/delete.html.twig", [
            'entity' => $entity,
            'id_serre' => $id_serre,
        ]);
    }

    /**
     * @Route("/serre/edit/{id}", name="editPlant",requirements={"id":"\d+"})
     */
    public function editPlant(Plant $plant_edit, Request $request, EntityManagerInterface $entityManager,$id,SerreRepository $serreRepository): Response{
        
        

        $edit =true;
         $serre = $serreRepository->findByID($plant_edit->getSerre()->getId());
         $form = $this->createForm(PlantType::class, $plant_edit);
 
         $form->handleRequest($request);
 
         if($form->isSubmitted() && $form->isValid()){
             $entityManager->flush();
 
             //$this->addFlash('sucess','La fiche du chat a bien été modifié!');
 
             return $this->redirectToRoute("serre",['id' => $plant_edit->getSerre()->getId()]);
         }
         return $this->render("plant/edit.html.twig", [
             'form' => $form->createView(),
             'plant_edit' => $plant_edit,
             'serre' => $serre,
             'edit' => $edit,
             
         ]);
     }
}
