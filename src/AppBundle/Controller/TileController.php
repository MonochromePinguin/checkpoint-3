<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tile controller.
 *
 * @Route("tile")
 */
class TileController extends Controller
{
    /**
     * Lists all tile entities.
     *
     * @Route("/", name="tile_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tiles = $em->getRepository('AppBundle:Tile')->findAll();

        return $this->render('tile/index.html.twig', array(
            'tiles' => $tiles,
        ));
    }
}
