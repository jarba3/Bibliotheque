<?php


namespace Bibliotheque\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Bibliotheque\UserBundle\Entity\User;
use Bibliotheque\UserBundle\Entity\Livres;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
	public function adminAction()
	{
		return $this->render('UserBundle:Admin:admin.html.twig');
	}

	public function admin_ajout_livreAction()
	{
		$livre = new Livres();

		$form = $this->createFormBuilder($livre)
					->add('titre', 'text')
					->add('isbn', 'number')
					->add('description', 'textarea')
					->add('dateparution', 'date')
					->getForm();


		return $this->render('UserBundle:Admin:admin_ajout_livre.html.twig', array('form' => $form->createView()));
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

				$this->get('session')->getFlashBag()->add('user_add_success', 'Utilisateur créé dans la base de donnée ! L\'utilisateur doit se connecter pour changer son mot de passe.');

				return $this->redirect($this->generateUrl('bibliotheque_admin_ajout_user'));

	    }


		return $this->render('UserBundle:Admin:admin_profils.html.twig', array('form' => $form->createView()));
	}

	public function admin_modif_userAction(Request $request)
	{
			$this->get('session')->getFlashBag()->add('user_modif_success', 'Utilisateur modifié dans la base de donnée !');

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
				        				'expanded' => false,
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



	public function admin_suppr_userAction(Request $request)
	{

			$search = $this->createFormBuilder()
								->add('recherche', 'search', array('required' => true))
								->add('save', 'submit', array('label' => 'Rechercher','attr' => array('class' => 'submit spacer')))
								->getForm();

			$nom = $request->get('form')['recherche'];

			$search->handleRequest($request);	
			
			if($search->isValid()){

					return $this->redirect($this->generateUrl('bibliotheque_admin_suppr_user_form', array('nom' => $nom)));
				
			}


		return $this->render('UserBundle:admin:admin_suppr_user.html.twig', array('search' => $search->createView()));
	}

	public function admin_suppr_user_formAction($nom,  Request $request)
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
									->add('save', 'submit', array(
														'label' => 'Supprimer',
												  'attr' => array(
												  		'class' => 'submit spacer'
											)
										))
									->getForm();

	    $form->handleRequest($request);
	 
	    if ($form->isValid()) {

        	$em = $this->getDoctrine()->getManager();
        	$em->remove($user);
			$em->flush();


			return $this->redirect($this->generateUrl('bibliotheque_admin_suppr_user'));
	    }
	    
	    $build['form'] = $form->createView();

	    return $this->render('UserBundle:Admin:admin_suppr_user_form.html.twig', $build);
	}
}