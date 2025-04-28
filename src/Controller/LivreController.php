<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class LivreController extends AbstractController{
    #[Route('/admin/livre/create', name: 'app_livre-create')]
    public function create(Request $request, EntityManagerInterface $em): Response{
        $livre = new Livre();
        $form = $this->createForm(LivresType::class, $livre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($livre);
            $em->flush();
            $this->addFlash('success', 'Livre créé avec succès');
            return $this->redirectToRoute('app_livre-findall');
        }
        return $this->render('livre/create.html.twig', [
            'f' => $form->createView(),
            'livre' => $livre,
        ]);
    }


    #[Route('/admin/livre/show/{id}', name: 'app_livre-show')]
    public function show(LivreRepository $lr, $id): Response
    {
        $livre = $lr->find($id);
        if (!$livre) {
            throw $this->createNotFoundException('Le livre n\'existe pas');
        }
        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/livre/show2', name: 'app_livre-show2')]
    public function show2(LivreRepository $lr): Response
    {
        $livre = $lr->findBy(['titre' => 'Le seigneur des anneaux'], ['Prix' => 'ASC']);
        if (!$livre) {
            throw $this->createNotFoundException('Le livre n\'existe pas');
        }
        dd($livre);
    }

    #[Route('/admin/livre/findall', name: 'app_livre-findall')]
    public function findall(LivreRepository $lr, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $lr->createQueryBuilder('l')
            ->getQuery();

        $livres = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );

        return $this->render('livre/all.html.twig', [
            'livres' => $livres,
        ]);
    }

    #[Route('/admin/livre/update/{id}', name: 'app_livre-update')]
    public function update(LivreRepository $lr, EntityManagerInterface $em, $id): Response
    {
        $livre = $lr->find($id);
        if (!$livre) {
            throw $this->createNotFoundException('Le livre n\'existe pas');
        }
        $newPrix = $livre->getPrix() + $livre->getPrix() * 0.1;
        $livre->setPrix($newPrix);
        $em->flush();
        dd($livre);
        return new Response('Livre modifié');
    }

    #[Route('/admin/livre/delete/{id}', name: 'app_livre-delete')]
    public function delete(LivreRepository $lr, EntityManagerInterface $em, $id): Response
    {
        $livre = $lr->find($id);
        if (!$livre) {
            throw $this->createNotFoundException('Le livre n\'existe pas');
        }
        $em->remove($livre);
        $em->flush();
        return new Response('Livre supprimé');
    }

}