<?php

namespace Ctg\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/a")
     */
    public function indexAction()
    {
        return $this->render('CtgMainBundle:Default:index.html.twig');
    }
}
