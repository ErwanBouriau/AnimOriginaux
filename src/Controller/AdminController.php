<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use Symfony\Component\HttpFoundation\Request;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
* @Route("/admin")
*/
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public function index(AnimalRepository $animalRepository)
    {
        $animals = $animalRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'animals' => $animals
        ]);
    }

    /**
     * @Route("/nouveau", name="new")
     */
    public function new(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $animal = new Animal();

        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        // on regarde si le formualire est posté
        if ($form->isSubmitted() && $form->isValid()) {

            $animal->setName($form->get('name')->getData());
            $animal->setWeight($form->get('weight')->getData());
            $animal->setHeight($form->get('height')->getData());
            $animal->setFood($form->get('food')->getData());
            $animal->setPrice($form->get('price')->getData());
            $animal->setDisponibility(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($animal);
            $entityManager->flush();
    
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/form.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="update")
     */
    public function update($id, AnimalRepository $animalRepository, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $animal = $animalRepository->getAnimalById($id);

        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        // on regarde si le formualire est posté
        if ($form->isSubmitted() && $form->isValid()) {
            // si le champ de nom a été rempli
            if ($form->get('name')->getData()) {
                $animal->setName($form->get('name')->getData());
            }
            // si le champ de poids a été rempli
            if ($form->get('weight')->getData()) {
                $animal->setWeight($form->get('weight')->getData());
            }
            // si le champ de taille a été rempli
            if ($form->get('height')->getData()) {
                $animal->setHeight($form->get('height')->getData());
            }
            // si le champ de nourriture a été rempli
            if ($form->get('food')->getData()) {
                $animal->setFood($form->get('food')->getData());
            }
            // si le champ de prix a été rempli
            if ($form->get('price')->getData()) {
                $animal->setPrice($form->get('price')->getData());
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($animal);
            $entityManager->flush();
    
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/form.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="delete")
     */
    public function delete($id, AnimalRepository $animalRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $animal = $animalRepository->getAnimalById($id);

        if (!$animal->getDisponibility()) {
            throw new Exception("L'animal est en location !");
        } else {
            $entityManager->remove($animal);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }
    }
}
