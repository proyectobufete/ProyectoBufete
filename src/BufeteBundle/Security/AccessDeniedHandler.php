<?php
namespace BufeteBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        //return $this->redirectToRoute('errores_notfound');
        //return $this->render('errores/notfound.html.twig');
        $content = '
        <htm>
          <div class="container">
            <div class="box">
              <h1>404</h1>
              <h2>Pagina no encontrada</h2>
              <hr />
              <a href="/home">Regresar</a>
            </div>
          </div>
        </htm>

        <style>
        body{
          background-color: #E6E6E6;
          font-family: Helvetica, Arial, sans-serif;
          font-size: 10pt;
          padding-top: 50px;
          text-align: left;
        }
        a {
          color: #666;
          text-decoration: none;
        }
        a:hover {
          text-decoration: underline;
        }
        .container {
          margin: auto;
          max-width: 540px;
          min-width: 200px;
        }
        .box hr {
          diplay: block;
          border: none;
          border-bottom: 1px dashed #ccc;
        }
        .box {
          background-color: #fbfbfb;
          border: 1px solid #AAA;
          border-bottom: 1px solid #888;
          border-radius: 3px;
          color: black;
          box-shadow: 0px 2px 2px #AAA;
          padding: 20px;
        }
        .box h1, .box h2 {
          display: block;
          text-align: center;
        }
        .box h1 {
          color: #666;
          font-weight: normal;
          font-size: 50px;
          padding: 0;
          margin: 0;
          margin-top: 10px;
          line-height:50px
        }
        .box h2 {
          color: #666;
          font-weight: normal;
          font-size: 1.5em;
        }
        </style>
         ';
        return new Response($content, 403);
    }
}
