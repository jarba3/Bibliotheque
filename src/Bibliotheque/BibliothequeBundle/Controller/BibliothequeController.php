<?php

namespace Bibliotheque\BibliothequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Bibliotheque\UserBundle\Entity\Livres;

class BibliothequeController extends Controller
{
    public function indexAction()
    {

        $search = $this->createFormBuilder()
                                ->add('recherche', 'search', array('label' => '', 'attr' => array('class' => 'livreSearch')))
                                ->add('save', 'submit', array('label' => 'Rechercher','attr' => array('class' => 'livreSearch')))
                                ->getForm();

        return $this->render('BibliothequeBundle:Bibliotheque:index.html.twig', array('search' => $search->createView()));
    }

    public function livresAction(request $request)
    {
        $search = $this->createFormBuilder()
                                ->add('recherche', 'search', array('label' => '', 'attr' => array('class' => 'livreSearch')))
                                ->add('save', 'submit', array('label' => 'Rechercher','attr' => array('class' => 'livreSearch')))
                                ->getForm();

        $repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Livres');
        $livre = $repository->findAll();

        return $this->render('BibliothequeBundle:Bibliotheque:livres.html.twig', array('search' => $search->createView(), 'livre' => $livre ));
    }

    public function panierAction()
    {
        $search = $this->createFormBuilder()
                                ->add('recherche', 'search', array('label' => '', 'attr' => array('class' => 'livreSearch')))
                                ->add('save', 'submit', array('label' => 'Rechercher','attr' => array('class' => 'livreSearch')))
                                ->getForm();

        return $this->render('BibliothequeBundle:Bibliotheque:panier.html.twig', array('search' => $search->createView()));
    }


    public function detail_livreAction($titre, request $request)
    {
        $search = $this->createFormBuilder()
                                ->add('recherche', 'search', array('label' => '', 'attr' => array('class' => 'livreSearch')))
                                ->add('save', 'submit', array('label' => 'Rechercher','attr' => array('class' => 'livreSearch')))
                                ->getForm();

        $repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Livres');
        $livre = $repository->findAll($titre);

        $vueLivre = $this->createFormBuilder($livre)
                    ->add('titre', 'text')
                    ->add('isbn', 'text')
                    ->add('description', 'textarea')
                    ->add('dateparution', 'date')
                    ->add('theme', 'text')
                    ->add('auteur', 'text')
                    ->add('editeur', 'text')
                    ->add('save', 'submit', array('label' => 'Réserver', 'attr' => array('class' => 'submit spacer')))
                    ->add('image', 'file')
                    ->getForm();

        $vueLivre->handleRequest($request);

        return $this->render('BibliothequeBundle:Bibliotheque:detail_livre.html.twig', array('search' => $search->createView(), 'livre' => $vueLivre->createView()));
    }
    
}