<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {   
        // La librairie FAKER permaet d'insérer en BDD des fausses données (fixtures), il génère via diffiérentes méthodes, des données 
        $faker = \Faker\Factory::create('fr_FR');
        //  Création de 3 catégories
        for ($i = 1; $i <=3; $i++)
        {   
            // On instancie l'entité Categoty afin d'insérer des catégories dans la BDD
            $category = new Category;

            $category->setTittle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);  
            //création entre 4 et 6 articles par catégorie
            
            for($j = 1; $j <= mt_rand(4,6); $j++)
            {   
                //On instancie l'entité Article afin d'insérer des articles dans la BDD
                $article = new Article;

                $content = '<p>' .join($faker->paragraphs(5),'</p><p>') .'</p>'; // On séparer las paragraphes crées par FAKER par 
                 // des balises <p></p>

                // On apple les secteurs de l'objet $article
                $article->setTitle($faker->sentence()) //titre aléatoire
                        ->setContent($content)          //parqgraphes aléatoire        
                        ->setImage($faker->imageUrl())  //génère des URL d'image lorempixel aléatoire
                        ->setCreatedAt($faker->dateTimeBetween('-6 months')) // création de date de commentaire d'il y a 6 mois à
                        // aujour'hui
                        ->setCategory($category); // on relie nos articles aus catégories crées juste au desus (clé étrangère)
                $manager->persist($article);       // on prépare l'insertion des articles      


                //Création entre 4 et 10 commentaire par article
                for($k = 1; $k <= mt_rand(4,10); $k++)
                {
                    // On instancie l'entité Comment afin d'insérer des commentaires dans la BDD
                    $comment = new Comment;

                    $content = '<p>' .join($faker->paragraphs(5),'</p><p>') .'</p>'; // on séparer les paragraphes crées par FAKER par des balises <p></p>

                    $now = new \Datetime;
                    $interval = $now->diff($article->getCreatedAt());// Représente le temps en timestape entre la date de céation de 
                    //l'article et maintenant
                    $days = $interval->days; // nombre de jour entre la date de création de l'article et maintenant
                    $minimum = '-' .$days . 'days';

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);

                    $manager->persist($comment);        
                }        
            }
            
        }

        $manager->flush();


    }
}
