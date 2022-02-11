<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Inscription;
use App\Entity\Stagiaire;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExCrudController extends AbstractController
{

    // INSERT

    #[Route('/ex/crud/insert/stagiaire', name: 'insert_stagiaire')]
    public function insertStagiaire(ManagerRegistry $doctrine): Response
    {
        //em pour Entity Manager
        $em = $doctrine->getManager();
        
        // Créer l'objet
        $stagiaire1 = new Stagiaire(['nom'=>'Assmaa', 'email'=>'assmaa@interface3.be']);
        
        // Persist = Lier l'objet avec la BD
        $em->persist($stagiaire1);

        // Écrire l'objet dans la BD
        $em->flush();

        $vars = ['stagiaire'=>$stagiaire1];
        // return new Response ("Ok, stagiaire inséré");
        return $this->render('ex_crud/insert_stagiaire.html.twig', $vars);
    }

    #[Route('/ex/crud/insert/cours', name: 'insert_cours')]
    public function insertCours(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        
        // Créer l'objet
        $cours1 = new Cours(['titre'=>'Node.JS', 'description'=>'Cours de Node.JS pour complété JavaScript donné par Rudy.']);
        
        // Lier l'objet avec la BD
        $em->persist($cours1);

        // Écrire l'objet dans la BD
        $em->flush();

        // return new Response ("Ok, cours inséré");

        $vars = ['cours'=>$cours1];
        // return new Response ("Ok, stagiaire inséré");
        return $this->render('ex_crud/insert_cours.html.twig', $vars);
    }

    #[Route('/ex/crud/insert/inscription', name: 'insert_inscription')]
    public function insertInscription(ManagerRegistry $doctrine): Response
    {
        //POSSIBILITE 1 (Théorique)
        // Création Stagiaire
        $s1 = new Stagiaire(['nom'=>'Stefania', 'email'=>'stef@interface3.be']);
        $s2 = new Stagiaire(['nom'=>'Judy', 'email'=>'judy@interface3.be']);
        // Création Cours
        $c1 = new Cours(['titre'=>'C#', 'description'=>'Cours de C# (fortement typé) avec Cognitic.']);
        // Création Inscription
        $i1 =new Inscription(['dateInscription'=> new \DateTime ('2022/2/11'), ]);
        $i1->setStagiaire($s1);
        $i1->setCours($c1);

        $i2 =new Inscription(['dateInscription'=> new \DateTime ('2022/2/11'), ]);
        $i2->setStagiaire($s2);
        $i2->setCours($c1);
        
        // Entity Manager
        $em = $doctrine->getManager();
        // Lier les objets 
        $em->persist($s1);
        $em->persist($s2);
        $em->persist($c1);
        $em->persist($i1);
        $em->persist($i2);
        // Flush dans DB
        $em->flush();
        return new Response ("Ok, inscription insérée");
    }

    #[Route('/ex/crud/insert/inscription/bd', name: 'insert_inscription_bd')]
    public function insertInscriptionBD(ManagerRegistry $doctrine): Response
    {
        //POSSIBILITE 2 (RealLife)
        $em = $doctrine->getManager();

        // 1. Obtenir une stagiaire de la BD (user session)
        $repS = $em->getRepository(Stagiaire::class);
        $stagiaire = $repS->find(1); // pour prendre le stagiaire avec l'id 1
        dump($stagiaire);

        // 2. Obtenir un Cours de la BD (choisi par le user)
        $repC = $em->getRepository(Cours::class);
        $arrayCours = $repC->findAll(); // pour obtenir tous les Cours
        $unCours = $arrayCours[rand(0,count($arrayCours)-1)]; // choisir un cours au hasard
        dump($unCours);
        
        // 3. Créer une inscription
        $i1 =new Inscription(['dateInscription'=> new \DateTime(), ]); // Date d'aujourd'hui
        $stagiaire->addInscription($i1);
        $unCours->addInscription($i1);
        
        // 4. Persist et Stocker l'inscription (persist uniquement pour Insert un new pas pour updater(set...))
        $em->persist($i1);

        // Flush dans DB
        $em->flush();
        return new Response ("Ok, inscription insérée");
    }

    // SELECT

    #[Route('/ex/crud/select/stagiaire', name: 'select_stagiaire')]
    public function selectStagiaires(ManagerRegistry $doctrine)
    {
        // Chercher par nom
        $em = $doctrine->getManager();
        $rep = $em->getRepository(Stagiaire::class);
        $stagiaires = $rep->findAll();
        $vars = ['stagiaires' => $stagiaires];

        // $objetLivre = $rep->findOneBy(['titre' => 'La Vie', 'isbn'=>'REF1234']); // WHERE avec AND
        // $resultat = $rep->findBy(['titre' => 'La Vie']); ===>>> Pour avoir un array de tous les livres avec ce titre
        // dump($objetLivre->getTitre());
        // dd($objetLivre);
        
        return $this->render('exemple_model/exemple_select_find_all.html.twig', $vars);
    }



}
