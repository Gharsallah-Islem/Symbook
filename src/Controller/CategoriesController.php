<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request; // Corrected namespace for Request
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoriesController extends AbstractController
{
    #[Route('/admin/categories', name: 'admin_categories')]
    public function index(CategoriesRepository $rep): Response
    {
        $categories = $rep->findAll();
        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/categories/create', name: 'admin_categories_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $categories = new Categories();
        $form = $this->createForm(CategoriesType::class, $categories);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categories);
            $em->flush();
            $this->addFlash('success', 'Category created successfully');
            return $this->redirectToRoute('admin_categories');
        }
        return $this->render('categories/create.html.twig', [
            'f' => $form->createView(),
            'categories' => $categories,
        ]);
    }
    #[Route('/admin/categories/edit/{id}', name: 'admin_categories_edit')]
    public function edit(Request $request, EntityManagerInterface $em, CategoriesRepository $rep, $id): Response
    {
        $categories = $rep->find($id);
        if (!$categories) {
            throw $this->createNotFoundException('Category not found');
        }
        $form = $this->createForm(CategoriesType::class, $categories);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Category updated successfully');
            return $this->redirectToRoute('admin_categories');
        }
        return $this->render('categories/edit.html.twig', [
            'f' => $form->createView(),
            'categories' => $categories,
        ]);
    }
}