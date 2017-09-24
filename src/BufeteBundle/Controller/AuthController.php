<?php

namespace BufeteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auth controller.
 *
 */
class AuthController extends Controller
{
  public function loginAction(Request $request){
      $authenticationUtils = $this->get("security.authentication_utils");
      $error = $authenticationUtils->getLastAuthenticationError();
      $lastUsername = $authenticationUtils->getLastUsername();

      return $this->render("auth/login.html.twig", array(
          "error"=> $error,
          "last_username" => $lastUsername,
      ));
  }
}
