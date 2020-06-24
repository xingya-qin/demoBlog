<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /*
        Symfony fonctionne toujours avec un système de routage. Une méthode d'un controlleur sera executée en fonction de la route transmise dans l'URL.
        ex : Si nous envoyons la route '/blog' dans l'url (http://localhost:8000/blog), cela fait appel au controller 'BlogController' et execute la méthode 'index()'. Cette méthode renvoi un template sur le navigateur (méthode render())
        Symfony se sert des annotations (@Route())
        Les annotations doivent toujours contenir 4 astérix
    */

    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
       /*
        Un des prin pes de principe de base de Symfony est l'injection de dépendances.
            Par exemple, ici dans le cas de la méthode index(), cette a besoin de la classe ArticleRepository pour fonctionner correctement, c'est à dire que la méthode index() dépend de la classe ArticleRepository
            Donc ici on injecte une dépendance en argument de la méthode index(), on impose un objet issu de la classe ArticleRepository
            Du coup plus besoin de faire appel à Doctrine (getDoctrine())
            $repo est un objet issu de la classe ArticleRepository et nous avons accès à toute les méthodes issues de cette classe
            Les méthodes sont moins chargé et c'est plus simple d'utilisatio
        */     

       
        $repo = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repo->findAll(); //équivalent à SELECT * FROM Article + FETCH_ALL

        dump($articles);// equivalent de var_dump();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles

        ]);
    }

    /**
     * @Route("/", name="home") 
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Bienvenue sur le blog Symfony',
            'age' => 25
        ]);
    }

     /**
     * @Route("/blog/new", name="blog_create")
     */
    public function create()
    {
        return $this->render('blog/create.html.twig');
    }


    // show() : méthode permettant de voir le détail d'un article

    /**
     * @Route("/blog/{id}", name="blog_show" )
     */
    public function show($id) // id 1 
    {
        /* show()    c'est une variable de reception que nous sommes à souhaite et quireception un objet issu de la classe ArticleRepository*/
        $repo = $this->getDoctrine()->getRepository(Article::class);

        $article = $repo->find($id);

        dump($article);

        return $this->render('blog/show.html.twig',[
            'article' =>$article
        ]);
    }


}