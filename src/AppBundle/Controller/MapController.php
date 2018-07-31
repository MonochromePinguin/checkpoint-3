<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Boat;
use AppBundle\Entity\Tile;
use AppBundle\Service\MapManager;
use AppBundle\Traits\BoatTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MapController extends Controller
{
    use BoatTrait;

    /**
     * start a new game: clear boat and treasure position
     *
     * @Route("/start", name="start")
     */
    public function startAction(MapManager $mapManager)
    {
        $em = $this->getDoctrine()->getManager();
        $tileRepository = $em->getRepository(Tile::class);

        $oldTreasureIsland = $tileRepository->findOneBy(
            ['hasTreasure' => true]
        );
        $oldTreasureIsland->setHasTreasure(false);

        $randomIsland = $mapManager->getRandomIsland();
        $randomIsland->setHasTreasure(true);

        $boat = $this->getBoat();
        $boat->setCoordX(0);
        $boat->setCoordY(0);

        $em->flush();

        return $this->redirectToRoute('map');
    }

    /**
     * @Route("/map", name="map")
     */
    public function displayMapAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tiles = $em->getRepository(Tile::class)->findAll();

        foreach ($tiles as $tile) {
            $map[$tile->getCoordX()][$tile->getCoordY()] = $tile;
        }

        $boat = $this->getBoat();

        $boatTile = $map[$boat->getCoordX()][$boat->getCoordY()];

        return $this->render('map/index.html.twig', [
            'map'  => $map ?? [],
            'boat' => $boat,
            'tileType' => $boatTile->getType(),
            'movements' => Boat::DIRECTION_LIST
        ]);
    }
}
