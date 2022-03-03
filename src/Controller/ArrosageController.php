<?php

namespace App\Controller;

use App\Entity\Arrosage;
use App\Form\ArrosageType;
use App\Repository\PlantRepository;
use App\Repository\SerreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArrosageController extends AbstractController
{
    /**
     * @Route("/arrosage", name="arrosage")
     */
    public function index(): Response
    {
        return $this->render('arrosage/index.html.twig', [
            'controller_name' => 'ArrosageController',
        ]);
    }

    /**
     * @Route("/plant/{id}/newArrosage", name="newArrosage",requirements={"id":"\d+"})
     */
    public function newPlant(EntityManagerInterface $entityManager, Request $request,$id,PlantRepository $plantRepository,SerreRepository $serreRepository): Response
    {
        $entity = new Arrosage;

      

       /*On recherche le plant ou va etre cree l'arrosage grace à l'id transmis dans l'url*/
        $plant = $plantRepository->findAllPlanteOfPlantByID($id);

         /*Pour afficher le nom de l'entité dans le titre du formulaire*/
        $entity_name ="Arrosage pour ".$plant[0]->getEspece();
        $new_arrosage = true;
        
        /*Recupere donnée formulaire*/
        $form = $this->createForm(ArrosageType::class, $entity);
        $form->handleRequest($request);

        //On ajoute le plant
        $entity->setplant($plant[0]);

        //On vérifie si le formulaire est valide et on transmet les donnée dans la base
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($entity);
            $entityManager->flush();

            /*Pour toast (pas encore implementer) */
           // $this->addFlash('sucess','Le nouveau plant a bien été ajouté!');

            return $this->redirectToRoute("serre",['id' => $plant[0]->getSerre()->getId()]);
        }

        return $this->render('arrosage/new.html.twig',[
            'form' => $form->createView(),
            'entity_name' => $entity_name,
            'plant' => $plant,
            'new_arrosage' => $new_arrosage,
        ]);
    }

     /**
     * @Route("plant/arrosage/edit/{id}", name="editArrosage",requirements={"id":"\d+"})
     */
    public function editArrosage(Arrosage $arrosage_edit, Request $request, EntityManagerInterface $entityManager,$id, PlantRepository $plantRepository): Response{
        
        

        $edit = true;
        
         
         $form = $this->createForm(ArrosageType::class, $arrosage_edit);
 
         $form->handleRequest($request);
 
         if($form->isSubmitted() && $form->isValid()){
             $entityManager->flush();
 
             //$this->addFlash('sucess','La fiche du chat a bien été modifié!');
 
             return $this->redirectToRoute("serre",['id' => $arrosage_edit->getPlant()->getSerre()->getId()]);
         }
         return $this->render("arrosage/edit.html.twig", [
             'form' => $form->createView(),
             'arrosage_edit' => $arrosage_edit,
             'edit' => $edit,
         ]);
     }

      /**
     * @Route("plant/arrosage/delete/{id}",name="deleteArrosage", requirements={"id":"\d+"})
     */
    public function deleteArrosage(Arrosage $entity, Request $request, EntityManagerInterface $entityManager): Response{
        

        if($this->isCsrfTokenValid("delete".$entity->getId(), $request->get('token'))){
            $entityManager->remove($entity);
            $entityManager->flush();

            //$this->addFlash('success', 'La fiche du chat a bien été supprimé!');

            return $this->redirectToRoute("serre",['id' => $entity->getPlant()->getSerre()->getId()]);
        }

        return $this->render("arrosage/delete.html.twig", [
            'entity' => $entity,
        ]);
    }
}
