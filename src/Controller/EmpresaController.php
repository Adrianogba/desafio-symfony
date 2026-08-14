<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Repository\EmpresaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmpresaController extends AbstractController
{
    #[Route('/', name: 'empresa_list', methods: ['GET'])]
    public function index(EmpresaRepository $empresaRepository): JsonResponse
    {
        $empresas = $empresaRepository->findAll();

        return $this->json($empresas, Response::HTTP_OK);
    }

    #[Route('/empresa/new', name: 'new_empresa', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['nome'])) {
            return $this->json(['error' => 'Dados inválidos. O campo "nome" é obrigatório.'], Response::HTTP_BAD_REQUEST);
        }

        $empresa = new Empresa();
        $empresa->setNome($data['nome']);
        $empresa->setTelefone($data['telefone'] ?? null);

        $entityManager->persist($empresa);
        $entityManager->flush();

        return $this->json($empresa, Response::HTTP_CREATED);
    }

    #[Route('/empresa/edit/{id}', name: 'edit_empresa', methods: ['GET', 'POST', 'PUT'])]
    public function edit(int $id, Request $request, EmpresaRepository $empresaRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $empresa = $empresaRepository->find($id);

        if (!$empresa) {
            return $this->json(['error' => 'Empresa não encontrada.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if ($data) {
            if (isset($data['nome'])) {
                $empresa->setNome($data['nome']);
            }
            if (isset($data['telefone'])) {
                $empresa->setTelefone($data['telefone']);
            }

            $entityManager->flush();
        }

        return $this->json($empresa, Response::HTTP_OK);
    }

    #[Route('/empresa/{id}', name: 'empresa_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[Route('/empresa/show/{id}', name: 'empresa_show_alias', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id, EmpresaRepository $empresaRepository): JsonResponse
    {
        $empresa = $empresaRepository->find($id);

        if (!$empresa) {
            return $this->json(['error' => 'Empresa não encontrada.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($empresa, Response::HTTP_OK);
    }

    #[Route('/empresa/delete/{id}', name: 'empresa_delete', methods: ['DELETE', 'POST', 'GET'], requirements: ['id' => '\d+'])]
    #[Route('/empresa/{id}', name: 'empresa_delete_rest', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id, EmpresaRepository $empresaRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $empresa = $empresaRepository->find($id);

        if (!$empresa) {
            return $this->json(['error' => 'Empresa não encontrada.'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($empresa);
        $entityManager->flush();

        return $this->json(['message' => 'Empresa removida com sucesso.', 'id' => $id], Response::HTTP_OK);
    }
}
