<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(LocationRepository $locationRepository, AnimalRepository $animalRepository)
    {
        $locations = $locationRepository->getLocationsByUser($this->getUser());
        $animals = array();

        foreach ($locations as $key => $value) {
            $animals[] = $animalRepository->getAnimalById($value->getAnimal()->getId());
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'animals' => $animals
        ]);
    }
}
