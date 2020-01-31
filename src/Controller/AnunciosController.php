<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \App\Entity\Anuncios;
use App\Entity\Usuarios;
use App\Entity\Fotos;

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

    /**
     * @Route("/misanuncios/{id}", name="misAnuncios", requirements={"id"="\d+"})
     */
    public function misAnuncios(Usuarios $usuario) {

        $misAnuncios = $this->getDoctrine()->getRepository(Anuncios::class)->findAll(['usuario_id' => $usuario->getId()]);
        return $this->render('anuncios/misAnuncios.twig', ['anuncios' => $misAnuncios]);
    }

    /**
     * @Route("/add", name="addAnuncio", requirements={"id"="\d+"})
     */
    public function addAnuncio(Usuarios $usuario) {
        
    }

}
