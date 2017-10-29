<?php

namespace BufeteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Bufete controller.
 *
 */
class ErroresController extends Controller
{
    /**
     * Error pagina no encontrada
     *
     */
    public function notfoundAction()
    {
        return $this->render('errores/notfound.html.twig');
    }

}
