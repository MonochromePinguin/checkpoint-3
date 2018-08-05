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

        $tileRepository->clearTreasure();

        $randomIsland = $tileRepository->getRandomIsland();
        $randomIsland->setHasTreasure(true);

        # store the coordinates in cache to avoid DB access (see implementation)
        # to be future-proof, we use an array of coordinate pairs
        $mapManager->setTreasureCoords([$randomIsland->getCoords()]);

        $boat = $this->getBoat();
        $boat->setCoords(0, 0);

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
            [$xTile, $yTile] = $tile->getCoords();
            $map[$xTile][$yTile] = $tile;
        }

        $boat = $this->getBoat();
        [$xBoat, $yBoat] = $boat->getCoords();

        $boatTile = $map[$xBoat][$yBoat];

        return $this->render('map/index.html.twig', [
            'map'  => $map ?? [],
            'boat' => $boat,
            'tileType' => $boatTile->getType(),
            'movements' => Boat::DIRECTION_LIST
        ]);
    }
}
