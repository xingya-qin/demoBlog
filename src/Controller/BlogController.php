<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

    // create() : méthode permettant d'insérer un nouvle article en BDD

     /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function create(Article $article = null,Request $request , EntityManagerInterface $manager)
    {
        /*
            La class Request est une clasee prédéfinie en Symfony qui stocke toute les données véhiculées par les superglobales
            ($_POST, $COOKIE, $_SERVER ect...)
            Nous avons accès aux données saisie dans le fourmulaire via l'objet $request
            La proprité 'request->request' représente la superglobale $_POST, les données saisies dans le formulaire sont accessible
            via cette proprité
            pour insérer un nouvel article , nous devons instancier la classe / Entitée Article pour avoir un objet Article vide , afon de 
            renseigner tout les setteursde l'objet $article

            EntityManagerInterface est une interface prédefinie en Symnfony qui permet de manipulier les lignes de la BDD(INSET,UPDATE,DELETE)
            Elle possède des méthodes permettant de péparer et d'executer les requetes SQL (persist() | flush ())

            persist() est une méthode issue de l'interface EntityManagerInterface qui permet de préparer et sticker la requete SQL
            flush() est une méthode issue de l'interface EntityManagerInterface qui permet de libérer eet d'executer la requete SQL

            redirectToRoute() est une méthode prédéfinie en Symfony qui permet de rediriger vers une route spécifique, dans notre 
            cqs on redirige après insertion vers la routr 'blog_show' (détail de l'article que l'on vient d'insérer) et on transmet à la 
            méthode l'id de l'article a envoyer dans l'URL

            get(): méthode del'objet $request qui permet de récupérer les donnéés saisie aux différents indicces 'name' du formulaire
       
        */



        
        //dump($request)

        //if($request->request->count() > 0)
       // {
            //$article = new Article;

        //   $article->setTitle($request->request->get('title'))
          //          ->setContent($request->request->get('content'))
           //         ->setImage($request->request->get('image'))
            //        ->setCreatedAt(new \DateTime());
       //

         //   $manager->persist($article);

         //   $manager->flush();

          //  dump($article);

        //    return $this->redirectToRoute('blog_show',[
         //       'id' => $article->getId()
        //    ]);

       // }


       if(!$article)
       {
           $article =  new Article;
       }
    

    //    $article->setTitle("Titre à la con")
    //            ->setContent("Contenu à la con");

       $form = $this->createFormBuilder($article)
                    ->add('title')
                    ->add('content')
                    ->add('image')
                    ->getForm();

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $article->setCreatedAt(new \DateTime);

            $manager->persist($article);
            $manager->flush();

            dump($article);

            return $this->redirectToRoute('blog_show',[
                    'id' => $article->getId()
                ]);
        }

        return $this->render('blog/create.html.twig',[
            'formArticle' =>$form->createView()
        ]);


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