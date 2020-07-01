<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/articles", name="admin_articles")
     */
    public function adminArticles(ArticleRepository $repo)
    {   
        // On apple getManager() qui est le gestionnaire d'entités de Doctrine. Il est responsables de l'enregistrement des 
        // objets et leur r"cupèration dans la base de données
        $em = $this->getDoctrine()->getManager();

        // getClassMetadata() permet de recolter les métadonnées d'une table SQL (noms des champs, type de champs) à 
        $colonnes = $em->getClassMatadata(Article::class)->getFieldNames();

        $artcles = $repo->findAll();

        dump($articles);
        dump($colonnes);
        
        return $this->render('admin/admin_articles.html.twig', [
            'articles' => $articles,
            'colonnes' =>$colonnes
        ]);
    }

    /**
     * @Route("/admin/{id}/edit_article", name="admin_edit_article")
     */
    public function editArticles(Article $article)
    {
        dump($article);
        $form = $this->createForm(ArticleType::class, $article);
        return $this->render('admin/edit_article.html.twig');
    }
}
