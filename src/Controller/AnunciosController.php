<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Description of AnunciosController
 *
 * @author DAW209
 */
class AnunciosController extends AbstractController {
    /**
     * @Route("/", name="inicio")
     */
    public function inicio() {
               
        return $this->render('anuncios/inicio.twig');
    }
}
