<?php

namespace PS\ParametreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ParametreBundle:Default:index.html.twig');
    }
}
