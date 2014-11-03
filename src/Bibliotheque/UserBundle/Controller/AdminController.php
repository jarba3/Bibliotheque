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
					->add('theme', 'choice', array(
						'choices' => array(
										'actu_politique_societe' => 'Actu, Politique et Société',
										'adolescents' => 'Adolescents',
										'art_musique_cinema' => 'Art, Musique et Cinéma',
										'bandes_dessinees' => 'Bandes dessinées',
										'beaux_livres' => 'Beaux livres',
										'cuisine_vins' => 'Cuisine et Vins',
										'dictionnaires_langues_encyclopedies' => 'Dictionnaires, langues et encyclopédies',
										'droit' => 'Droit',
										'entreprise_bourse' => 'Entreprise et Bourse',
										'erotisme' => 'Erotisme',
										'esotérisme_paranormal' => 'Esotérisme et Paranormal',
										'etudes_superieures' => 'Etudes supérieures',
										'famille_sante_bien-etre' => 'Famille, Santé et Bien-être',
										'fantasy_terreur' => 'Fantasy et Terreur',
										'histoire' => 'Histoire',
										'humour' => 'Humour',
										'informatique_internet' => 'Informatique et Internet',
										'litterature' => 'Littérature',
										'litterature_sentimentale' => 'Littérature sentimentale',
										'livres_enfants' => 'Livres pour enfants',
										'loisirs_creatifs_decoration_bricolage' => 'Loisirs créatifs, décoration et bricolage',
										'manga' => 'Manga',
										'nature_animaux' => 'Nature et animaux',
										'policier_suspense' => 'Policier et Suspense',
										'religions_spiritualites' => 'Religions et Spiritualités',
										'science-fiction' => 'Science-Fiction',
										'sciences_humaines' => 'Sciences humaines',
										'sciences_techniques_Medecine' => 'Sciences, Techniques et Médecine',
										'scolaire_parascolaire' => 'Scolaire et Parascolaire',
										'sports_passions' => 'Sports et passions',
										'tourisme_voyages' => 'Tourisme et Voyages',
							)))
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