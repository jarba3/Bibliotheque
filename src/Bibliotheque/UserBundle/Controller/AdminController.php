<?php


namespace Bibliotheque\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

use Bibliotheque\UserBundle\Entity\User;
use Bibliotheque\UserBundle\Entity\Livres;
use Bibliotheque\UserBundle\Entity\Auteur;
use Bibliotheque\UserBundle\Entity\Editeur;

class AdminController extends Controller
{
	public function adminAction()
	{	
		return $this->render('UserBundle:Admin:admin.html.twig');
	}

	public function admin_ajout_livreAction(Request $request)
	{

		$livre = new Livres();

		$form = $this->createFormBuilder($livre)
					->add('titre', 'text', array('required' => true))
					->add('isbn', 'text', array('required' => true))
					->add('description', 'textarea', array('required' => true))
					->add('dateparution', 'date', array('required' => true, 'widget' => 'choice', 'years' => range(1900, 2014), 'empty_value' => ''))
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
							)), array('required' => true))
					->add('auteur', 'entity', array(
							'class' => 'UserBundle:Auteur',
							'property' => 'nom',
							'expanded' => false,
							'multiple' => false,
							'query_builder' => function(EntityRepository $er)
							{
        					return $er->createQueryBuilder('auteur')->orderBy('auteur.nom', 'ASC');
    						},
							'empty_value' => 'Choisissez un auteur',
						))
					->add('editeur', 'entity', array(
							'class' => 'UserBundle:Editeur',
							'property' => 'nom',
							'expanded' => false,
							'multiple' => false,
							'query_builder' => function(EntityRepository $er)
							{
        					return $er->createQueryBuilder('editeur')->orderBy('editeur.nom', 'ASC');
    						},
							'empty_value' => 'Choisissez un éditeur'
						))
					->add('save', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'submit spacer')))
					->add('image', 'file', array('required' => true))
					->getForm();

		

		$form->handleRequest($request);



		if($form->isValid()){
			
			$dir = 'bundles/bibliotheque/images';
			$file = $form['image']->getData();

			$extension = $file->guessExtension();
				if (!$extension) {
				    $extension = 'jpeg';
				}
			$nomImage = rand(1, 99999).'.'.$extension;

			$file->move($dir, $nomImage);

			$livre->setUrlimage($dir.'/'.$nomImage);
			$livre->setAltimage($livre->getTitre());


			$em = $this->getDoctrine()->getManager();
			$em->persist($livre);
			$em->flush();

			return $this->redirect($this->generateUrl('bibliotheque_admin_ajout_livre'));
		}


		return $this->render('UserBundle:Admin:admin_ajout_livre.html.twig', array('form' => $form->createView()));
	}
	
	public function admin_modif_livreAction(Request $request)
	{
		$search = $this->createFormBuilder()
								->add('recherche', 'search', array('required' => true))
								->add('save', 'submit', array('label' => 'Rechercher','attr' => array('class' => 'submit spacer')))
								->getForm();

		$nom = $request->get('form')['recherche'];

		$search->handleRequest($request);	
		
		if($search->isValid()){

				return $this->redirect($this->generateUrl('bibliotheque_admin_modif_livre_form', array('nom' => $nom)));
			
		}

			
		return $this->render('UserBundle:Admin:admin_modif_livre.html.twig', array('search' => $search->createView()));
	}

	public function admin_modif_livre_formAction($nom, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
	    $livre = $em->getRepository('UserBundle:Livres')->findByTitre($nom)[0];

		$form = $this->createFormBuilder($livre)
					->add('titre', 'text', array('required' => true))
					->add('isbn', 'text', array('required' => true))
					->add('description', 'textarea', array('required' => true))
					->add('dateparution', 'date', array('required' => true))
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
							)), array('required' => true))
					->add('auteur', 'entity', array(
							'class' => 'UserBundle:Auteur',
							'property' => 'nom',
							'expanded' => false,
							'multiple' => false,
							'data_class' => null
						))
					->add('editeur', 'entity', array(
							'class' => 'UserBundle:Editeur',
							'property' => 'nom',
							'expanded' => false,
							'multiple' => false,
							'data_class' => null
						))
					->add('save', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'submit spacer')))
					->add('image', 'file', array(
							'required' => true,
							'data_class' => null,
							))
					->getForm();

		

		$form->handleRequest($request);



		if($form->isValid()){
			
			$dir = 'bundles/bibliotheque/images';
			$file = $form['image']->getData();

			$extension = $file->guessExtension();
				if (!$extension) {
				    $extension = 'jpeg';
				}
			$nomImage = rand(1, 99999).'.'.$extension;

			$file->move($dir, $nomImage);

			$livre->setUrlimage($dir.'/'.$nomImage);
			$livre->setAltimage($livre->getTitre());


			$em = $this->getDoctrine()->getManager();
			$em->persist($livre);
			$em->flush();

			return $this->redirect($this->generateUrl('bibliotheque_admin_modif_livre'));
		}

		return $this->render('UserBundle:Admin:admin_modif_livre_form.html.twig', array('form' => $form->createView(), 'livre' => $livre));
	}

	public function admin_suppr_livreAction(Request $request)
	{
		$search = $this->createFormBuilder()
						->add('recherche', 'search', array('required' => true))
						->add('save', 'submit', array('label' => 'Rechercher','attr' => array('class' => 'submit spacer')))
						->getForm();

		$nom = $request->get('form')['recherche'];

		$search->handleRequest($request);	
		
		if($search->isValid()){

				return $this->redirect($this->generateUrl('bibliotheque_admin_suppr_livre_form', array('nom' => $nom)));
			
		}
		return $this->render('UserBundle:Admin:admin_suppr_livre.html.twig', array('search' => $search->createView()));
	}

	public function admin_suppr_livre_formAction($nom, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
	    $livre = $em->getRepository('UserBundle:Livres')->findByTitre($nom)[0];

		$form = $this->createFormBuilder($livre)
					->add('titre', 'text', array('required' => true))
					->add('isbn', 'text', array('required' => true))
					->add('description', 'textarea', array('required' => true))
					->add('dateparution', 'date', array('required' => true))
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
							)), array('required' => true))
					->add('auteur', 'entity', array(
							'class' => 'UserBundle:Auteur',
							'property' => 'nom',
							'expanded' => false,
							'multiple' => false,
							'data_class' => null
						))
					->add('editeur', 'entity', array(
							'class' => 'UserBundle:Editeur',
							'property' => 'nom',
							'expanded' => false,
							'multiple' => false,
							'data_class' => null
						))
					->add('save', 'submit', array('label' => 'Supprimer', 'attr' => array('class' => 'submit spacer')))
					->getForm();

		

		$form->handleRequest($request);



		if($form->isValid()){
			
			
			$em = $this->getDoctrine()->getManager();
			$em->remove($livre);
			$em->flush();

			return $this->redirect($this->generateUrl('bibliotheque_admin_suppr_livre'));
		}

		return $this->render('UserBundle:Admin:admin_suppr_livre_form.html.twig', array('form' => $form->createView(), 'livre' => $livre));
	}

	public function admin_ajout_auteurAction(request $request)
	{
		$auteur = new Auteur;

		$form = $this->createFormBuilder($auteur)
							->add('nom', 'text', array('required' => true))
							->add('ajouter', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'submit spacer')))
							->getForm();

		$form->handleRequest($request);

		if($form->isValid()){

			$em = $this->getDoctrine()->getManager();
			$em->persist($auteur);
			$em->flush();

			return $this->redirect($this->generateUrl('bibliotheque_admin_ajout_auteur'));
		}

		return $this->render('UserBundle:Admin:admin_ajout_auteur.html.twig', array('form' => $form->createView()));
	}

	public function admin_ajout_editeurAction(request $request)
	{
		$editeur = new Editeur;

		$form = $this->createFormBuilder($editeur)
							->add('nom', 'text', array('required' => true))
							->add('ajouter', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'submit spacer')))
							->getForm();

		$form->handleRequest($request);

		if($form->isValid()){

			$em = $this->getDoctrine()->getManager();
			$em->persist($editeur);
			$em->flush();

			return $this->redirect($this->generateUrl('bibliotheque_admin_ajout_editeur'));
		}

		return $this->render('UserBundle:Admin:admin_ajout_editeur.html.twig', array('form' => $form->createView()));
	}

	public function admin_ajout_userAction(Request $request)
	{
		$session = $this->getRequest()->getSession();
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

				$session->getFlashBag()->add('user_add_success', 'Utilisateur créé dans la base de donnée ! L\'utilisateur doit se connecter pour changer son mot de passe.');

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
		$session = $this->getRequest()->getSession();

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

			$session->getFlashBag()->add('user_modif_success', 'Utilisateur mis à jour correctement dans la base de donnée.');

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
	public function admin_pretsAction()
	{
		return $this->render('UserBundle:Admin:admin_prets.html.twig');
	}

}