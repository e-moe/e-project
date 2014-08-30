<?php

namespace Levi9\EProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Levi9EProjectBundle:Default:index.html.twig', array('name' => $name));
    }
}
