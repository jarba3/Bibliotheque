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
			));
	}

	public function adminAction()
	{
		return $this->render('UserBundle:Admin:admin.html.twig');
	}

	public function admin_livresAction()
	{
		return $this->render('UserBundle:Admin:admin_livres.html.twig');
	}

	public function admin_pretsAction()
	{
		return $this->render('UserBundle:Admin:admin_prets.html.twig');
	}

	public function admin_ajout_userAction(Request $request)
	{

		$user = new User();

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
					->add('password', 'password', array('required' => true))
					->add("roles", 'choice', array(
        				'expanded' => false,
        				'multiple' => true,
        				'choices'  => array(
							            'ROLE_ADMIN' => 'Administrateur',
							            'ROLE_BIBLIOTHECAIRE'  => 'Bibliothecaire',
							            'ROLE_ETUDIANT'   => 'Etudiant',
							            'ROLE_PROFESSEUR'  => 'Professeur')))
					->add('save', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'submit spacer')))
					->getForm();

		$form->handleRequest($request);

	    if ($form->isValid()) {

	    		$factory = $this->get('security.encoder_factory');
	            $encoder = $factory->getEncoder($user);
	            $password = $encoder->encodePassword($form->get('password')->getData(), $user->getSalt());
	            $user->setPassword($password);
	            $role = $form->get('roles')->getData();
	            $user->setRoles($role[0]);

	        	$em = $this->getDoctrine()->getManager();
				$em->persist($user);
				$em->flush();


				return $this->redirect($this->generateUrl('bibliotheque_admin_ajout_user'));

	    }


		return $this->render('UserBundle:Admin:admin_profils.html.twig', array('form' => $form->createView()));
	}

	public function admin_modif_userAction(Request $request)
	{

			$search = $this->createFormBuilder()
								->add('recherche', 'search', array('required' => true))
								->add('save', 'submit', array('label' => 'Rechercher','attr' => array('class' => 'submit spacer')))
								->getForm();

			$nom = $request->get('form')['recherche'];

			$search->handleRequest($request);	
			
			if($search->isValid()){

					return $this->redirect($this->generateUrl('bibliotheque_admin_modif_user_form', array('nom' => $nom)));
				
			}


		return $this->render('UserBundle:admin:admin_modif_user.html.twig', array('search' => $search->createView()));
	}

	public function admin_modif_user_formAction($nom,  Request $request)
	{

	    $em = $this->getDoctrine()->getManager();
	    $user = $em->getRepository('UserBundle:User')->findByUsername($nom)[0];
	   	

	  
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
									// ->add('password', 'password', array('required' => true))
									->add("roles", 'choice', array(
				        				'expanded' => true,
				        				'multiple' => true,
				        				'choices'  => array(
											            'ROLE_ADMIN' => 'Administrateur',
											            'ROLE_BIBLIOTHECAIRE'  => 'Bibliothecaire',
											            'ROLE_ETUDIANT'   => 'Etudiant',
											            'ROLE_PROFESSEUR'  => 'Professeur',
									        ),
									    ))
									->add('save', 'submit', array(
														'label' => 'Enregistrer',
												  'attr' => array(
												  		'class' => 'submit spacer'
											)
										))
									->getForm();

	    $form->handleRequest($request);
	 
	    if ($form->isValid()) {

        	$role = $form->get('roles')->getData();
	        $user->setRoles($role[0]);

        	$em = $this->getDoctrine()->getManager();
			$em->flush();


			return $this->redirect($this->generateUrl('bibliotheque_admin_modif_user'));
	    }
	    
	    $build['form'] = $form->createView();

	    return $this->render('UserBundle:admin:admin_modif_user_form.html.twig', $build);
	}


	public function bibliothecaireAction()
	{
		return $this->render('UserBundle:bibliothecaire:bibliothecaire.html.twig');
	}
}