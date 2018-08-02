<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Boat
 *
 * @ORM\Table(name="boat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BoatRepository")
 */
class Boat
{
    # available directions for boat movements
    public const DIRECTION_LIST = [
        'N' => 'North',
        'E' => 'East',
        'S' => 'South',
        'W' => 'West'
    ];

    #movement functions related to directions,
    # operating on a by-reference indexed array [X, Y].
    public const DIRECTION_FUNCTIONS = [
        'N' => [self::class, 'decY'],
        'E' => [self::class, 'incX'],
        'S' => [self::class, 'incY'],
        'W' => [self::class, 'decX']
    ];

    #### these functions operate on a by-reference indexed array [X, Y] storing
    # coordinates, are are only useful to uncouple
    # the movement mechanism from the controller
    #
    public static function incX(array &$coord): void
    {
        ++$coord[0];
    }

    public static function decX(array &$coord): void
    {
        --$coord[0];
    }

    public static function incY(array &$coord): void
    {
        ++$coord[1];
    }

    public static function decY(array &$coord): void
    {
        --$coord[1];
    }
    #
    ####


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="coordX", type="integer")
     */
    private $coordX;

    /**
     * @var int
     *
     * @ORM\Column(name="coordY", type="integer")
     */
    private $coordY;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Boat
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set coords
     *
     * @param integer $coordX
     * @param integer $coordY
     *
     * @return Boat
     */
    public function setCoord(int $coordX, int $coordY)
    {
        $this->coordX = $coordX;
        $this->coordY = $coordY;

        return $this;
    }

    /**
     * get coords
     *
     * @return array
     */
    public function getCoord(): array
    {
        return [$this->coordX, $this->coordY];
    }

    /**
     * Get coordX
     *
     * @return int
     */
    public function getCoordX(): int
    {
        return $this->coordX;
    }

    /**
     * Set coordX
     *
     * @param integer $coordX
     *
     * @return Boat
     */
    public function setCoordX(int $coordX)
    {
        $this->coordX = $coordX;

        return $this;
    }

    /**
     * Get coordY
     *
     * @return int
     */
    public function getCoordY(): int
    {
        return $this->coordY;
    }

    /**
     * Set coordY
     *
     * @param integer $coordY
     *
     * @return Boat
     */
    public function setCoordY(int $coordY)
    {
        $this->coordY = $coordY;

        return $this;
    }
}
