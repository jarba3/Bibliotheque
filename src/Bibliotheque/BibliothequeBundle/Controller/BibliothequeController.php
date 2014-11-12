<?php

namespace Bibliotheque\BibliothequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;

use Bibliotheque\UserBundle\Entity\Livres;
use Bibliotheque\UserBundle\Entity\Theme;
use Bibliotheque\UserBundle\Entity\Auteur;
use Bibliotheque\UserBundle\Entity\Editeur;

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

        $repository2 = $this->getDoctrine()->getManager()->getRepository('UserBundle:Theme');
        $theme = $repository2->findAll();
        

        return $this->render('BibliothequeBundle:Bibliotheque:livres.html.twig', array('search' => $search->createView(), 'livre' => $livre, 'theme' => $theme));
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
        $livre = $repository->findByTitre($titre)[0];
        
        $repository2 = $this->getDoctrine()->getManager()->getRepository('UserBundle:Theme');
        $theme = $repository2->findAll();

        return $this->render('BibliothequeBundle:Bibliotheque:detail_livre.html.twig', array('search' => $search->createView(), 'livre' => $livre, 'theme' => $theme));
    }
    
}