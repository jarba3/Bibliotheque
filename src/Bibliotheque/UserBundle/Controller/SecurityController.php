<?php


namespace Bibliotheque\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Bibliotheque\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
	public function loginAction(Request $request)
	{
		$search = $this->createFormBuilder()
                                ->add('recherche', 'search', array('label' => '', 'attr' => array('class' => 'livreSearch')))
                                ->add('save', 'submit', array('label' => 'Rechercher','attr' => array('class' => 'livreSearch')))
                                ->getForm();

		if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			return $this->redirect($this->generateUrl('bibliotheque_index'));
		}
		$session = $request->getSession();

		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		} else {
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}
		return $this->render('UserBundle:Security:login.html.twig', array(

			'last_username' => $session->get(SecurityContext::LAST_USERNAME),
			'error'         => $error,
			'search' => $search->createView(),
			));
	}
	public function profilAction()
    {
        $search = $this->createFormBuilder()
                                ->add('recherche', 'search', array('label' => '', 'attr' => array('class' => 'livreSearch')))
                                ->add('save', 'submit', array('label' => 'Rechercher','attr' => array('class' => 'livreSearch')))
                                ->getForm();
        
        
        $username = $this->get('security.context')->getToken()->getUsername();
		
        return $this->render('UserBundle:Security:profil.html.twig', array('search' => $search->createView(), 'username' => $username));
    }

    public function profil_infoAction($username, request $request)
    {
    	$search = $this->createFormBuilder()
                                ->add('recherche', 'search', array('label' => '', 'attr' => array('class' => 'livreSearch')))
                                ->add('save', 'submit', array('label' => 'Rechercher','attr' => array('class' => 'livreSearch')))
                                ->getForm();


		$session = $this->getRequest()->getSession();

	    $em = $this->getDoctrine()->getManager();
	    $user = $em->getRepository('UserBundle:User')->findByUsername($username)[0];
	   	

	  
	    $form = $this->createFormBuilder($user)
					    			->add('nom', 'text', array('required' => true))
									->add('prenom', 'text', array('required' => true))
									->add('adresse1', 'text', array('required' => true))
									->add('adresse2', 'text', array('required' => false))
									->add('codepostal', 'number', array('required' => true))
									->add('ville', 'text', array('required' => true))
									->add('telephone', 'number', array('required' => true))
									->add('email', 'text', array('required' => true))
									->add('username', 'text', array('required' => true))
									->add('password', 'password', array('required' => false))
									->add('save', 'submit', array(
														'label' => 'Enregistrer',
												  'attr' => array(
												  		'class' => 'submit spacer'
											)
										))
									->getForm();

	    $form->handleRequest($request);
	 
	    if ($form->isValid()) {
	    	
	    	$factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($form->get('password')->getData(), $user->getSalt());
            $user->setPassword($password);

        	$em = $this->getDoctrine()->getManager();
			$em->flush();

			$session->getFlashBag()->add('user_modif_success', 'Utilisateur mis à jour correctement dans la base de donnée.');

			return $this->redirect($this->generateUrl('profil_info', array('username' => $username)));


	    }
	    
    	return $this->render('UserBundle:Security:profil_info.html.twig', array('search' => $search->createView(), 'form'=> $form->createView()));

    }

}