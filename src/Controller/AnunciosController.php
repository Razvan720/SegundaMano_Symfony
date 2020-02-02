<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \App\Entity\Anuncios;
use App\Entity\Usuarios;
use App\Entity\Fotos;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
    public function addAnuncio(Request $request) {
        $anuncio = new Anuncios();
        $form = $this->createFormBuilder($anuncio, ['attr' => ['id' => 'insertar_anuncio_form']])
        ->add('titulo', TextType::class, ['attr' => ['class' => 'texto_form']])
        ->add('descripcion', TextType::class, ['attr' => ['class' => 'texto_form']])
        ->add('precio', MoneyType::class, ['attr' => ['class' => 'texto_form']])
        ->add('foto', FileType::class, ['attr' => ['class' => 'input_form']])
        /*->add('usuario_id', EntityType::class,
        ])*/
        ->add('Insertar', SubmitType::class, ['attr' => ['class' => 'boton_form']])
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $anuncio = $form->getData();

            //Guarda el archivo de la foto
            $foto = $form->get('foto')->getData();
            if ($foto) {
                $extensión = pathinfo($foto->getClientOriginalName(), PATHINFO_EXTENSION);
                $nuevo_nombre_archivo = md5(time() + rand(0, 9999)) . "." . $extensión;
                $foto->move("imagenes", "$nuevo_nombre_archivo");
                $producto->setFoto($nuevo_nombre_archivo);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($anuncio);
            $entityManager->flush();
            $this->addFlash('mensaje', 'Anuncio creado');
            return $this->redirectToRoute('inicio');
        }
        return $this->render('anuncios/insertar.html.twig', ['formulario' => $form->createView()]);
    }

}
