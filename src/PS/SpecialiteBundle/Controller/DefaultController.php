<?php

namespace PS\SpecialiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PSSpecialiteBundle:Default:index.html.twig');
    }
}
