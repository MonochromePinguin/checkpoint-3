<?php
namespace AppBundle\Service;

use AppBundle\Entity\Boat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class MapManager
{
    private $tileRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->tileRepository = $em->getRepository('AppBundle:Tile');
    }

    public function tileExist(int $x, int $y): bool
    {
        return (
            $this->tileRepository->findOneBy([ 'coordX' => $x, 'coordY' => $y ]) != null
        );
    }


    # to avoid DB accesses, we cache treasure's coordinates into a session variable;
    # to be extension-proof, this cache store an array of coordinate pairs
    #
    #

    /**
     * @param array $coordinatePairs indexed array of [x,y] indexed arrays
     */
    public function setTreasureCoords(array $coordinatePairs): void
    {
        $session = new Session();
        // cannot call $session->start() : the session is already started by PHP's internal server

        $session->set('treasureCoordList', $coordinatePairs);
        $session->save();
    }

    public function checkTreasure(Boat $boat): bool
    {
        /*
         *** previously: ***
         $this->tileRepository->findOneBy(
            [ 'coordX' => $boatX, 'coordY' => $boatY, 'hasTreasure' => true ]
        );
        */
        $session = new Session();
        // cannot call $session->start() : the session is already started by PHP's internal server

        $coordinateList = $session->get('treasureCoordList');
        $boatCoords = $boat->getCoords();

        foreach ($coordinateList as $coordinatePair) {
            if ($coordinatePair == $boatCoords) {
                return true;
            }
        }

        return false;
    }
}
