<?php
namespace AppBundle\Service;

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
}
