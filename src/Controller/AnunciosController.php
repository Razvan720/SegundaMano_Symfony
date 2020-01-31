<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \App\Entity\Anuncios;

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
        $anuncios_inicio = $this->getDoctrine()->getRepository(Anuncios::class)->findAll(['fecha_creacion' => 'DESC']);
        return $this->render('anuncios/inicio.twig', ['anuncios' => $anuncios_inicio]);
    }

    /**
     * @Route("/anuncios/{id}", name="verAnuncio", requirements={"id"="\d+"})
     */
    public function verAnuncio(Anuncios $anuncio) {
        return $this->render('anuncios/verAnuncio.twig', ['anuncio' => $anuncio]);
    }

}
