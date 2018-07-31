<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Boat;
use AppBundle\Service\MapManager;
use AppBundle\Traits\BoatTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Boat controller.
 *
 * @Route("boat")
 */
class BoatController extends Controller
{
    use BoatTrait;

    /**
     * @param string $direction must be one of 'N', 'E', 'S', 'W' for the 4 cardinal direction
     *               **THIS IS BAD PRACTICE (MAGICAL STRINGS?), BUT IT IS ASKED BY THE INSTRUCTIONS.**
     *               @TODO: USE Boat::DIRECTION_LIST INSTEAD
     * @param SessionInterface $session
     *
     * @Route("/move/{direction}", name="moveDirection", requirements={"direction"="N|E|S|W"})
     */
    public function moveDirectionAction(
        string $direction,
        MapManager $mapManager
    ) {
        $em = $this->getDoctrine()->getManager();
        $boat = $this->getBoat();

        $x = $boat->getCoordX();
        $y = $boat->getCoordY();

        $error = false;

        switch ($direction) {
            case 'N':
                $y--;
                break;
            case 'E':
                $x++;
                break;
            case 'S':
                $y++;
                break;
            case 'W':
                $x--;
                break;
            default:
                $error = true;
                $this->addFlash(
                    'danger',
                    'invalid "' . $direction . '" direction'
                );
        }

        if (!$mapManager->tileExist($x, $y)) {
            $error = true;
            $this->addFlash(
                'warning',
                'the place you tried to go is full of dragons! YOU CAN\'T!'
            );
        }

        if (!$error) {
            $boat->setCoord($x, $y);
            $em->flush();
        }

        if ($mapManager->checkTreasure($boat)) {
            $this->addFlash(
                'success',
                'You have found a treasure!'
            );
        }

        return $this->redirectToRoute('map');
    }

    /**
     * Move the boat to coord x,y
     * @Route("/move/{x}/{y}", name="moveBoat", requirements={"x"="\d+", "y"="\d+"})
     */
    public function moveBoatAction(int $x, int $y)
    {
        $em = $this->getDoctrine()->getManager();
        $boat = $this->getBoat();

        $boat->setCoord($x, $y);

        $em->flush();

        return $this->redirectToRoute('map');
    }
}
