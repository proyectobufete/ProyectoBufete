<?php

namespace BufeteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BufeteBundle:Default:index.html.twig');
    }
}
