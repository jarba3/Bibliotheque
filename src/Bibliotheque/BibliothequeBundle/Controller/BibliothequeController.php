<?php

namespace Bibliotheque\BibliothequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BibliothequeController extends Controller
{
    public function indexAction()
    {
        return $this->render('BibliothequeBundle:Bibliotheque:index.html.twig');
    }
    public function livresAction()
    {
        return $this->render('BibliothequeBundle:Bibliotheque:livres.html.twig');
    }
    public function panierAction()
    {
        return $this->render('BibliothequeBundle:Bibliotheque:panier.html.twig');
    }
}
