<?php
namespace AppBundle\Service;

use AppBundle\Entity\Boat;
use AppBundle\Entity\Tile;
use AppBundle\Repository\TileRepository;
use Doctrine\ORM\EntityManagerInterface;

class MapManager
{
    private $tileRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->tileRepository = $em->getRepository('AppBundle:Tile');
    }

    public function tileExist(int $x, int $y): bool
    {
        return ( $this->tileRepository->findOneBy([ 'coordX' => $x, 'coordY' => $y ]) != null );
    }

    public function checkTreasure(Boat $boat): bool
    {
        $boatX = $boat->getCoordX();
        $boatY = $boat->getCoordY();

        $boatTileWithTreasure = $this->tileRepository->findOneBy(
            [ 'coordX' => $boatX, 'coordY' => $boatY, 'hasTreasure' => true ]
        );

        return ( null != $boatTileWithTreasure );
    }
}
