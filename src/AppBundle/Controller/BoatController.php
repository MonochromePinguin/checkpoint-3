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
 *               **ISN'T IT BAD PRACTICE (MAGICAL STRINGS?)? BUT IT IS ASKED BY THE INSTRUCTIONS.**
 *               @TODO: IS THERE A WAY TO USE Boat::DIRECTION_LIST INTO ANNOTATIONS INSTEAD?
     *
     * @Route("/move/{direction}", name="moveDirection", requirements={"direction"="N|E|S|W"})
     */
    public function moveDirectionAction(
        string $direction,
        MapManager $mapManager
    ) {
        $em = $this->getDoctrine()->getManager();
        $boat = $this->getBoat();

        $boatCoord = $boat->getCoord();

        $error = false;

        #The direction and the associated instructions to move the boat
        # are stored in an array, so the controller is now independant of the
        # available movements ...
        # **BUT THE ANNOTATIONS ARE STILL HARDCODED HERE**
        //TODO: this work, but it is surely less performant than a good
        // old plain case{} block!!!-
        if (array_key_exists($direction, Boat::DIRECTION_LIST)) {
            #The future boat coordinates are updated there
            (Boat::DIRECTION_FUNCTIONS[$direction])($boatCoord);
        } else {
            $error = true;
            $this->addFlash(
                'danger',
                'invalid "' . $direction . '" direction'
            );
        }

        # $boatCoord is spread into two parameters $x and $y...
        if (!$mapManager->tileExist(...$boatCoord)) {
            $error = true;
            $this->addFlash(
                'warning',
                'the place you tried to go is full of dragons! YOU CAN\'T!'
            );
        }

        if (!$error) {
            $boat->setCoord(...$boatCoord);
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
