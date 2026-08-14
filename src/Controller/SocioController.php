<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Entity\Socio;
use App\Repository\EmpresaRepository;
use App\Repository\SocioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SocioController extends AbstractController
{
    #[Route('/socios', name: 'socio_list', methods: ['GET'])]
    public function index(SocioRepository $socioRepository): JsonResponse
    {
        $socios = $socioRepository->findAll();

        return $this->json($socios, Response::HTTP_OK);
    }

    #[Route('/socio/new', name: 'new_socio', methods: ['GET', 'POST'])]
    public function new(Request $request, EmpresaRepository $empresaRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['nome']) || !isset($data['empresa'])) {
            return $this->json(['error' => 'Dados inválidos. Os campos "nome" e "empresa" (ID) são obrigatórios.'], Response::HTTP_BAD_REQUEST);
        }

        $empresa = $empresaRepository->find($data['empresa']);

        if (!$empresa) {
            return $this->json(['error' => 'Empresa vinculada não encontrada.'], Response::HTTP_NOT_FOUND);
        }

        $socio = new Socio();
        $socio->setNome($data['nome']);
        $socio->setTelefone($data['telefone'] ?? null);
        $socio->setEmpresa($empresa);

        $entityManager->persist($socio);
        $entityManager->flush();

        return $this->json($socio, Response::HTTP_CREATED);
    }

    #[Route('/socio/edit/{id}', name: 'edit_socio', methods: ['GET', 'POST', 'PUT'])]
    public function edit(int $id, Request $request, SocioRepository $socioRepository, EmpresaRepository $empresaRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $socio = $socioRepository->find($id);

        if (!$socio) {
            return $this->json(['error' => 'Sócio não encontrado.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if ($data) {
            if (isset($data['nome'])) {
                $socio->setNome($data['nome']);
            }
            if (isset($data['telefone'])) {
                $socio->setTelefone($data['telefone']);
            }
            if (isset($data['empresa'])) {
                $empresa = $empresaRepository->find($data['empresa']);
                if ($empresa) {
                    $socio->setEmpresa($empresa);
                }
            }

            $entityManager->flush();
        }

        return $this->json($socio, Response::HTTP_OK);
    }

    #[Route('/socio/{id}', name: 'socio_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[Route('/socio/show/{id}', name: 'socio_show_alias', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id, SocioRepository $socioRepository): JsonResponse
    {
        $socio = $socioRepository->find($id);

        if (!$socio) {
            return $this->json(['error' => 'Sócio não encontrado.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($socio, Response::HTTP_OK);
    }

    #[Route('/socio/delete/{id}', name: 'socio_delete', methods: ['DELETE', 'POST', 'GET'], requirements: ['id' => '\d+'])]
    #[Route('/socio/{id}', name: 'socio_delete_rest', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id, SocioRepository $socioRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $socio = $socioRepository->find($id);

        if (!$socio) {
            return $this->json(['error' => 'Sócio não encontrado.'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($socio);
        $entityManager->flush();

        return $this->json(['message' => 'Sócio removido com sucesso.', 'id' => $id], Response::HTTP_OK);
    }
}
