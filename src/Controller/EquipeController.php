<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Personne;
use App\Form\EquipeType;
use App\Form\PersonneType;
use App\Repository\EquipeRepository;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EquipeController extends AbstractController
{
    /**
     * @Route("/", name="equipe")
     */
    public function index(Request $request, EquipeRepository $eqRepo, PersonneRepository $perRepo): Response
    {
        
        $equipes = $eqRepo->findAll();
        $equipe = new Equipe();
        
        $formEquipe = $this->createForm(EquipeType::class,$equipe);
        $formEquipe->handleRequest($request);
        
        // si le form est validé.
        if ($formEquipe->isSubmitted()){
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($equipe);
            $em->flush();
            
            return $this->redirectToRoute('equipe');
        }

        $personne = new Personne();
        
        $formPersonne = $this->createForm(PersonneType::class,$personne);
        $formPersonne->handleRequest($request);

        // si le form est validé.
        if ($formPersonne->isSubmitted()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($personne);
            $em->flush();

            return $this->redirectToRoute('equipe');
        }

        return $this->render('equipe/index.html.twig',[
            'formEquipe' => $formEquipe->createView(),
            'equipes' => $equipes,
            'formPersonne' => $formPersonne->createView(),
        ]);
    }

    /**
     * @Route("/deleteEquipe/{id}", name="deleteEquipe")
     */
    public function deleteEquipe(Equipe $equipe, EntityManagerInterface $em): Response
    {
        $em->remove($equipe);
        $em->flush();
        return $this->redirectToRoute('equipe');
    }

    /**
     * @Route("/deletePersonne/{id}", name="deletePersonne")
     */
    public function deletePersonne(Personne $personne, EntityManagerInterface $em): Response
    {
        $em->remove($personne);
        $em->flush();
        return $this->redirectToRoute('equipe');
    }
}
