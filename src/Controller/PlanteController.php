<?php

namespace App\Controller;

use App\Entity\Plante;
use App\Form\PlanteType;
use App\Repository\PlanteRepository;
use App\Repository\PlantRepository;
use App\Repository\SensorDataRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanteController extends AbstractController
{
    /**
     * @Route("/plant/{id}", name="plant",requirements={"id":"\d+"})
     */
    public function plant(PlantRepository $plantRepository,$id,SensorDataRepository $sensorDataRepository): Response
    {
        $plant = $plantRepository->findAllPlanteOfPlantByID($id);
        
        $CePlant = $plantRepository->findByID($id);
        $Allmesures = $sensorDataRepository->findAllOf($CePlant[0]->getArrosage()->getId());
        $Temp_Hum = $sensorDataRepository->findLastMesure($CePlant[0]->getArrosage()->getId());
        return $this->render('plante/index.html.twig', [
            'plant' => $plant,
            'controller_name' => 'PlanteController',
            'mesures' => $Temp_Hum,
            'Allmesures' =>$Allmesures,
        ]);
    }

     /**
     * @Route("/plant/{id}/new", name="newPlante",requirements={"id":"\d+"})
     */
    public function newPlante(EntityManagerInterface $entityManager, Request $request,$id,PlantRepository $plantRepository): Response
    {
        $entity = new Plante;

      

       /*On recherche le plant ou va etre cree la plante grace à l'id transmis dans l'url*/
        $plant = $plantRepository->findAllPlanteOfPlantByID($id);
        
         /*Pour afficher le nom de l'entité dans le titre du formulaire*/
        $entity_name ="Plante dans ".$plant[0]->getEspece();
        
        
        /*Recupere donnée formulaire*/
        $form = $this->createForm(PlanteType::class, $entity);
        $form->handleRequest($request);

        //On ajoute la Serre
        $entity->setPlant($plant[0]);

        $array_plante =  $plant[0]->getPlantes()->toArray();
        //Si c'est PAS la premiere plante cree
        if( count($array_plante) >= 1)
        {
          $entity->setNumeros(count($array_plante)+1);
        }
        //Si c'est la PREMIERE plante cree
        else
        {
            $entity->setNumeros(1);
        }
        
        

        //On vérifie si le formulaire est valide et on transmet les donnée dans la base
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($entity);
            $entityManager->flush();

            /*Pour toast (pas encore implementer) */
            // $this->addFlash('sucess','Le nouveau plant a bien été ajouté!');

            return $this->redirectToRoute("plant",['id' => $id]);
        }
        return $this->render('plante/new.html.twig',[
            'form' => $form->createView(),
            'entity_name' => $entity_name,
            'plant' => $plant,
        ]);
    }

     /**
     * @Route("/plant/delete/{id}/{id_plant}",name="deletePlante", requirements={"id":"\d+" , "id_plant":"\d+"})
     */
    public function deletePlant(Plante $entity, Request $request, EntityManagerInterface $entityManager,$id,$id_plant,PlantRepository $plantRepository): Response{
        
        $plant = $plantRepository->findAllPlanteOfPlantByID($entity->getPlant()->getId());
        $array_plante =  $plant[0]->getPlantes()->toArray();

        if($this->isCsrfTokenValid("delete".$entity->getId(), $request->get('token'))){

            
            foreach ($array_plante as &$plante) {
               if($plante->getNumeros() > $entity->getNumeros())
               {
                $plante->setNumeros($plante->getNumeros()-1);
               }
            }

            $entityManager->remove($entity);
            $entityManager->flush();

            //$this->addFlash('success', 'La fiche du chat a bien été supprimé!');

            return $this->redirectToRoute("plant",['id' => $id_plant]);
        }

        return $this->render("plante/delete.html.twig", [
            'entity' => $entity,
            'id_plant' => $id_plant,
        ]);
    }
}
