<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EntrepriseType;
use App\Form\StageType;
use App\Entity\Stage;
use App\Entity\Entreprise;
use App\Entity\Formation;
use App\Repository\FormationRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\StageRepository;

class ProStagesController extends AbstractController
{
    /**
     * @Route("/entreprises/new", name="entreprises_new")
     */
    public function AddNewEntreprise(Request $request)
    {
        $entreprise = new Entreprise();

        $form =$this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->persist($entreprise);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("home");

        }

        return $this->render('entreprises/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/stage/new", name="stage_new")
     */
    public function addStage(Request $request) {
        $stage = new Stage();

        $form =$this->createForm(StageType::class, $stage);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->persist($stage);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("home");

        }

        return $this->render('stages/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/entreprises/{id}/edit", name="entreprises_edit")
     */
    public function EditEntreprise(Request $request, Entreprise $entreprise)
    {

        $form =$this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $this->getDoctrine()->getManager()->persist($entreprise);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("entreprises");

        }

        return $this->render('entreprises/edit.html.twig', [
            "form" => $form->createView()
        ]);
    }
  /**
   * @Route("/", name="home")
   */
  public function HomeIndex(StageRepository $repo)
  {
    $stages = $repo->findAllWithNomEntreprise();

    return $this->render('home/index.html.twig', [
      "stages" => $stages
    ]);
  }

  /**
   * @Route("/tri/{type}/{nom}", name="sort")
   */
  public function SortedHomeIndex($type, $nom, FormationRepository $repoF, StageRepository $repoS)
  {

    if ($type=="entreprises") {
      $stages = $repoS->findByEntrepriseDonnée($nom);
    }
    elseif ($type=="formation") {
      $stages = $repoS->findByFormationDonnée($nom);
    }

    return $this->render('home/index.html.twig', [
      "stages" => $stages,
      "type" => $type,
    ]);

  }

  /**
   * @Route("/entreprises", name="entreprises")
   */
  public function EntreprisesIndex(EntrepriseRepository $repo)
  {
    $entreprises = $repo->findAll();

      return $this->render('entreprises/index.html.twig', [
        "entreprises" => $entreprises
      ]);
  }

  /**
   * @Route("/stages/{id}", name="stages")
   */
  public function StagesIndex(Stage $stage)
  {
      return $this->render('stages/index.html.twig', [
          'stage' => $stage
      ]);
  }

  /**
   * @Route("/choixTtri/{type}", name="choixTri")
   */
  public function TriStagesIndex($type, FormationRepository $repoF, EntrepriseRepository $repoE)
  {
    if ($type=="entreprises") {
      $resultat = $repoE->findAll();
    }
    elseif ($type=="formations") {
      $resultat = $repoF->findAll();
    }
    return $this->render('tri/index.html.twig', [
      'type' => $type,
      'tableau' => $resultat
    ]);
  }
}


