<?php
namespace App\Controller;

use App\Entity\Empresa;
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
        $request = json_decode($request->getContent(),1);

        $socio = new Socio();
        $socio->setNome($request["nome"]);
        $socio->setTelefone($request["telefone"]);


        $empresa = $this->getDoctrine()->getRepository(Empresa::class)->find($request["empresa"]);

        $socio->setEmpresa($empresa);

        $manager = $this->getDoctrine()->getManager();

        #try
        $manager->persist($socio);
        $manager->flush();

        $response = $this->get("jms_serializer")->serialize($socio,"json");
        return new Response($response);
    }

    /**
     * @Route("/socio/edit/{id}", name="edit_socio")
     * Method({"GET", "POST"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function edit(Request $request, $id) {
        $socio = new Socio();
        $socio = $this->getDoctrine()->getRepository(Socio::class)->find($id);

        $socio->setNome($request["nome"]);
        $socio->setTelefone($request["telefone"]);

        $empresa = $this->getDoctrine()->getRepository(Empresa::class)->find($request["empresa"]);

        $socio->setEmpresa($empresa);

        $manager = $this->getDoctrine()->getManager();

        #try
        $manager->persist($socio);
        $manager->flush();

        $response = $this->get("jms_serializer")->serialize($socio,"json");

        return new Response($response);
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

        $response = $this->get("jms_serializer")->serialize($socio,"json");

        return new Response($response);
    }
}
