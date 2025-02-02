<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Mastermind;

final class MastermindController extends AbstractController {

    #[Route('/mastermind', name: 'app_mastermind')]
    public function master(Request $request, SessionInterface $session): Response
    {
        // Vérifier si une partie est en cours
        if (!$session->has('mastermind')) {
            $mastermind = new Mastermind();
            $session->set('mastermind', $mastermind);
        } else {
            $mastermind = $session->get('mastermind');
        }

        // Récupérer la proposition de l'utilisateur
        $proposition = $request->query->get('proposition', '');
        $resultat = null;

        if (!empty($proposition) && strlen($proposition) === 4 && ctype_digit($proposition)) {
            $resultat = $mastermind->test($proposition);
            $session->set('mastermind', $mastermind);
        }

        return $this->render('mastermind.html.twig', [
            'essais' => $mastermind->getEssais(),
            'fini' => $mastermind->isFini(),
            'resultat' => $resultat
        ]);
    }
}
