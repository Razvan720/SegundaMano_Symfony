<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Usuarios;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Description of UsuariosController
 *
 * @author DAW209
 */
class UsuariosController extends AbstractController {

    /**
     * @Route("/usuarios/registrar", name="registrar")
     */
    public function registrar(Request $request, UserPasswordEncoderInterface $encoder) {

        $usuario = new Usuarios();
        
        //Creamos el formulario para poder mostrarlo en el html
        $form = $this->createFormBuilder($usuario, ['attr' => ['id' => 'registro_form']])
                ->add('email', EmailType::class, ['attr' => ['class' => 'texto_form']])
                ->add('password', PasswordType::class, ['attr' => ['class' => 'texto_form']])
                ->add('nombre', TextType::class, ['attr' => ['class' => 'texto_form']])
                ->add('apellido', TextType::class, ['attr' => ['class' => 'texto_form']])
                ->add('provincia', TextType::class, ['attr' => ['class' => 'texto_form']])
                ->add('telefono', TextType::class, ['attr' => ['class' => 'texto_form']])
                ->add('foto', FileType::class, [
                    'attr' => ['class' => 'input_form'],
                    'required' => false
                ])
                ->add('Registrarse', SubmitType::class, ['attr' => ['class' => 'boton_form']])
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $usuario = $form->getData();
            //Asignamos un rol temporal
            $usuario->setRoles('ROLE_USER');
            //Codificamos el password
            $password_hash = $encoder->encodePassword($usuario, $usuario->getPassword());
            $usuario->setPassword($password_hash);
            //Guarda el archivo de la foto
            $foto = $form->get('foto')->getData();
            if ($foto) {
                $extension = pathinfo($foto->getClientOriginalName(), PATHINFO_EXTENSION);
                $nuevo_nombre_archivo = md5(time() + rand(0, 9999)) . "." . $extension;
                $foto->move("imagenes", "$nuevo_nombre_archivo");
                $usuario->setFoto($nuevo_nombre_archivo);
            }

            if (!$this->getDoctrine()->getRepository(Usuarios::class)
                            ->findOneBy(['email' => $usuario->getEmail()])) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($usuario);
                $entityManager->flush();
                $this->addFlash('mensaje', 'Usuario creado');
                return $this->redirectToRoute('inicio');
            } else {
                $this->addFlash('mensaje', 'El email ya existe');
            }
        }

        return $this->render('security/registro.twig', ['formulario_registro' => $form->createView()]);
    }

}
