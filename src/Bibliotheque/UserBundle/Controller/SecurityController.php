<?php


namespace Bibliotheque\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Bibliotheque\UserBundle\Entity\User;


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

	public function admin_ajout_userAction(request $request)
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
        				'multiple' => false,
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

	    		$factory = $this->get('security.encoder_factory');
	            $encoder = $factory->getEncoder($user);
	            $password = $encoder->encodePassword($form->get('password')->getData(), $user->getSalt());
	            $user->setPassword($password);

	        	$em = $this->getDoctrine()->getManager();
				$em->persist($user);
				$em->flush();

	    }


		return $this->render('UserBundle:Admin:admin_profils.html.twig', array('form' => $form->createView()));
	}

	public function admin_modif_userAction(request $request)
	{


		$search = $this->createFormBuilder()
							->add('recherche', 'search')
							->getForm();
		
		$nom = $request->get('form')['recherche'];
		
		$repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:User');
		$user = $repository->findByUsername($nom);
		



		if($user != NULL){

			$form = $this->createFormBuilder($user)
					->add('nom', 'text', array('required' => true, 'attr' => array('value' => $user[0]->getNom())))
					->add('prenom', 'text', array('required' => true, 'attr' => array('value' => $user[0]->getPrenom())))
					->add('adresse1', 'text', array('required' => true, 'attr' => array('value' => $user[0]->getAdresse1())))
					->add('adresse2', 'text', array('required' => false, 'attr' => array('value' => $user[0]->getAdresse2())))
					->add('codepostal', 'number', array('required' => true, 'attr' => array('value' => $user[0]->getCodepostal())))
					->add('ville', 'text', array('required' => true, 'attr' => array('value' => $user[0]->getVille())))
					->add('telephone', 'number', array('required' => true, 'attr' => array('value' => $user[0]->getTelephone())))
					->add('email', 'text', array('required' => true, 'attr' => array('value' => $user[0]->getEmail())))
					->add('username', 'text', array('required' => true, 'attr' => array('value' => $user[0]->getUsername())))
					->add('password', 'password', array('required' => true))
					->add("roles", 'choice', array(
        				'expanded' => false,
        				'multiple' => false,
        				'choices'  => array(
							            'ROLE_ADMIN' => 'Administrateur',
							            'ROLE_BIBLIOTHECAIRE'  => 'Bibliothecaire',
							            'ROLE_ETUDIANT'   => 'Etudiant',
							            'ROLE_PROFESSEUR'  => 'Professeur',
					        				),'attr' => array('value' => $user[0]->getRoles()[0])))
					->add('save', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'submit spacer')))
					->getForm();

				return $this->render('UserBundle:admin:admin_modif_user.html.twig', array('form' => $form->createView(), 'user' => $user));

		}

		return $this->render('UserBundle:admin:admin_modif_user.html.twig', array('form' => $search->createView(), 'user' => $user));
	}



	public function bibliothecaireAction()
	{
		return $this->render('UserBundle:bibliothecaire:bibliothecaire.html.twig');
	}
}