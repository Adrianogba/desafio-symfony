<?php
namespace App\Controller;

use App\Entity\Socio;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SocioController extends Controller {
    /**
     * @Route("/socios", name="socio_list")
     * @Method({"GET"})
     */
    public function index() {

        $socios= $this->getDoctrine()->getRepository(Socio::class)->findAll();

        return $this->render('socios/index.html.twig', array('socios' => $socios));
    }

    /**
     * @Route("/socio/new", name="new_socio")
     * Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request) {
        $socio = new Socio();

        $form = $this->createFormBuilder($socio)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('body', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $socio = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($socio);
            $entityManager->flush();

            return $this->redirectToRoute('socio_list');
        }

        return $this->render('socios/new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/socio/edit/{id}", name="edit_socio")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id) {
        $socio = new Socio();
        $socio = $this->getDoctrine()->getRepository(Socio::class)->find($id);

        $form = $this->createFormBuilder($socio)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('body', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('socio_list');
        }

        return $this->render('socios/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/socio/{id}", name="socio_show")
     */
    public function show($id) {
        $socio = $this->getDoctrine()->getRepository(Socio::class)->find($id);

        return $this->render('socios/show.html.twig', array('socio' => $socio));
    }

    /**
     * @Route("/socio/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id) {
        $socio = $this->getDoctrine()->getRepository(Socio::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($socio);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }
}
