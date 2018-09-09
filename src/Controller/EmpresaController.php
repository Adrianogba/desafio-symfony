<?php
namespace App\Controller;

use App\Entity\Empresa;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class EmpresaController extends Controller {
    /**
     * @Route("/", name="empresa_list")
     * @Method({"GET"})
     */
    public function index() {

        $empresas= $this->getDoctrine()->getRepository(Empresa::class)->findAll();

        $response = $this->get("jms_serializer")->serialize($empresas,"json");
        return new Response($response);
    }

    /**
     * @Route("/empresa/new", name="new_empresa")
     * Method({"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request) {
        $request = json_decode($request->getContent(),1);

        $empresa = new Empresa();
        $empresa->setNome($request["nome"]);
        $empresa->setTelefone($request["telefone"]);

        $manager = $this->getDoctrine()->getManager();

        #try
        $manager->persist($empresa);
        $manager->flush();

        $response = $this->get("jms_serializer")->serialize($empresa,"json");
        return new Response($response);
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

        $empresa->setNome($request["nome"]);
        $empresa->setTelefone($request["telefone"]);

        $manager = $this->getDoctrine()->getManager();

        #try
        $manager->persist($empresa);
        $manager->flush();

        $response = $this->get("jms_serializer")->serialize($empresa,"json");

        return new Response($response);
    }

    /**
     * @Route("/empresa/{id}", name="empresa_show")
     */
    public function show($id) {
        $empresa = $this->getDoctrine()->getRepository(Empresa::class)->find($id);

        $response = $this->get("jms_serializer")->serialize($empresa,"json");
        return $response;
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
