<?php

namespace App\Controller;

use App\Entity\Serre;
use App\Form\SerreType;
use App\Repository\SerreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoreController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(SerreRepository $serreRepository,Request $request): Response
    {
        $serres = $serreRepository->findAll();//On récupère toute les serres

        return $this->render('core/index.html.twig', [
            'controller_name' => 'CoreController',
            'serres'=>$serres
        ]);
    }

     /**
     * @Route("/{id}", name="homepage_filtre",requirements={"id":"\d+"})
     */
    public function homepage_filtre(SerreRepository $serreRepository,Request $request): Response
    {
        $serres = $serreRepository->findAll();//On récupère toute les serres

        return $this->render('core/index.html.twig', [
            'controller_name' => 'CoreController',
            'serres'=>$serres
        ]);
    }

    /**
     * @Route("/new", name="newSerre")
     */
    public function newSerre(EntityManagerInterface $entityManager, Request $request): Response
    {
        $entity = new Serre;
        $entity_name ="Serre";
        
        $form = $this->createForm(SerreType::class, $entity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($entity);
            $entityManager->flush();

            $this->addFlash('sucess','La nouvelle serre a bien été ajouté!');

            return $this->redirectToRoute("homepage");
        }

        return $this->render('serre/new.html.twig',[
            'form' => $form->createView(),
            'entity_name' => $entity_name,
        ]);
    }
     /**
     * @Route("/delete/{id}",name="deleteSerre", requirements={"id":"\d+"})
     */
    public function deleteSerre(Serre $entity, Request $request, EntityManagerInterface $entityManager): Response{
        

        if($this->isCsrfTokenValid("delete".$entity->getId(), $request->get('token'))){
            $entityManager->remove($entity);
            $entityManager->flush();

            //$this->addFlash('success', 'La fiche du chat a bien été supprimé!');

            return $this->redirectToRoute("homepage");
        }

        return $this->render("serre/delete.html.twig", [
            'entity' => $entity,
        ]);
    }
    
    /**
     * @Route("/edit/{id}", name="editSerre",requirements={"id":"\d+"})
     */
    public function editSerre(Serre $serre_edit, Request $request, EntityManagerInterface $entityManager): Response{
        
        

       $edit = true;
        
        $form = $this->createForm(SerreType::class, $serre_edit);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();

            //$this->addFlash('sucess','La fiche du chat a bien été modifié!');

            return $this->redirectToRoute("homepage");
        }
        return $this->render("serre/edit.html.twig", [
            'form' => $form->createView(),
            'serre_edit' => $serre_edit,
            'edit' => $edit,
        ]);
    }
    /**
     * @Route("/serveur", name="serveur")
     */
    public function serveur(): Response
    {
        

        return new Response("");
    }

}
