<?php

namespace App\Controller;

use App\Entity\Usuarios;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use \App\Entity\Anuncios;
use \App\Entity\Fotos;

/**
 * Description of AnunciosController
 *
 * @author DAW209
 */
class AnunciosController extends AbstractController
{

    /**
     * @Route("/", name="inicio")
     */
    public function inicio()
    {
        $anuncios_inicio = $this->getDoctrine()->getRepository(Anuncios::class)->findAll(['fecha_creacion' => 'DESC']);
        return $this->render('anuncios/inicio.twig', ['anuncios' => $anuncios_inicio]);
    }

    /**
     * @Route("/anuncios/{id}", name="verAnuncio", requirements={"id"="\d+"})
     */
    public function verAnuncio(Anuncios $anuncio)
    {
        return $this->render('anuncios/verAnuncio.twig', ['anuncio' => $anuncio]);
    }

    /**
     * @Route("/misanuncios/{id}", name="misAnuncios", requirements={"id"="\d+"})
     */
    public function misAnuncios(Usuarios $usuario)
    {

        $misAnuncios = $this->getDoctrine()->getRepository(Anuncios::class)->findAll(['usuario_id' => $usuario->getId()]);
        return $this->render('anuncios/misAnuncios.twig', ['anuncios' => $misAnuncios]);
    }

    /**
     * @Route("/add", name="addAnuncio")
     */
    public function addAnuncio(Request $request)
    {
        $anuncio = new Anuncios();
        $form = $this->createFormBuilder($anuncio, ['attr' => ['id' => 'insertar_anuncio_form']])
            ->add('titulo', TextType::class, ['attr' => ['class' => 'texto_form']])
            ->add('descripcion', TextType::class, ['attr' => ['class' => 'texto_form']])
            ->add('precio', MoneyType::class, ['attr' => ['class' => 'texto_form']])
            ->add('foto', FileType::class, ['attr' => ['class' => 'input_form'], 'mapped' => false])
            ->add('Insertar', SubmitType::class, ['attr' => ['class' => 'boton_form']])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $anuncio = $form->getData();
            $anuncio->setUsuario($this->getUser());

            //Guarda el archivo de la foto
            $foto = new Fotos();
            $fotoform = $form->get('foto')->getData();

            if ($fotoform) {
                //Generamos un nuevo nombre para la foto
                $extensión = pathinfo($fotoform->getClientOriginalName(), PATHINFO_EXTENSION);
                $nuevo_nombre_archivo = md5(time() + rand(0, 9999)) . "." . $extensión;
                $fotoform->move("imagenes/anuncios", "$nuevo_nombre_archivo");

                $foto->setNombre($nuevo_nombre_archivo);
                $foto->setPrincipal(false);

                $anuncio->addFoto($foto);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($anuncio);
            $entityManager->flush();
            $this->addFlash('mensaje', 'Anuncio creado');
            return $this->redirectToRoute('inicio');
        }
        return $this->render('anuncios/insertar.twig', ['formulario_add' => $form->createView()]);
    }

}
