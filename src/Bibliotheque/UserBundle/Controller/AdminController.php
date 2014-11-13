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
use Bibliotheque\UserBundle\Entity\Theme;
use Bibliotheque\UserBundle\Entity\Exemplaire;


class AdminController extends Controller
{
	public function adminAction()
	{	
		return $this->render('UserBundle:Admin:admin.html.twig');
	}

	public function admin_ajout_exemplaireAction(Request $request)
	{
		$exemplaire = new Exemplaire();

		$form = $this->createFormBuilder($exemplaire)
						->add('livre', 'entity', array(
								'class' => 'UserBundle:Livres',
								'property' => 'isbn',
								'empty_value' => 'Choisissez un isbn'
							))
						->add('valider', 'submit', array('label' => 'Valider', 'attr' => array('class' => 'submit spacer')))
						->getForm();

		$form->handleRequest($request);

		if($form->isValid()){

			$isbn = $exemplaire->getLivre()->getIsbn();

			return $this->redirect($this->generateUrl('bibliotheque_admin_ajout_exemplaire_form', array('isbn' => $isbn)));

		}

		return $this->render('UserBundle:Admin:admin_ajout_exemplaire.html.twig', array('form' => $form->createView()));
	}

	public function admin_ajout_exemplaire_formAction($isbn, Request $request)
	{	
		$repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Livres');
		
		$livre = $repository->findByIsbn($isbn)[0];
		
		$date = getdate();

		$exemplaire = new Exemplaire();

		$form = $this->createFormBuilder($exemplaire)
						->add('dateacquisition', 'date', array('required' => true, 'years' => range(1900, 2014), 'empty_value' => getdate()))
						->add('usure', 'choice', array(
							'expanded' => false,
							'multiple' => false,
							'choices' => array(
									'neuf' => 'Neuf',
									'bon-etat' => 'Bon état',
									'usage' => 'Usagé',
									'a-remplacer' => 'A remplacer'
								)
							))
						->add('save', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'submit spacer')))
						->getForm();

		$form->handleRequest($request);

		if($form->isValid()){
			
			$exemplaire->setLivre($livre);

			$em = $this->getDoctrine()->getManager();
			$em->persist($exemplaire);
			$em->flush();

			return $this->redirect($this->generateUrl('bibliotheque_admin_ajout_exemplaire'));
		}

		return $this->render('UserBundle:Admin:admin_ajout_exemplaire_form.html.twig', array('livre' => $livre, 'form' => $form->createView()));
	}

	public function admin_gestion_exemplaireAction(Request $request)
	{
		$exemplaire = new Exemplaire();

		$form = $this->createFormBuilder($exemplaire)
						->add('livre', 'entity', array(
								'class' => 'UserBundle:Livres',
								'property' => 'isbn',
								'empty_value' => 'Choisissez un isbn'
							))
						->add('valider', 'submit', array('label' => 'Valider', 'attr' => array('class' => 'submit spacer')))
						->getForm();

		$form->handleRequest($request);

		if($form->isValid()){

			$isbn = $exemplaire->getLivre()->getIsbn();

			return $this->redirect($this->generateUrl('bibliotheque_admin_gestion_exemplaire_form', array('isbn' => $isbn)));

		}

		return $this->render('UserBundle:Admin:admin_gestion_exemplaire.html.twig', array('form' => $form->createView()));
	}

	public function admin_gestion_exemplaire_formAction($isbn)
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Livres');
		$livre = $repository->findByIsbn($isbn)[0];

		$_SESSION['ISBN']=$isbn;

		$exemplaire = $livre->getExemplaire()->getValues();


		return $this->render('UserBundle:Admin:admin_gestion_exemplaire_form.html.twig', array('exemplaire' => $exemplaire, 'livre' => $livre));
	}

	public function admin_suppressionAction($id, Request $request)
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Exemplaire');
		$exemplaire = $repository->findById($id)[0];


		$em = $this->getDoctrine()->getManager();
		$em->remove($exemplaire);
		$em->flush();

		return $this->redirect($this->generateUrl('bibliotheque_admin_gestion_exemplaire_form', array('isbn' => $_SESSION['ISBN'])));
	}

	public function admin_modificationAction($id, Request $request)
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Exemplaire');
		$exemplaire = $repository->findById($id)[0];

		$form = $this->createFormBuilder($exemplaire)
						->add('dateacquisition', 'date')
						->add('usure', 'choice', array(
							'expanded' => false,
							'multiple' => false,
							'choices' => array(
									'neuf' => 'Neuf',
									'bon-etat' => 'Bon état',
									'usage' => 'Usagé',
									'a-remplacer' => 'A remplacer'
								)
							))
						->add('save', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'submit spacer')))
						->getForm();

		$form->handleRequest($request);

		if($form->isValid()){

			$em = $this->getDoctrine()->getManager();
			$em->flush();
			
			return $this->redirect($this->generateUrl('bibliotheque_admin_gestion_exemplaire'));
		}

		return $this->render('UserBundle:Admin:admin_modif_exemplaire.html.twig', array('form' => $form->createView()));
	}

	public function admin_ajout_livreAction(Request $request)
	{

		$livre = new Livres();

		$form = $this->createFormBuilder($livre)
					->add('titre', 'text', array('required' => true))
					->add('isbn', 'text', array('required' => true))
					->add('description', 'textarea', array('required' => true))
					->add('dateparution', 'date', array('required' => true, 'widget' => 'choice', 'years' => range(1900, 2014), 'empty_value' => getdate()))
					->add('theme', 'entity', array(
							'class' => 'UserBundle:Theme',
							'property' => 'intitule',
							'expanded' => false,
							'multiple' => false,
							'query_builder' => function(EntityRepository $er)
							{
        					return $er->createQueryBuilder('theme')->orderBy('theme.intitule', 'ASC');
    						},
							'empty_value' => 'Choisissez un thème',
							'required' => true,
						))
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
							'required' => true,
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
							'empty_value' => 'Choisissez un éditeur',
							'required' => true,
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
		$repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Livres');
		$livre = $repository->findAll();


		$form = $this->createFormBuilder($livre)
								->add('isbn', 'entity', array(
									'class' => 'UserBundle:Livres',
									'property' => 'isbn',
									'expanded' => false,
									'multiple' => false,
									'empty_value' => 'Selectionner un isbn'
									))
								->getForm();

		$search = $this->createFormBuilder()
								->add('recherche', 'search', array('required' => true))
								->add('save', 'submit', array('label' => 'Rechercher','attr' => array('class' => 'submit spacer')))
								->getForm();

		$nom = $request->get('form')['recherche'];

		$search->handleRequest($request);	
		
		if($search->isValid()){

				return $this->redirect($this->generateUrl('bibliotheque_admin_modif_livre_form', array('nom' => $nom)));
			
		}

			
		return $this->render('UserBundle:Admin:admin_modif_livre.html.twig', array('search' => $search->createView(), 'form' => $form->createView()));
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
					->add('theme', 'entity', array(
							'class' => 'UserBundle:Theme',
							'property' => 'intitule',
							'expanded' => false,
							'multiple' => false,
							'query_builder' => function(EntityRepository $er)
							{
        					return $er->createQueryBuilder('theme')->orderBy('theme.intitule', 'ASC');
    						},
							'empty_value' => 'Choisissez un thème',
							'required' => true,
						))
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
							'required' => true,
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
							'empty_value' => 'Choisissez un éditeur',
							'required' => true,
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

		$repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Livres');
		$livres = $repository->findAll();
		
		if($search->isValid()){

				return $this->redirect($this->generateUrl('bibliotheque_admin_suppr_livre_form', array('nom' => $nom)));
			
		}
		return $this->render('UserBundle:Admin:admin_suppr_livre.html.twig', array('search' => $search->createView(), 'livres' => $livres));
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
					->add('theme', 'entity', array(
							'class' => 'UserBundle:Theme',
							'property' => 'intitule',
							'expanded' => false,
							'multiple' => false,
							'query_builder' => function(EntityRepository $er)
							{
        					return $er->createQueryBuilder('theme')->orderBy('theme.intitule', 'ASC');
    						},
							'empty_value' => 'Choisissez un thème',
							'required' => true,
						))
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
							'required' => true,
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
							'empty_value' => 'Choisissez un éditeur',
							'required' => true,
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

	public function admin_suppr_auteurAction(Request $request)
	{

		$repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Auteur');
		$auteur = $repository->findAllOrderedByName();
				
		return $this->render('UserBundle:Admin:admin_suppr_auteur.html.twig', array('auteur' => $auteur));
	}

	public function admin_suppression_auteurAction($id, Request $request)
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Auteur');
		$exemplaire = $repository->findById($id)[0];


		$em = $this->getDoctrine()->getManager();
		$em->remove($exemplaire);
		$em->flush();

		return $this->redirect($this->generateUrl('bibliotheque_admin_suppr_auteur'));
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

	public function admin_suppr_editeurAction(Request $request)
	{	

		$repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Editeur');
		$editeur = $repository->findAllOrderedByName();

		return $this->render('UserBundle:Admin:admin_suppr_editeur.html.twig', array('editeur' => $editeur));
	}

	public function admin_suppression_editeurAction($id, Request $request)
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Editeur');
		$exemplaire = $repository->findById($id)[0];


		$em = $this->getDoctrine()->getManager();
		$em->remove($exemplaire);
		$em->flush();

		return $this->redirect($this->generateUrl('bibliotheque_admin_suppr_editeur'));
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
					->add('username', 'text', array(
							'required' => true,
							'read_only' => true,
						))
					->add('password', 'repeated', array(
						    'type' => 'password',
						    'invalid_message' => 'Les mots de passe doivent correspondre',
						    'options' => array(
						    	'required' => true,
						    	'read_only' => true,
						    ),
						    'first_options'  => array('label' => 'Mot de passe'),
						    'second_options' => array('label' => 'Mot de passe (validation)'),
						))
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

	public function admin_ajout_themeAction(Request $request)
	{
		$theme = new Theme;

		$form = $this->createFormBuilder($theme)
							->add('intitule', 'text', array('required' => true))
							->add('ajouter', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'submit spacer')))
							->getForm();

		$form->handleRequest($request);

		if($form->isValid()){

			$em = $this->getDoctrine()->getManager();
			$em->persist($theme);
			$em->flush();

			return $this->redirect($this->generateUrl('bibliotheque_admin_ajout_theme'));
		}

		return $this->render('UserBundle:Admin:admin_ajout_theme.html.twig', array('form' => $form->createView()));
	}

	public function admin_suppr_themeAction(Request $request)
	{	

		$repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Theme');
		$theme = $repository->findAllOrderedByTheme();

		return $this->render('UserBundle:Admin:admin_suppr_theme.html.twig', array('theme' => $theme));
	}

	public function admin_suppression_themeAction($id, Request $request)
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('UserBundle:Theme');
		$exemplaire = $repository->findById($id)[0];


		$em = $this->getDoctrine()->getManager();
		$em->remove($exemplaire);
		$em->flush();

		return $this->redirect($this->generateUrl('bibliotheque_admin_suppr_theme'));
	}

}