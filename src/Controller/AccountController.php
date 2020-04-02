<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/compte", name="account")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();

        $form = $this->createFormBuilder()
            ->add('login', TextType::class,  ['label' => 'Identifiant', 'data' => $user->getLogin(), 'required' => false,])
            ->add('password', PasswordType::class,  ['label' => 'Mot de passe', 'required' => false,])
            ->add('money', IntegerType::class,  ['label' => 'Ajouter un montant', 'required' => false,])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
            ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // si le champ de login a été rempli
                if ($form->get('login')->getData()) {
                    $user->setLogin($form->get('login')->getData());
                }
                // si le champ de mot de passe a été rempli
                if ($form->get('password')->getData()) {
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $form->get('password')->getData()
                        )
                    );
                }
                // si l'utilisateur s'est ajouté de la monnaie
                if ($form->get('money')->getData()) {
                    $mon = $form->get('money')->getData() + $user->getMoney();
                    $user->setMoney($mon);
                }
    
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
        
                return $this->redirectToRoute('account');
            }

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'form' => $form->createView(),
        ]);
    }
}
