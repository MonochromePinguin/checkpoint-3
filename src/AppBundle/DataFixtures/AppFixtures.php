<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 24/05/18
 * Time: 17:48
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Tile;
use AppBundle\Entity\Boat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tiles = [
            ['sea', 'sea', 'sea', 'sea', 'sea', 'island', 'sea', 'sea', 'sea', 'port', 'sea', 'sea'],
            ['sea', 'port', 'sea', 'island', 'sea', 'sea', 'sea', 'sea', 'sea', 'sea', 'island', 'sea'],
            ['sea', 'sea', 'sea', 'sea', 'sea', 'sea', 'sea', 'sea', 'island', 'sea', 'sea', 'sea'],
            ['sea', 'island', 'sea', 'sea', 'island', 'sea', 'sea', 'sea', 'sea', 'sea', 'sea', 'sea'],
            ['sea', 'sea', 'sea', 'sea', 'sea', 'sea', 'sea', 'island', 'sea', 'sea', 'port', 'sea'],
            ['island', 'sea', 'sea', 'sea', 'port', 'sea', 'sea', 'sea', 'sea', 'sea', 'sea', 'island'],
        ];

        // create 20 products! Bam!
        $types = ['sea', 'sea', 'sea', 'sea', 'island', 'island', 'port'];

        foreach ($tiles as $y => $line) {
            foreach ($line as $x => $type) {
                $tile = new Tile();
                $tile->setType($type);
                $tile->setCoordX($x);
                $tile->setCoordY($y);
                $tile->setHasTreasure(false);
                $manager->persist($tile);
            }
        }

        $boat = new Boat();
        $boat->setCoords(0, 0);
        $boat->setName('Black Pearl');
        $manager->persist($boat);

        $manager->flush();
    }
}
