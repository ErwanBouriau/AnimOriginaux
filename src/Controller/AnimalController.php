<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use App\Repository\LocationRepository;
use App\Entity\Location;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function pageInfo($id, AnimalRepository $animalRepository, LocationRepository $locationRepository, Request $request)
    {
        $animal = $animalRepository->getAnimalById($id);
        $location = $locationRepository->getLocationByAnimal($animal);
        $entityManager = $this->getDoctrine()->getManager();

        $action = $request->get('location');

        if ($action == 'loue') {
            $locationTemp = new Location();
            $user = $this->getUser();

            // on crée la nouvelle location
            $locationTemp->setUser($this->getUser());
            $locationTemp->setAnimal($animal);

            // on retire le prix de l'animal au portefeuille de l'utilisateur
            if ($user->getMoney() - $animal->getPrice() < 0) {
                throw new Exception('Vous n\'avez pas assez d\'argent');
            } else {
                $user->setMoney($user->getMoney() - $animal->getPrice());
            }

            // on change la disponibilité de l'animal
            $animal->setDisponibility(false);

            $entityManager->persist($user);
            $entityManager->persist($animal);
            $entityManager->persist($locationTemp);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
        if ($action == 'rend') {
            $animal->setDisponibility(true);
            $entityManager->persist($animal);
            $entityManager->remove($location);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('animal/animal.html.twig', [
            'controller_name' => 'AnimalController',
            'animal' => $animal,
            'location' => $location
        ]);
    }
}
