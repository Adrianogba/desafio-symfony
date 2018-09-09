<?php
namespace App\Controller;

use App\Entity\Empresa;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmpresaController extends Controller {
    /**
     * @Route("/", name="empresa_list")
     * @Method({"GET"})
     */
    public function index() {

        $empresas= $this->getDoctrine()->getRepository(Empresa::class)->findAll();

        return $this->render('empresas/index.html.twig', array('empresas' => $empresas));
    }

    /**
     * @Route("/empresa/new", name="new_empresa")
     * Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request) {
        $empresa = new Empresa();

        $form = $this->createFormBuilder($empresa)
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
            $empresa = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($empresa);
            $entityManager->flush();

            return $this->redirectToRoute('empresa_list');
        }

        return $this->render('empresas/new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/empresa/edit/{id}", name="edit_empresa")
     * Method({"GET", "POST"})
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, $id) {
        $empresa = new Empresa();
        $empresa = $this->getDoctrine()->getRepository(Empresa::class)->find($id);

        $form = $this->createFormBuilder($empresa)
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

            return $this->redirectToRoute('empresa_list');
        }

        return $this->render('empresas/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/empresa/{id}", name="empresa_show")
     */
    public function show($id) {
        $empresa = $this->getDoctrine()->getRepository(Empresa::class)->find($id);

        return $this->render('empresas/show.html.twig', array('empresa' => $empresa));
    }

    /**
     * @Route("/empresa/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id) {
        $empresa = $this->getDoctrine()->getRepository(Empresa::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($empresa);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }
}
