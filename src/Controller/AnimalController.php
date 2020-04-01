<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    /**
     * @Route("/animaux", name="animal")
     */
    public function index(AnimalRepository $animalRepository)
    {
        $animals = $animalRepository->getAnimals();

        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
            'animals' => $animals
        ]);
    }

    /**
     * @Route("/animaux/{id}", name="animalInfo")
     */
    public function pageInfo($id, AnimalRepository $animalRepository)
    {
        $animal = $animalRepository->getAnimalById($id);

        return $this->render('animal/animal.html.twig', [
            'controller_name' => 'AnimalController',
            'animal' => $animal[0]
        ]);
    }
}
