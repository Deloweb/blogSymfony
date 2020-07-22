<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator, ArticleRepository $articleRepository): Response
    {
        // $articles = $articleRepository->findAll();
        $dql   = "SELECT a FROM App:Article a";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('article/index.html.twig', [
            // 'articles' => $articles,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/tag/{id}", name="article_tag_index", methods={"GET"})
     */
    public function tag_index(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator, ArticleRepository $articleRepository, Tag $tag): Response
    {

        
        $articles = $articleRepository->findByTag($tag);
        // $articles = $articleRepository->findAll();
        $dql   = "SELECT a FROM App:Article a";
        $query = $em->createQuery($dql);
        
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        
        return $this->render('article/index.html.twig', [
            // 'articles' => $articles,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/category/{id}", name="article_category_index", methods={"GET"})
     */
    public function category_index(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator, ArticleRepository $articleRepository, Category $category): Response
    {

        $qb = $articleRepository->findAll();

        // $articles = $articleRepository->findByCategory($category);

        // $articles = $articleRepository->findAll();
        $dql   = "SELECT a FROM App:Article a";
        $query = $em->createQuery($dql);
        // ->setParams("category", $category->getId();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('article/index.html.twig', [
            // 'articles' => $articles,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }
}
