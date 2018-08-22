<?php

namespace Ctg\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Ctg\MainBundle\Entity\Utilisateurs;
use Ctg\MainBundle\Entity\Demandes;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class FormController extends Controller{
    
    /**
     * @Route("/contact/etudiant/{id}",name="ctg_contact_etudiant")
     * @Security("has_role('ROLE_GESTIONNAIRE')")
     */
    public function ContactEtudiantAction(Request $request, $id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $etudiant = new Utilisateurs();
    
        $etudiant = $repository->findOneBy(
                array('id' => $id)
            );
            
        $security = $this->get('security.token_storage');
        // On rÃ©cupÃ¨re le token
        $token = $security->getToken();
        // Si la requÃªte courante n'est pas derriÃ¨re un pare-feu, $token est null
        // Sinon, on rÃ©cupÃ¨re l'utilisateur
        $gestionnaire = $token->getUser();  
        
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlContact = $this->get('router')->generate('ctg_contact');
        $urlProcedureAttribution = $this->get('router')->generate('ctg_procedure_attribution');
        $urlListePiece = $this->get('router')->generate('ctg_liste_piece');
        $urlReglement = $this->get('router')->generate('ctg_reglement'); 
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class);
        
        $formBuilder
            ->add('Objet',TextType::class, array('required' => false))
            ->add('Message',TextareaType::class, array('required' => true))
            ->add('Envoyer',SubmitType::class)
        ;
        
        
        //On gÃ©nÃ¨re le formulaire Ã  partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requÃªte est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien RequÃªte <-> Formulaire
            // Ã€ partir de maintenant, la variable $image contient les valeurs entrÃ©es dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vÃ©rifie que les valeurs entrÃ©es sont correctes
            // (Nous verrons la validation des objets en dÃ©tail dans le prochain chapitre)
            if ($form->isValid()) {
                $objet = $form['Objet']->getData();
                $mes = $form['Message']->getData();
                
                $message = \Swift_Message::newInstance()
                    ->setSubject($objet)
                    ->setFrom($gestionnaire->getMail())
                    ->setTo($etudiant->getMail())
                    ->setCharset('utf-8')
                    ->setContentType('text/html')
                    ->setBody($mes)
                ;
                $this->get('mailer')->send($message);
                    
                 // On redirige vers la page de visualisation de l'annonce nouvellement crÃ©Ã©e
                return $this->redirectToRoute('ctg_profil', array('id' => $id));
            }
        }
        // On passe la mÃ©thode createView() du formulaire Ã  la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire:contact_etudiant.html.twig', array(
            'form' => $form->createView(),
            'urlReglement' => $urlReglement, 
            'urlBac1a5' => $urlBac1a5,
            'urlProcedureAttribution' => $urlProcedureAttribution,
            'urlListePiece' => $urlListePiece,
            'urlContact' => $urlContact,
			'etudiant' => $etudiant
            
        ));
    }

    /**
     * @Route("/contact/referent/{id}",name="ctg_contact_referent")
     * @Security("has_role('ROLE_GESTIONNAIRE')")
     */
    public function ContactReferentAction(Request $request, $id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $etudiant = new Utilisateurs();
    
        $etudiant = $repository->findOneBy(
                array('id' => $id)
            );
            
        $security = $this->get('security.token_storage');
        // On rÃ©cupÃ¨re le token
        $token = $security->getToken();
        // Si la requÃªte courante n'est pas derriÃ¨re un pare-feu, $token est null
        // Sinon, on rÃ©cupÃ¨re l'utilisateur
        $gestionnaire = $token->getUser(); 
        
        $listeRef = $repository->findBy(
                array('role'=>'ROLE_REFERENT')
            );
            
        $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
        $dem = new Demandes();
        $dem = $rep->findOneBy(
                array('demandeur' => $etudiant->getId())
            );
            
        $listeGest = $repository->findBy(
            array('role'=>'ROLE_REFERENT')
            );
        
        $listeMailRef = [];
        foreach($listeRef as $l){
            $listeMailRef = $l->getMail();
        }
        
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlContact = $this->get('router')->generate('ctg_contact');
        $urlProcedureAttribution = $this->get('router')->generate('ctg_procedure_attribution');
        $urlListePiece = $this->get('router')->generate('ctg_liste_piece');
        $urlReglement = $this->get('router')->generate('ctg_reglement'); 
        
        //passage en commission
        $dem->setEtat('Commission');
        $em = $this->getDoctrine()->getManager();
        $em->persist($dem);
        $em->flush();
        
        $message = \Swift_Message::newInstance()
            ->setSubject('Vérification de fiche')
            ->setFrom($gestionnaire->getMail())
            ->setTo($listeMailRef)
            ->setCharset('utf-8')
            ->setContentType('text/html')
            ->setBody(
                $this->renderView(
                    'Email/verifier_fiche.html.twig',
                    array(
                        'etudiant' => $etudiant,
                        'gestionnaire' => $gestionnaire,
                    )
                )
            )
        ;
    
        $this->get('mailer')->send($message);
        
        return $this->redirectToRoute('ctg_perso');
    }

    /**
     * @Route("/contact",name="ctg_contact")
     */
    public function ContactAction(Request $request){
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlContact = $this->get('router')->generate('ctg_contact');
        $urlProcedureAttribution = $this->get('router')->generate('ctg_procedure_attribution');
        $urlListePiece = $this->get('router')->generate('ctg_liste_piece');
        $urlReglement = $this->get('router')->generate('ctg_reglement'); 
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class);

        $securityContext = $this->container->get('security.authorization_checker');
	if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $security = $this->get('security.token_storage');
            // On récupère le token
            $token = $security->getToken();
            // Si la requête courante n'est pas derrière un pare-feu, $token est null
            // Sinon, on récupère l'utilisateur
            $user = $token->getUser();
	    $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
            $demandeCurrUser = $repository->findOneBy(
                array('demandeur' => $user->getId())
            );
	}
        
        $formBuilder
            ->add('Mail',TextType::class, array('required' => true))
            ->add('Nom',TextType::class, array('required' => true))
            ->add('Prenom',TextType::class, array('label' => 'Prénom','required' => true))
            ->add('Objet',TextType::class, array('required' => false))
            ->add('Message',TextareaType::class, array('required' => true))
            ->add('Envoyer',SubmitType::class)
        ;
        
        
        //On gÃ©nÃ¨re le formulaire Ã  partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requÃªte est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien RequÃªte <-> Formulaire
            // Ã€ partir de maintenant, la variable $image contient les valeurs entrÃ©es dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vÃ©rifie que les valeurs entrÃ©es sont correctes
            // (Nous verrons la validation des objets en dÃ©tail dans le prochain chapitre)
            if ($form->isValid()) {
                $nom = $form['Nom']->getData();
                $prenom = $form['Prenom']->getData();
                $objet = $form['Objet']->getData();
                $mail = $form['Mail']->getData();
                $message = $form['Message']->getData();
                
                $message = \Swift_Message::newInstance()
                    ->setSubject($objet)
                    ->setFrom($mail)
                    ->setTo('anthony_contrevilliers@yahoo.fr')
                    ->setCharset('utf-8')
                    ->setContentType('text/html')
                    ->setBody($message)
                ;
                $this->get('mailer')->send($message);
                    
                 // On redirige vers la page de visualisation de l'annonce nouvellement crÃ©Ã©e
                return $this->redirectToRoute('ctg_profil', array('id' => $id));
            }
        }
        // On passe la mÃ©thode createView() du formulaire Ã  la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire:contact.html.twig', array(
            'form' => $form->createView(),
            'urlReglement' => $urlReglement, 
            'urlBac1a5' => $urlBac1a5,
            'urlProcedureAttribution' => $urlProcedureAttribution,
            'urlListePiece' => $urlListePiece,
            'urlContact' => $urlContact,
	    'DCU' => $demandeCurrUser
            
        ));
    }
    
    /**
     * @Route("/formulaire/creation-de-compte-gestionnaire",name="ctg_form_creation_compte_gestionnaire")
     * @Security("has_role('ROLE_REFERENT')")
     */
    public function creerCompteGestionnaire(Request $request){
        $urlEnseignement_superieur = $this->get('router')->generate('ctg_enseignement_superieur');
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlLoremIpsum = $this->get('router')->generate('ctg_lorem_ipsum');
        
        $utilisateur = new Utilisateurs();
        $utilisateur->setRole('ROLE_GESTIONNAIRE');
        $utilisateur->setSalt('');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class,$utilisateur);
        
        $formBuilder
            ->add('Nom',TextType::class, array('required' => true))
            ->add('Prenom',TextType::class, array('label' => 'Prénom', 'required' => true ))
            //->add('Date_naissance',BirthdayType::class, array('required' => true))
            //->add('Rue',TextType::class, array('required' => true))
            //->add('Code_postal',TextType::class, array('required' => true))
            //->add('Ville',TextType::class, array('required' => true))
            //->add('Telephone',TextType::class, array('label' => 'Téléphone', 'required' => true ))
            ->add('Mail',TextType::class, array('required' => true))
            ->add('Password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => '',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'label' => 'Mot de passe',
                'first_options'  => array('label' => 'Choisissez votre Mot de passe'),
                'second_options' => array('label' => 'Confirmez le mot de passe'),
            ))
            //->add('Confirm_password', PasswordType::class, array('label' => 'Confirmez le mot de passe', 'required' => true ))
            ->add('Valider',SubmitType::class)
        ;
        
        
        //On gÃ©nÃ¨re le formulaire Ã  partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requÃªte est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien RequÃªte <-> Formulaire
            // Ã€ partir de maintenant, la variable $image contient les valeurs entrÃ©es dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vÃ©rifie que les valeurs entrÃ©es sont correctes
            // (Nous verrons la validation des objets en dÃ©tail dans le prochain chapitre)
            if ($form->isValid()) {
            // On enregistre notre objet $image dans la base de donnÃ©es, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($utilisateur);
                $em->flush();
                
                 // On redirige vers la page de visualisation de l'annonce nouvellement crÃ©Ã©e
                return $this->redirectToRoute('ctg_perso');
            }
        }
        // On passe la mÃ©thode createView() du formulaire Ã  la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire/Add:compte-gestionnaire.html.twig', array(
            'form' => $form->createView(),
            'urlEnsuignement_superieur' => $urlEnseignement_superieur, 
            'urlBac1a5' => $urlBac1a5,
            'urlLoremIpsum' => $urlLoremIpsum
        ));
    }
    
    /**
     * @Route("/formulaire/creation-de-compte-super_admin",name="ctg_form_creation_compte_super-admin")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function creerCompteSuperAdmin(Request $request){
        $urlEnseignement_superieur = $this->get('router')->generate('ctg_enseignement_superieur');
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlLoremIpsum = $this->get('router')->generate('ctg_lorem_ipsum');
        
        $utilisateur = new Utilisateurs();
        $utilisateur->setSalt('');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class,$utilisateur);
        
        $formBuilder
            ->add('Nom',TextType::class, array('required' => true))
            ->add('Prenom',TextType::class, array('label' => 'Prénom', 'required' => true ))
            //->add('Date_naissance',BirthdayType::class, array('required' => true))
            //->add('Rue',TextType::class, array('required' => true))
            //->add('Code_postal',TextType::class, array('required' => true))
            //->add('Ville',TextType::class, array('required' => true))
            //->add('Telephone',TextType::class, array('label' => 'Téléphone', 'required' => true ))
            ->add('Mail',TextType::class, array('required' => true))
            ->add('Password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => '',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'label' => 'Mot de passe',
                'first_options'  => array('label' => 'Choisissez votre Mot de passe'),
                'second_options' => array('label' => 'Confirmez le mot de passe'),
            ))
            ->add('Role',ChoiceType::class, array('label' => 'Rôle','choices' => array(
                'ROLE_GESTIONNAIRE' => 'ROLE_GESTIONNAIRE',
                'ROLE_REFERENT' => 'ROLE_REFERENT',
                'ROLE_ADMIN' => 'ROLE_ADMIN'
            
            ),'required' => true))
            //->add('Confirm_password', PasswordType::class, array('label' => 'Confirmez le mot de passe', 'required' => true ))
            ->add('Valider',SubmitType::class)
        ;
        
        
        //On gÃ©nÃ¨re le formulaire Ã  partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requÃªte est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien RequÃªte <-> Formulaire
            // Ã€ partir de maintenant, la variable $image contient les valeurs entrÃ©es dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vÃ©rifie que les valeurs entrÃ©es sont correctes
            // (Nous verrons la validation des objets en dÃ©tail dans le prochain chapitre)
            if ($form->isValid()) {
            // On enregistre notre objet $image dans la base de donnÃ©es, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($utilisateur);
                $em->flush();
                
                $message = \Swift_Message::newInstance()
                    ->setSubject('Votre compte est crée')
                    ->setFrom('ate@ctguyane.fr')
                    ->setTo($utilisateur->getMail())
                    ->setCharset('utf-8')
                    ->setContentType('text/html')
                    ->setBody(
                        $this->renderView(
                            'Email/compte_cree.html.twig',
                            array(
                                'nom' => $utilisateur->getNom(),
                                'prenom' => $utilisateur->getPrenom(),
                                'mail' => $utilisateur->getMail(),
                                'mdp' => $utilisateur->getPassword()
                            )
                        )
                    )
                ;
                    
                $this->get('mailer')->send($message);
                
                 // On redirige vers la page de visualisation de l'annonce nouvellement crÃ©Ã©e
                return $this->redirectToRoute('ctg_perso');
            }
        }
        // On passe la mÃ©thode createView() du formulaire Ã  la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire/Add:compte-gestionnaire.html.twig', array(
            'form' => $form->createView(),
            'urlEnsuignement_superieur' => $urlEnseignement_superieur, 
            'urlBac1a5' => $urlBac1a5,
            'urlLoremIpsum' => $urlLoremIpsum
        ));
    }
    
    /**
     * @Route("/formulaire/creation-de-compte-etudiant",name="ctg_form_creation_compte_etudiant")
     */
    public function creerCompteUtilisateurEtudiantAction(Request $request){
        $urlEnseignement_superieur = $this->get('router')->generate('ctg_enseignement_superieur');
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlLoremIpsum = $this->get('router')->generate('ctg_lorem_ipsum');
        
        $utilisateur = new Utilisateurs();
        $utilisateur->setRole('ROLE_UTILISATEUR');
        $utilisateur->setSalt('');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class,$utilisateur);
        
        $formBuilder
            ->add('Nom',TextType::class, array('required' => true))
            ->add('Prenom',TextType::class, array('label' => 'Prénom','required' => true))
            ->add('Date_naissance',BirthdayType::class, array('label' => 'Date de naissance','years' => range(date('Y')-30,date('Y')),'required' => true, 'format' => 'dd-MM-yyyy'))
            ->add('Rue',TextType::class, array('required' => true))
            ->add('Code_postal',TextType::class, array('required' => true))
            ->add('Ville',TextType::class, array('required' => true))
            ->add('Telephone',TextType::class, array('label' => 'Téléphone','required' => true))
            ->add('CNI',FileType::class, array('label' => 'Pièce d\'identité' ,'required' => false))
            //->add('Justificatif_de_domicile',FileType::class, array('required' => false))
            ->add('lieu_de_naissance',TextType::class, array('label' => 'Lieu de naissance','required' => true))
            ->add('Niveau_d_etude',ChoiceType::class, array('label' => 'Niveau d\'études','choices' => array(
                'Bac' => 'Bac',
                'Bac+1' => 'Bac+1',
                'Bac+2' => 'Bac+2',
                'Bac+3' => 'Bac+3',
                'Bac+4' => 'Bac+4',
                'Bac+5' => 'Bac+5',
                'Bac+6' => 'Bac+6',
                'Bac+7' => 'Bac+7',
                'Bac+8' => 'Bac+8'
            
            ),'required' => true))
            ->add('Dernier_diplome',TextType::class, array('label' => 'Dernier diplôme','required' => true))
            ->add('Mail',EmailType::class, array('required' => true))
            //->add('Password',  PasswordType::class, array('label' => 'Mot de passe','required' => true))
            //->add('Confirm_password', PasswordType::class, array('label' => 'Confirmez le mot de passe','required' => true))
            ->add('Password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => '',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'label' => 'Mot de passe',
                'first_options'  => array('label' => 'Choisissez votre Mot de passe'),
                'second_options' => array('label' => 'Confirmez le mot de passe'),
            ))
            //->add('RIB',TextType::class, array('label' => 'RIB' ),array('required' => false))
            //->add('Diplome',FileType::class, array('label' => 'Diplôme','required' => false))
            ->add('RIB_file',FileType::class, array('label' => 'RIB','required' => false))
            //->add('cv',FileType::class, array('label' => 'CV', 'required' => false))
            ->add('Valider',SubmitType::class)
        ;
        
        
        //On gÃ©nÃ¨re le formulaire Ã  partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requÃªte est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien RequÃªte <-> Formulaire
            // Ã€ partir de maintenant, la variable $image contient les valeurs entrÃ©es dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vÃ©rifie que les valeurs entrÃ©es sont correctes
            // (Nous verrons la validation des objets en dÃ©tail dans le prochain chapitre)
            if ($form->isValid()) {
                
                $file_1 = $utilisateur->getCni();
                if( $file_1 != null){
                    $fileName_1 = $utilisateur->getMail().'_piece_identite.'.$file_1->guessExtension(); 
                    $utilisateur->setCni($fileName_1);
                }
                
                
                $file_2 = $utilisateur->getJustificatifDeDomicile();
                if( $file_2 != null){    
                    $fileName_2 = $utilisateur->getMail().'_justificatif_domicile.'.$file_2->guessExtension(); 
                    $utilisateur->setJustificatifDeDomicile($fileName_2);
                }
                
                
                $file_3 = $utilisateur->getDiplome();
                if( $file_3 != null){
                    $fileName_3 = $utilisateur->getMail().'_diplome.'.$file_3->guessExtension(); 
                    $utilisateur->setDiplome($fileName_3);
                }
                
                
                $file_4 = $utilisateur->getRibFile();
                if( $file_4 != null){
                    $fileName_4 = $utilisateur->getMail().'_rib.'.$file_4->guessExtension(); 
                    $utilisateur->setRibFile($fileName_4); 
                }
                
                $file_5 = $utilisateur->getCv();
                if( $file_5 != null){
                    $fileName_5 = $utilisateur->getMail().'_rib.'.$file_5->guessExtension(); 
                    $utilisateur->setRibFile($fileName_5); 
                }                
            
            // On enregistre notre objet $image dans la base de donnÃ©es, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($utilisateur);
                try{
                    $em->flush();
                    
                    mkdir ($this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail(), 0755);
                    
                    if( $file_1 != null){
                        $file_1->move(
                          $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail(),
                          $fileName_1
                        );
                    }
                    
                    if( $file_2 != null){
                        $file_2->move(
                          $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail(),
                          $fileName_2
                        );
                    }
                    
                    if( $file_3 != null){
                        $file_3->move(
                          $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail(),
                          $fileName_3
                        );
                    }
                    
                    if( $file_4 != null){
                        $file_4->move(
                          $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail(),
                          $fileName_4
                        );
                    }
                    
                    if( $file_5 != null){
                        $file_5->move(
                          $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail(),
                          $fileName_5
                        );
                    }
                    
                    
                    try{
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Inscription - Bourse enseignement supérieur')
                        ->setFrom('ate@ctguyane.fr')
                        ->setTo($utilisateur->getMail())
                        ->setCharset('utf-8')
                        ->setContentType('text/html')
                        ->setBody(
                            $this->renderView(
                                'Email/enregistrement.html.twig',
                                array(
                                    'nom' => $utilisateur->getNom(),
                                    'prenom' => $utilisateur->getPrenom(),
                                    'mail' => $utilisateur->getMail()
                                )
                            )
                        )
                    ;
                    
                    $this->get('mailer')->send($message);
                    }
                    catch(\Swift_RfcComplianceException $e){
                        return $this->render('CtgMainBundle:Erreur:format_mail.html.twig');
                    }
                    
                     // On redirige vers la page de visualisation de l'annonce nouvellement crÃ©Ã©e
                    $this->addFlash(
                        'notice',
                        'Votre compte est maintenant crée. Vérifiez la réception de votre confirmation d\'inscription sur votre boite mail.'
                    );
                    $this->addFlash(
                        'notice',
                        'Si vous n\'avez pas reçu votre notification par mail pensez à vérifier vos courriers indésirables.'
                    );
                    return $this->redirectToRoute('login'); 
                }
                catch (UniqueConstraintViolationException $e){
                    return $this->render('CtgMainBundle:Erreur:duplicata.html.twig');
                }
            }
        }
        // On passe la mÃ©thode createView() du formulaire Ã  la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire/Add:compte-etudiant.html.twig', array(
            'form' => $form->createView(),
            'urlEnsuignement_superieur' => $urlEnseignement_superieur, 
            'urlBac1a5' => $urlBac1a5,
            'urlLoremIpsum' => $urlLoremIpsum
        ));
    }
    
    /**
     * @Route("/formulaire/creation-de-compte-pro",name="ctg_form_creation_compte_pro")
     */
    public function creerCompteUtilisateurProAction(Request $request){
        
        $urlEnseignement_superieur = $this->get('router')->generate('ctg_enseignement_superieur');
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlLoremIpsum = $this->get('router')->generate('ctg_lorem_ipsum');
        
        $utilisateur = new Utilisateurs();
        $utilisateur->setRole('ROLE_UTILISATEUR');
        $utilisateur->setSalt('');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class,$utilisateur);
        
        $formBuilder
            ->add('Nom',TextType::class, array('required' => true))
            ->add('Prenom',TextType::class, array('label' => 'Prénom' ), array('required' => true))
            ->add('Telephone',TextType::class, array('label' => 'Téléphone' ),array('required' => true))
            ->add('Rue',TextType::class, array('required' => true))
            ->add('Code_postal',TextType::class, array('required' => true))
            ->add('Ville',TextType::class, array('required' => true))
            ->add('CNI',FileType::class, array('label' => 'Pièce d\'identité'),array('required' => false))
            ->add('Mail',TextType::class, array('required' => true))
            ->add('Password', PasswordType::class, array('label' => 'Mot de passe' ),array('required' => true))
            ->add('Confirm_password',PasswordType::class, array('label' => 'Confirmez le mot de passe' ),array('required' => true))
            //->add('RIB',TextType::class, array('required' => true))
            ->add('RIB_file',FileType::class, array('label' => 'RIB' ), array('required' => false))
            ->add('Denomination',TextType::class, array('label' => 'Dénomination' ),array('required' => true))
            ->add('SIRET',TextType::class, array('label' => 'SIRET' ),array('required' => true))
            ->add('KBIS',FileType::class, array('label' => 'KBIS' ), array('required' => false))
            ->add('Valider',SubmitType::class)
        ;
        
        
        //On gÃ©nÃ¨re le formulaire Ã  partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requÃªte est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien RequÃªte <-> Formulaire
            // Ã€ partir de maintenant, la variable $image contient les valeurs entrÃ©es dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vÃ©rifie que les valeurs entrÃ©es sont correctes
            // (Nous verrons la validation des objets en dÃ©tail dans le prochain chapitre)
            if ($form->isValid()) {
            // On enregistre notre objet $image dans la base de donnÃ©es, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($utilisateur);
                $em->flush();
                
                 // On redirige vers la page de visualisation de l'annonce nouvellement crÃ©Ã©e
                return $this->redirectToRoute('ctg_enseignement_superieur');
            }
        }
        // On passe la mÃ©thode createView() du formulaire Ã  la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire/Add:compte-pro.html.twig', array(
            'form' => $form->createView(),
            'urlEnsuignement_superieur' => $urlEnseignement_superieur, 
            'urlBac1a5' => $urlBac1a5,
            'urlLoremIpsum' => $urlLoremIpsum
        ));
    }
    
    /**
     * @Route("/formulaire/creation-demande",name="ctg_form_creation_demande_bac1a5")
     * @Security("has_role('ROLE_UTILISATEUR')")
     */
    public function creationDemandeBac1a5Action(Request $request){
        
        $urlEnseignement_superieur = $this->get('router')->generate('ctg_enseignement_superieur');
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlLoremIpsum = $this->get('router')->generate('ctg_lorem_ipsum');
        
        $security = $this->get('security.token_storage');

        // On rÃ©cupÃ¨re le token
        $token = $security->getToken();
       
        // Si la requÃªte courante n'est pas derriÃ¨re un pare-feu, $token est null
        
        // Sinon, on rÃ©cupÃ¨re l'utilisateur
        $user = $token->getUser();
	$repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
        $demandeCurrUser = $repository->findOneBy(
                array('demandeur' => $user->getId())
            );
        
        $demande = new Demandes();
        $demande->setDemandeur($user);
        $demande->setTypeDeDemande('');
        $demande->setDateDemande(new \Datetime);
        $demande->setEtat('Envoyé');
        $demande->setComplet('En attente de confirmation');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class,$demande);
        
        $formBuilder
            ->add('Etablissement_n_1',TextType::class, array('label' => 'Etablissement N-1','required' => true))
            ->add('Adresse_n_1',TextType::class, array('label' => 'Adresse N-1','required' => true))
            ->add('Code_postal_n_1',TextType::class, array('label' => 'Code postal N-1','required' => true))
            ->add('Classe_frequentee_n_1',TextType::class, array('label' => 'Classe fréquentée N-1','required' => true))
            ->add('Ville_n_1',TextType::class, array('label' => 'Ville N-1','required' => true))
            ->add('Specialite_n_1',TextType::class, array('label' => 'Spécialité N-1','required' => true))
            ->add('Etablissement',TextType::class, array('required' => true))
            ->add('Adresse',TextType::class, array('required' => true))
            ->add('Code_postal',TextType::class, array('required' => true))
            ->add('Classe_frequentee',TextType::class, array('label' => 'Classe fréquentée','required' => true))
            ->add('Ville',TextType::class, array('required' => true))
            ->add('Specialite',TextType::class, array('label' => 'Spécialité','required' => true))
            ->add('Memoire',FileType::class, array('label' => 'Mémoire**','required' => false))
            ->add('Avis_d_imposition_N_1',FileType::class, array('label' => 'Avis d\'imposition N-1','required' => false))
            ->add('Avis_d_imposition_N',FileType::class, array('label' => 'Avis d\'imposition','required' => false))
            ->add('Notification_CROUS',FileType::class, array('label' => 'Notification CROUS','required' => false))
            ->add('Certificat_de_scolarite',FileType::class, array('label' => 'Certificat de scolarité','required' => false))
            ->add('Certificat_de_scolarite_N_1',FileType::class, array('label' => 'Certificat de scolarité N-1','required' => false))
            ->add('autre_1',FileType::class, array('label' => 'Document facutif','required' => false))
            ->add('aides',ChoiceType::class, array('label' => 'Aides sollicités (Cumulables)','required' => true, 'multiple' => true, 'expanded' => true, 'choices' => array(
                    'Installation' => 'Installation',
                    'Scolarité' => 'Scolarité',
                    'Transport' => 'Transport',
                    'Achat de matériels scolaire' => 'Achat de matériels scolaire'
                )))
            ->add('Valider',SubmitType::class)
        ;
        
        
        //On gÃ©nÃ¨re le formulaire Ã  partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requÃªte est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien RequÃªte <-> Formulaire
            // Ã€ partir de maintenant, la variable $image contient les valeurs entrÃ©es dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vÃ©rifie que les valeurs entrÃ©es sont correctes
            // (Nous verrons la validation des objets en dÃ©tail dans le prochain chapitre)
            if ($form->isValid()) {
            // On enregistre notre objet $image dans la base de donnÃ©es, par exemple
                
                $file_1 = $demande->getAvisDImpositionN1();
                if( $file_1 != null){
                    $fileName_1 = $demande->getDemandeur()->getMail().'_avis_imposition_n_1.'.$file_1->guessExtension(); 
                    $demande->setAvisDImpositionN1($fileName_1);
                }
            
                $file_2 = $demande->getAvisDImpositionN();
                if( $file_2 != null){
                    $fileName_2 = $demande->getDemandeur()->getMail().'_avis_imposition_n.'.$file_2->guessExtension(); 
                    $demande->setAvisDImpositionN($fileName_2);
                }
                
                $file_3 = $demande->getCertificatDeScolarite();
                if( $file_3 != null){
                    $fileName_3 = $demande->getDemandeur()->getMail().'_certificat_scolarite.'.$file_3->guessExtension(); 
                    $demande->setCertificatDeScolarite($fileName_3);
                }
                
                $file_4 = $demande->getCertificatDeScolariteN1();
                if( $file_4 != null){
                    $fileName_4 = $demande->getDemandeur()->getMail().'_certificat_scolarite_n_1.'.$file_4->guessExtension(); 
                    $demande->setCertificatDeScolariteN1($fileName_4);
                }                
            
                $file_5 = $demande->getMemoire();
                if( $file_5 != null){
                    $fileName_5 = $demande->getDemandeur()->getMail().'_memoire.'.$file_5->guessExtension(); 
                    $demande->setMemoire($fileName_5);
                }            
                
                $file_6 = $demande->getNotificationCROUS();
                if( $file_6 != null){
                    $fileName_6 = $demande->getDemandeur()->getMail().'_notification_crous.'.$file_6->guessExtension(); 
                    $demande->setNotificationCROUS($fileName_6);
                }
                $file_7 = $demande->getAutre1();
                if( $file_7 != null){
                    $fileName_7 = $demande->getDemandeur()->getMail().'_autre_doc_1.'.$file_7->guessExtension(); 
                    $demande->setAutre1($fileName_7);
                }
                
                $file_8 = $demande->getAutre2();
                if( $file_8 != null){
                    $fileName_8 = $demande->getDemandeur()->getMail().'_autre_doc_2.'.$file_8->guessExtension(); 
                    $demande->setAutre2($fileName_8);
                }
                
                $file_9 = $demande->getAutre3();
                if( $file_9 != null){
                    $fileName_9 = $demande->getDemandeur()->getMail().'_autre_doc_3.'.$file_9->guessExtension(); 
                    $demande->setAutre3($fileName_9);
                }               
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($demande);
                $em->flush();
                
                if( $file_1 != null){
                    $file_1->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_1
                    );
                }
                
                if( $file_2 != null){
                    $file_2->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_2
                    );
                }
                
                if( $file_3 != null){
                    $file_3->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_3
                    );
                }
                
                if( $file_4 != null){
                    $file_4->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_4
                    );
                }
                
                if( $file_5 != null){
                    $file_5->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_5
                    );
                }
                
                if( $file_6 != null){
                    $file_6->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_6
                    );
                }
                
                if( $file_7 != null){
                    $file_7->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_7
                    );
                }
                
                if( $file_8 != null){
                    $file_8->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_8
                    );
                }
                
                if( $file_9 != null){
                    $file_9->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_9
                    );
                }
                
                try{
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Inscription - Bourse enseignement supérieur')
                        ->setFrom('ate@ctguyane.fr')
                        ->setTo($user->getMail())
                        ->setCharset('utf-8')
                        ->setContentType('text/html')
                        ->setBody(
                            $this->renderView(
                                'Email/nouvelle-demande.html.twig',
                                array(
                                    'nom' => $user->getNom(),
                                    'prenom' => $user->getPrenom(),
                                    'mail' => $user->getMail()
                                )
                            )
                        )
                    ;
                    
                    $this->get('mailer')->send($message);
                }  
                catch(\Swift_RfcComplianceException $e){
                    return $this->render('CtgMainBundle:Erreur:format_mail.html.twig');
                }
                
                 // On redirige vers la page de visualisation de l'annonce nouvellement crÃ©Ã©e
                return $this->redirectToRoute('ctg_perso');
            }
        }
        // On passe la mÃ©thode createView() du formulaire Ã  la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire/Add:demande.html.twig', array(
            'form' => $form->createView(),
            'urlEnsuignement_superieur' => $urlEnseignement_superieur, 
            'urlBac1a5' => $urlBac1a5,
            'urlLoremIpsum' => $urlLoremIpsum,
	    'DCU' => $demandeCurrUser
        ));
    }
    
    /**
     * @Route("/formulaire/creation-demande2",name="ctg_form_creation_demande_bac6a8")
     * @Security("has_role('ROLE_UTILISATEUR')")
     */
    public function creationDemandeBac6a8Action(Request $request){
        
        $urlEnseignement_superieur = $this->get('router')->generate('ctg_enseignement_superieur');
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlLoremIpsum = $this->get('router')->generate('ctg_lorem_ipsum');
        
        $security = $this->get('security.token_storage');

        // On rÃ©cupÃ¨re le token
        $token = $security->getToken();
       
        // Si la requÃªte courante n'est pas derriÃ¨re un pare-feu, $token est null
        
        // Sinon, on rÃ©cupÃ¨re l'utilisateur
        $user = $token->getUser();
	$repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
        $demandeCurrUser = $repository->findOneBy(
                array('demandeur' => $user->getId())
            );
        
        $demande = new Demandes();
        $demande->setDemandeur($user);
        $demande->setTypeDeDemande('');
        $demande->setDateDemande(new \Datetime);
        $demande->setEtat('Reçue');
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class,$demande);
        
        $formBuilder
            ->add('Etablissement_n_1',TextType::class, array('label' => 'Etablissement N-1','required' => true))
            ->add('Adresse_n_1',TextType::class, array('label' => 'Adresse N-1','required' => true))
            ->add('Code_postal_n_1',TextType::class, array('label' => 'Code postal N-1','required' => true))
            ->add('Classe_frequentee_n_1',TextType::class, array('label' => 'Classe fréquentée N-1','required' => true))
            ->add('Ville_n_1',TextType::class, array('label' => 'Ville N-1','required' => true))
            ->add('Specialite_n_1',TextType::class, array('label' => 'Spécialité N-1','required' => true))
            ->add('Etablissement',TextType::class, array('required' => true))
            ->add('Adresse',TextType::class, array('required' => true))
            ->add('Code_postal',TextType::class, array('required' => true))
            ->add('Classe_frequentee',TextType::class, array('label' => 'Classe fréquentée','required' => true))
            ->add('Ville',TextType::class, array('required' => true))
            ->add('Specialite',TextType::class, array('label' => 'Spécialité','required' => true))
            ->add('Memoire',FileType::class, array('label' => 'Mémoire','required' => false))
            ->add('Avis_d_imposition_N_1',FileType::class, array('label' => 'Avis d\'imposition N-1','required' => false))
            ->add('Avis_d_imposition_N',FileType::class, array('label' => 'Avis d\'imposition','required' => false))
            ->add('Notification_CROUS',FileType::class, array('label' => 'Notification CROUS','required' => false))
            ->add('Certificat_de_scolarite',FileType::class, array('label' => 'Certificat de scolarité','required' => false))
            ->add('Certificat_de_scolarite_N_1',FileType::class, array('label' => 'Certificat de scolarité N-1','required' => false))
            ->add('autre_1',FileType::class, array('label' => 'Document facutif','required' => false))
            ->add('aides',ChoiceType::class, array('label' => 'Aides sollicités','required' => true, 'multiple' => true, 'expanded' => true, 'choices' => array(
                    'Aide forfaitaire au DOCTORANT' => 'Aide forfaitaire au DOCTORANT',
                )))
            ->add('Valider',SubmitType::class)
        ;
        
        
        //On gÃ©nÃ¨re le formulaire Ã  partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requÃªte est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien RequÃªte <-> Formulaire
            // Ã€ partir de maintenant, la variable $image contient les valeurs entrÃ©es dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vÃ©rifie que les valeurs entrÃ©es sont correctes
            // (Nous verrons la validation des objets en dÃ©tail dans le prochain chapitre)
            if ($form->isValid()) {
            // On enregistre notre objet $image dans la base de donnÃ©es, par exemple
                
                $file_1 = $demande->getAvisDImpositionN1();
                if( $file_1 != null){
                    $fileName_1 = $demande->getDemandeur()->getMail().'_avis_imposition_n_1.'.$file_1->guessExtension(); 
                    $demande->setAvisDImpositionN1($fileName_1);
                }
            
                $file_2 = $demande->getAvisDImpositionN();
                if( $file_2 != null){
                    $fileName_2 = $demande->getDemandeur()->getMail().'_avis_imposition_n.'.$file_2->guessExtension(); 
                    $demande->setAvisDImpositionN($fileName_2);
                }
                
                $file_3 = $demande->getCertificatDeScolarite();
                if( $file_3 != null){
                    $fileName_3 = $demande->getDemandeur()->getMail().'_certificat_scolarite.'.$file_3->guessExtension(); 
                    $demande->setCertificatDeScolarite($fileName_3);
                }
                
                $file_4 = $demande->getCertificatDeScolariteN1();
                if( $file_4 != null){
                    $fileName_4 = $demande->getDemandeur()->getMail().'_certificat_scolarite_n_1.'.$file_4->guessExtension(); 
                    $demande->setCertificatDeScolariteN1($fileName_4);
                }                
            
                $file_5 = $demande->getMemoire();
                if( $file_5 != null){
                    $fileName_5 = $demande->getDemandeur()->getMail().'_memoire.'.$file_5->guessExtension(); 
                    $demande->setMemoire($fileName_5);
                }            
                
                $file_6 = $demande->getNotificationCROUS();
                if( $file_6 != null){
                    $fileName_6 = $demande->getDemandeur()->getMail().'_notification_crous.'.$file_6->guessExtension(); 
                    $demande->setNotificationCROUS($fileName_6);
                }
                $file_7 = $demande->getAutre1();
                if( $file_7 != null){
                    $fileName_7 = $demande->getDemandeur()->getMail().'_autre_doc_1.'.$file_7->guessExtension(); 
                    $demande->setAutre1($fileName_7);
                }
                
                $file_8 = $demande->getAutre2();
                if( $file_8 != null){
                    $fileName_8 = $demande->getDemandeur()->getMail().'_autre_doc_2.'.$file_8->guessExtension(); 
                    $demande->setAutre2($fileName_8);
                }
                
                $file_9 = $demande->getAutre3();
                if( $file_9 != null){
                    $fileName_9 = $demande->getDemandeur()->getMail().'_autre_doc_3.'.$file_9->guessExtension(); 
                    $demande->setAutre3($fileName_9);
                }               
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($demande);
                $em->flush();
                
                if( $file_1 != null){
                    $file_1->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_1
                    );
                }
                
                if( $file_2 != null){
                    $file_2->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_2
                    );
                }
                
                if( $file_3 != null){
                    $file_3->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_3
                    );
                }
                
                if( $file_4 != null){
                    $file_4->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_4
                    );
                }
                
                if( $file_5 != null){
                    $file_5->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_5
                    );
                }
                
                if( $file_6 != null){
                    $file_6->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_6
                    );
                }
                
                if( $file_7 != null){
                    $file_7->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_7
                    );
                }
                
                if( $file_8 != null){
                    $file_8->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_8
                    );
                }
                
                if( $file_9 != null){
                    $file_9->move(
                      $this->getParameter('document_directory').'/'.$demande->getDemandeur()->getId().'_'.$demande->getDemandeur()->getMail(),
                      $fileName_9
                    );
                }
                try{
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Inscription - Bourse enseignement supÃ©rieur')
                        ->setFrom('ate@ctguyane.fr')
                        ->setTo($user->getMail())
                        ->setCharset('utf-8')
                        ->setContentType('text/html')
                        ->setBody(
                            $this->renderView(
                                'Email/nouvelle-demande.html.twig',
                                array(
                                    'nom' => $user>getNom(),
                                    'prenom' => $user->getPrenom(),
                                    'mail' => $user->getMail()
                                )
                            )
                        )
                    ;
                    
                    $this->get('mailer')->send($message);
                }
                catch(\Swift_RfcComplianceException $e){
                    return $this->render('CtgMainBundle:Erreur:format_mail.html.twig');
                }
                
                 // On redirige vers la page de visualisation de l'annonce nouvellement crÃ©Ã©e
                return $this->redirectToRoute('ctg_perso');
            }
        }
        // On passe la mÃ©thode createView() du formulaire Ã  la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire/Add:demande2.html.twig', array(
            'form' => $form->createView(),
            'urlEnsuignement_superieur' => $urlEnseignement_superieur, 
            'urlBac1a5' => $urlBac1a5,
            'urlLoremIpsum' => $urlLoremIpsum,
    	    'DCU' => $demandeCurrUser
        ));
    }
    
    /**
     * @Route("/formulaire/modification-compte/{id}",name="ctg_form_modification_compte")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function modifierInfosPersoAction(Request $request, $id){
        $security = $this->get('security.token_storage');
        // On rÃ©cupÃ¨re le token
        $token = $security->getToken();
        // Si la requÃªte courante n'est pas derriÃ¨re un pare-feu, $token est null
        // Sinon, on rÃ©cupÃ¨re l'utilisateur
        $curUser = $token->getUser();    

        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class,$user);
        $formBuilder
            ->add('Nom',TextType::class, array('required' => true))
            ->add('Prenom',TextType::class, array('label' => 'Prénom','required' => true))
            ->add('Date_naissance',BirthdayType::class, array('label' => 'Date de naissance','years' => range(1970,date('Y')),'required' => true, 'format' => 'dd-MM-yyyy'))
            ->add('Rue',TextType::class, array('required' => true))
            ->add('Code_postal',TextType::class, array('required' => true))
            ->add('Ville',TextType::class, array('required' => true))
            ->add('Telephone',TextType::class, array('label' => 'Téléphone','required' => true))
            ->add('lieu_de_naissance',TextType::class, array('label' => 'Lieu de naissance','required' => true))
            ->add('Niveau_d_etude',ChoiceType::class, array('label' => 'Niveau d\'études','choices' => array(
                'Bac' => 'Bac',
                'Bac+1' => 'Bac+1',
                'Bac+2' => 'Bac+2',
                'Bac+3' => 'Bac+3',
                'Bac+4' => 'Bac+4',
                'Bac+5' => 'Bac+5',
                'Bac+6' => 'Bac+6',
                'Bac+7' => 'Bac+7',
                'Bac+8' => 'Bac+8'
            
            ),'required' => true))
            ->add('Mail',TextType::class, array('required' => true))
            ->add('Valider',SubmitType::class)
        ;
        /*if(strcmp($curUser.getRole(),'ROLE_GESTIONNAIRE') == 0 || strcmp($curUser.getRole(),'ROLE_REFERENT')){
            $formBuilder
                ->add('Etat',ChoiceType::class, array('label' => 'Etat','choices' => array(
                    'Envoyée' => 'Envoyée',
                    'Reçue' => 'Reçue',
                    'Instruction' => 'Instruction',
                    'Commission' => 'Commission',
                    'Commission Validée' => 'Commission Vaidée',
                    'Rejetée' => 'Rejetée',
                    'Paiment' => 'Paiment',
                    'Clôturée' => 'Clôturée'
                
                ),'required' => true))
                ->add('Complet',ChoiceType::class, array('label' => 'Etat','choices' => array(
                    'En attente de confirmation' => 'En attente de confirmation',
                    'Complet' => 'Complet',
                    'Non Complet' => 'Non Complet'
                
                ),'required' => true))
            ;
        }*/
        
        //On gÃ©nÃ¨re le formulaire Ã  partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requÃªte est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien RequÃªte <-> Formulaire
            // Ã€ partir de maintenant, la variable $image contient les valeurs entrÃ©es dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vÃ©rifie que les valeurs entrÃ©es sont correctes
            // (Nous verrons la validation des objets en dÃ©tail dans le prochain chapitre)
            if ($form->isValid()) {
            // On enregistre notre objet $image dans la base de donnÃ©es, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                
                 // On redirige vers la page de visualisation de l'annonce nouvellement crÃ©Ã©e
                return $this->redirectToRoute('ctg_perso');
            }
        }
        // On passe la mÃ©thode createView() du formulaire Ã  la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire/Update:compte.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
        
    }
    
    /**
     * @Route("/formulaire/modification-demande_bac1a5/{id}",name="ctg_form_modification_demande_bac1a5")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function modifierDemandeBac1a5Action(Request $request, $id){
        $security = $this->get('security.token_storage');
        // On rÃ©cupÃ¨re le token
        $token = $security->getToken();
        // Si la requÃªte courante n'est pas derriÃ¨re un pare-feu, $token est null
        // Sinon, on rÃ©cupÃ¨re l'utilisateur
        $curUser = $token->getUser();    

        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
        $dem = $rep->findOneBy(
                array('demandeur' => $user->getId())
            );
            

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class,$dem);
        $formBuilder
            ->add('Etablissement_n_1',TextType::class, array('label' => 'Etablissement N-1','required' => true))
            ->add('Adresse_n_1',TextType::class, array('label' => 'Adresse N-1','required' => true))
            ->add('Code_postal_n_1',TextType::class, array('label' => 'Code postal N-1','required' => true))
            ->add('Classe_frequentee_n_1',TextType::class, array('label' => 'Classe fréquentée N-1','required' => true))
            ->add('Ville_n_1',TextType::class, array('label' => 'Ville N-1','required' => true))
            ->add('Specialite_n_1',TextType::class, array('label' => 'Spécialité N-1','required' => true))
            ->add('Etablissement',TextType::class, array('required' => true))
            ->add('Adresse',TextType::class, array('required' => true))
            ->add('Code_postal',TextType::class, array('required' => true))
            ->add('Classe_frequentee',TextType::class, array('label' => 'Classe fréquentée','required' => true))
            ->add('Ville',TextType::class, array('required' => true))
            ->add('Specialite',TextType::class, array('label' => 'Spécialité','required' => true))
            ->add('aides',ChoiceType::class, array('label' => 'Aides sollicités (Cumulables)','required' => true, 'multiple' => true, 'expanded' => true, 'choices' => array(
                    'Installation' => 'Installation',
                    'Scolarité' => 'Scolarité',
                    'Transport' => 'Transport',
                    'Achat de matériels scolaire' => 'Achat de matériels scolaire'
                )))
            ->add('Valider',SubmitType::class)
        ;

        /*if(strcmp($curUser->getRole(),'ROLE_GESTIONNAIRE') == 0 || strcmp($curUser->getRole(),'ROLE_REFERENT') == 0){
            $formBuilder
                ->add('Etat',ChoiceType::class, array('label' => 'Etat','choices' => array(
                    'Envoyée' => 'Envoyée',
                    'Reçue' => 'Reçue',
                    'Instruction' => 'Instruction',
                    'Commission' => 'Commission',
                    'Commission Validée' => 'Commission Vaidée',
                    'Rejetée' => 'Rejetée',
                    'Paiment' => 'Paiment',
                    'Clôturée' => 'Clôturée'
                
                ),'required' => true))
                ->add('Complet',ChoiceType::class, array('label' => 'Complet','choices' => array(
                    'En attente de confirmation' => 'En attente de confirmation',
                    'Complet' => 'Complet',
                    'Non Complet' => 'Non Complet'
                
                ),'required' => true))
            ;
        }*/
        
        //On gÃ©nÃ¨re le formulaire Ã  partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requÃªte est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien RequÃªte <-> Formulaire
            // Ã€ partir de maintenant, la variable $image contient les valeurs entrÃ©es dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vÃ©rifie que les valeurs entrÃ©es sont correctes
            // (Nous verrons la validation des objets en dÃ©tail dans le prochain chapitre)
            if ($form->isValid()) {
            // On enregistre notre objet $image dans la base de donnÃ©es, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($dem);
                $em->flush();
                
                 // On redirige vers la page de visualisation de l'annonce nouvellement crÃ©Ã©e
                return $this->redirectToRoute('ctg_perso');
            }
        }
        // On passe la mÃ©thode createView() du formulaire Ã  la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire/Update:demande-bac1a5.html.twig', array(
            'form' => $form->createView(),
            'dem' => $dem,
            'user' => $user
        ));
        
    }
    
    /**
     * @Route("/formulaire/modification-demande_bac6a8/{id}",name="ctg_form_modification_demande_bac6a8")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function modifierDemandeBac6a8Action(Request $request, $id){
        $security = $this->get('security.token_storage');
        // On rÃ©cupÃ¨re le token
        $token = $security->getToken();
        // Si la requÃªte courante n'est pas derriÃ¨re un pare-feu, $token est null
        // Sinon, on rÃ©cupÃ¨re l'utilisateur
        $curUser = $token->getUser();    

        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
        $dem = $rep->findOneBy(
                array('demandeur' => $user->getId())
            );

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class,$dem);
        $formBuilder
            ->add('Etablissement_n_1',TextType::class, array('label' => 'Etablissement N-1','required' => true))
            ->add('Adresse_n_1',TextType::class, array('label' => 'Adresse N-1','required' => true))
            ->add('Code_postal_n_1',TextType::class, array('label' => 'Code postal N-1','required' => true))
            ->add('Classe_frequentee_n_1',TextType::class, array('label' => 'Classe fréquentée N-1','required' => true))
            ->add('Ville_n_1',TextType::class, array('label' => 'Ville N-1','required' => true))
            ->add('Specialite_n_1',TextType::class, array('label' => 'Spécialité N-1','required' => true))
            ->add('Etablissement',TextType::class, array('required' => true))
            ->add('Adresse',TextType::class, array('required' => true))
            ->add('Code_postal',TextType::class, array('required' => true))
            ->add('Classe_frequentee',TextType::class, array('label' => 'Classe fréquentée','required' => true))
            ->add('Ville',TextType::class, array('required' => true))
            ->add('Specialite',TextType::class, array('label' => 'Spécialité','required' => true))
            ->add('aides',ChoiceType::class, array('label' => 'Aides sollicités','required' => true, 'multiple' => true, 'expanded' => true, 'choices' => array(
                    'Aide forfaitaire au DOCTORANT' => 'Aide forfaitaire au DOCTORANT',
                )))
            ->add('Valider',SubmitType::class)
        ;
        
        /*if(strcmp($curUser->getRole(),'ROLE_GESTIONNAIRE') == 0 || strcmp($curUser->getRole(),'ROLE_REFERENT') == 0){
            $formBuilder
                ->add('Etat',ChoiceType::class, array('label' => 'Etat','choices' => array(
                    'Envoyée' => 'Envoyée',
                    'Reçue' => 'Reçue',
                    'Instruction' => 'Instruction',
                    'Commission' => 'Commission',
                    'Commission Validée' => 'Commission Vaidée',
                    'Rejetée' => 'Rejetée',
                    'Paiment' => 'Paiment',
                    'Clôturée' => 'Clôturée'
                
                ),'required' => true))
                ->add('Complet',ChoiceType::class, array('label' => 'Complet','choices' => array(
                    'En attente de confirmation' => 'En attente de confirmation',
                    'Complet' => 'Complet',
                    'Non Complet' => 'Non Complet'
                
                ),'required' => true))
            ;
        }*/
        //On gÃ©nÃ¨re le formulaire Ã  partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requÃªte est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien RequÃªte <-> Formulaire
            // Ã€ partir de maintenant, la variable $image contient les valeurs entrÃ©es dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vÃ©rifie que les valeurs entrÃ©es sont correctes
            // (Nous verrons la validation des objets en dÃ©tail dans le prochain chapitre)
            if ($form->isValid()) {
            // On enregistre notre objet $image dans la base de donnÃ©es, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($dem);
                $em->flush();
                
                 // On redirige vers la page de visualisation de l'annonce nouvellement crÃ©Ã©e
                return $this->redirectToRoute('ctg_perso');
            }
        }
        // On passe la mÃ©thode createView() du formulaire Ã  la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire/Update:demande-bac6a8.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
            'dem' => $dem
        ));
        
    }
    
    /**
     * @Route("/formulaire/fiche/{id}",name="ctg_form_fiche")
     * @Security("has_role('ROLE_GESTIONNAIRE')")
     */ 
    public function ficheAction(Request $request, $id){
        if($request->isMethod('POST')){
            
            $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
            $user = new Utilisateurs();
        
            $user = $repository->findOneBy(
                    array('id' => $id)
                );
            
            $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
            $dem = new Demandes();
            $dem = $rep->findOneBy(
                    array('demandeur' => $user->getId())
                );
            
            $security = $this->get('security.token_storage');
            // On rÃ©cupÃ¨re le token
            $token = $security->getToken();
            // Si la requÃªte courante n'est pas derriÃ¨re un pare-feu, $token est null
            // Sinon, on rÃ©cupÃ¨re l'utilisateur
            $curUser = $token->getUser(); 
            
            if(strcmp($dem->getEtat(),'Validée') == 0){
                $dem->setEtat('Paiement');
                $em->persist($dem);
                $em->flush();
            }
            else if(strcmp($dem->getEtat(),'Paiement') == 0 || strcmp($dem->getEtat(),'Rejetée') == 0 ){
                $dem->setEtat('Clôturée');
                $em->persist($dem);
                $em->flush();
            }
            else{
                $dateComplet = $request->request->get('dateComplet');
                $dateInstruction = $request->request->get('dateInstruction');
                $echelon = $request->request->get('echelon');
                $aidesInstallation = $request->request->get('aidesInstallation');
                $aidesScolarite = $request->request->get('aidesScolarite');
                $aidesTransport = $request->request->get('aidesTransport');
                $aidesAchatMaterielScolaire = $request->request->get('aidesAchatMaterielScolaire');
                $revenuReference = $request->request->get('revenuReference');
                $referenceCROUS = $request->request->get('referenceCROUS');
                $montant = $request->request->get('montant');
                $rejet = $request->request->get('rejet');
                $observation = $request->request->get('observation');
                $radio = $request->request->get('group1');
                $lieuEtude = $request->request->get('lieuEtude');
                
                /*echo $dateNaissance;
                echo $niveauDEtude;
                echo $etablissement;
                echo $lieuDeNaissance;
                echo $echelon;
                echo $aidesInstallation;
                echo $aidesScolarite;
                echo $aidesTransport;
                echo $aidesAchatMaterielScolaire;
                echo $revenuReference;
                echo $referenceCROUS;
                echo $montant;
                echo $rejet;
                echo $observation;*/
                    
                $tab = [];
                if($aidesInstallation != null){
                    $tab[] = $aidesInstallation;
                }
                if($aidesScolarite != null){
                    $tab[] = $aidesScolarite;
                }
                if($aidesTransport != null){
                    $tab[] = $aidesTransport;
                }
                if($aidesAchatMaterielScolaire != null){
                    $tab[] = $aidesAchatMaterielScolaire;
                }
                
                if($dateComplet != null){
                    $dem->setEtat('Complet');
                }
                if($dateComplet != null || strcmp($dateComplet,'') != 0){
                    $dem->setDateComplet(date_create($dateComplet));
                }
                if($dateInstruction != null || strcmp($dateInstruction,'') != 0){
                    $dem->setDateComplet(date_create($dateInstruction));
                }
                if($dateComplet != null){
                  $dem->setDateComplet(date_create($dateComplet));  
                }
                if($dateInstruction != null){
                  $dem->setDateInstruction(date_create($dateInstruction));  
                }
                $dem->setBourseEchelon($echelon);
                $dem->setAides($tab);
                $dem->setRevenuReference($revenuReference);
                $dem->setReferenceCROUS($referenceCROUS);
                if(strcmp($radio,'r-montant') == 0){
                    $dem->setMontant($montant);
                    $dem->setRejet(null);
                }
                else{
                    $dem->setMontant(null);
                    $dem->setRejet($rejet);
                    $lieuEtude= null;
                }
                $dem->setLieuEtude($lieuEtude);
                $dem->setObservations($observation);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($dem);
                $em->flush();
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                
                return $this->redirectToRoute('ctg_profil', array('id' => $id));   
            }
        }
        return $this->redirectToRoute('ctg_perso');
    }
    
    /**
     * @Route("/fiche/valider/{id}",name="ctg_fiche_valider")
     * @Security("has_role('ROLE_GESTIONNAIRE')")
     */
    public function ValiderOrRejeterAction(Request $request, $id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $etudiant = new Utilisateurs();
    
        $etudiant = $repository->findOneBy(
                array('id' => $id)
            );
            
        $security = $this->get('security.token_storage');
        // On rÃ©cupÃ¨re le token
        $token = $security->getToken();
        // Si la requÃªte courante n'est pas derriÃ¨re un pare-feu, $token est null
        // Sinon, on rÃ©cupÃ¨re l'utilisateur
        $gestionnaire = $token->getUser(); 
        
        $listeRef = $repository->findBy(
                array('role'=>'ROLE_REFERENT')
            );
            
        $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
        $dem = new Demandes();
        $dem = $rep->findOneBy(
                array('demandeur' => $etudiant->getId())
            );
        
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlContact = $this->get('router')->generate('ctg_contact');
        $urlProcedureAttribution = $this->get('router')->generate('ctg_procedure_attribution');
        $urlListePiece = $this->get('router')->generate('ctg_liste_piece');
        $urlReglement = $this->get('router')->generate('ctg_reglement'); 
        
        if($dem->getRejet() != null){
            $dem->setEtat('Rejetée');
            $em = $this->getDoctrine()->getManager();
            $em->persist($dem);
            $em->flush();   
        }
        else{
            $dem->setEtat('Validée');
            $em = $this->getDoctrine()->getManager();
            $em->persist($dem);
            $em->flush();   
        }
        
        /*$message = \Swift_Message::newInstance()
            ->setSubject('Vérification de fiche')
            ->setFrom($gestionnaire->getMail())
            ->setTo($listeMailRef)
            ->setCharset('utf-8')
            ->setContentType('text/html')
            ->setBody(
                $this->renderView(
                    'Email/verifier_fiche.html.twig',
                    array(
                        'etudiant' => $etudiant,
                        'gestionnaire' => $gestionnaire,
                    )
                )
            )
        ;
    
        $this->get('mailer')->send($message);*/
        
        return $this->redirectToRoute('ctg_perso');
    }
    
    /**
     * @Route("/fiche/paiement/{id}",name="ctg_fiche_paiement")
     * @Security("has_role('ROLE_GESTIONNAIRE')")
     */
    public function PaiementAction(Request $request, $id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $etudiant = new Utilisateurs();
    
        $etudiant = $repository->findOneBy(
                array('id' => $id)
            );
            
        $security = $this->get('security.token_storage');
        // On rÃ©cupÃ¨re le token
        $token = $security->getToken();
        // Si la requÃªte courante n'est pas derriÃ¨re un pare-feu, $token est null
        // Sinon, on rÃ©cupÃ¨re l'utilisateur
        $gestionnaire = $token->getUser(); 
        
        $listeRef = $repository->findBy(
                array('role'=>'ROLE_REFERENT')
            );
            
        $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
        $dem = new Demandes();
        $dem = $rep->findOneBy(
                array('demandeur' => $etudiant->getId())
            );
        
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlContact = $this->get('router')->generate('ctg_contact');
        $urlProcedureAttribution = $this->get('router')->generate('ctg_procedure_attribution');
        $urlListePiece = $this->get('router')->generate('ctg_liste_piece');
        $urlReglement = $this->get('router')->generate('ctg_reglement'); 
        
        $dem->setEtat('Paiement');
        $em = $this->getDoctrine()->getManager();
        $em->persist($dem);
        $em->flush();   
        /*$message = \Swift_Message::newInstance()
            ->setSubject('Vérification de fiche')
            ->setFrom($gestionnaire->getMail())
            ->setTo($listeMailRef)
            ->setCharset('utf-8')
            ->setContentType('text/html')
            ->setBody(
                $this->renderView(
                    'Email/verifier_fiche.html.twig',
                    array(
                        'etudiant' => $etudiant,
                        'gestionnaire' => $gestionnaire,
                    )
                )
            )
        ;
    
        $this->get('mailer')->send($message);*/
        
        return $this->redirectToRoute('ctg_perso');
    }
    
    /**
     * @Route("/fiche/cloturer/{id}",name="ctg_fiche_cloturer")
     * @Security("has_role('ROLE_GESTIONNAIRE')")
     */
    public function CloturerAction(Request $request, $id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $etudiant = new Utilisateurs();
    
        $etudiant = $repository->findOneBy(
                array('id' => $id)
            );
            
        $security = $this->get('security.token_storage');
        // On rÃ©cupÃ¨re le token
        $token = $security->getToken();
        // Si la requÃªte courante n'est pas derriÃ¨re un pare-feu, $token est null
        // Sinon, on rÃ©cupÃ¨re l'utilisateur
        $gestionnaire = $token->getUser(); 
        
        $listeRef = $repository->findBy(
                array('role'=>'ROLE_REFERENT')
            );
            
        $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
        $dem = new Demandes();
        $dem = $rep->findOneBy(
                array('demandeur' => $etudiant->getId())
            );
        
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlContact = $this->get('router')->generate('ctg_contact');
        $urlProcedureAttribution = $this->get('router')->generate('ctg_procedure_attribution');
        $urlListePiece = $this->get('router')->generate('ctg_liste_piece');
        $urlReglement = $this->get('router')->generate('ctg_reglement'); 
        
        $dem->setEtat('Clôturée');
        $em = $this->getDoctrine()->getManager();
        $em->persist($dem);
        $em->flush();   
        /*$message = \Swift_Message::newInstance()
            ->setSubject('Vérification de fiche')
            ->setFrom($gestionnaire->getMail())
            ->setTo($listeMailRef)
            ->setCharset('utf-8')
            ->setContentType('text/html')
            ->setBody(
                $this->renderView(
                    'Email/verifier_fiche.html.twig',
                    array(
                        'etudiant' => $etudiant,
                        'gestionnaire' => $gestionnaire,
                    )
                )
            )
        ;
    
        $this->get('mailer')->send($message);*/
        
        return $this->redirectToRoute('ctg_perso');
    }
    
    /**
     * @Route("/modification-mot-de-passe",name="ctg_modification_mdp")
     */
    public function modificationMotDePasseAction(Request $request){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $listeUser =$repository->findAll();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class);
        
        foreach($listeUser as $u){
            $listeMail[] = $u->getMail();
        }
    
        $formBuilder
            ->add('Mail',EMailType::class, array('required' => true))
            ->add('ForgetPass', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => '',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'label' => 'Mot de passe',
                'first_options'  => array('label' => 'Nouveau mot de passe'),
                'second_options' => array('label' => 'Confirmez le mot de passe'),
            ))
            ->add('Modifier',SubmitType::class)
        ;
        
        
        //On gÃ©nÃ¨re le formulaire Ã  partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requÃªte est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien RequÃªte <-> Formulaire
            // Ã€ partir de maintenant, la variable $image contient les valeurs entrÃ©es dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vÃ©rifie que les valeurs entrÃ©es sont correctes
            // (Nous verrons la validation des objets en dÃ©tail dans le prochain chapitre)
            if ($form->isValid()) {
                $mail = $form['Mail']->getData();
                $pass= $form['ForgetPass']->getData();
                $objet = 'Modification de votre mot de passe';
                
                $isMail = false;
                foreach($listeMail as $m){
                    if(strcmp($m,$mail) == 0){
                        $isMail = true;
                        break;
                    }
                }
                
                if($isMail){
                    
                    $user = new Utilisateurs();
                    $user = $repository->findOneBy(
                        array('mail' => $mail)
                        )
                    ;
                    
                    $message = \Swift_Message::newInstance()
                        ->setSubject($objet)
                        ->setFrom('ate@ctguyane.fr')
                        ->setTo($mail)
                        ->setCharset('utf-8')
                        ->setContentType('text/html')
                        ->setBody(
                            $this->renderView(
                                'Email/mdp-modification.html.twig',
                                array(
                                    'utilisateur' => $user
                                )
                            )
                        )
                    ;
                    $this->get('mailer')->send($message);
                
                    $user->setForgetPass($pass);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    
                    $this->addFlash(
                        'notice',
                        'Consultez votre boîte mail afin de suivre la procédure de modification de mot de passe avant de pouvoir vous reconnecter.'
                    );    
    
                    return $this->redirectToRoute('login');
                        
                     // On redirige vers la page de visualisation de l'annonce nouvellement crÃ©Ã©e
                    //return $this->redirectToRoute('#### a définir ####');
                }
                else{
                    $this->addFlash(
                        'notice',
                        'aucun compte n\'a été créer avec l\'email que vous avez renseigné. Vérifiez l\'exactidude du mail que vous avez renseigné ou créez vous un compte'
                    );  
                    return $this->redirectToRoute('ctg_modification_mdp');
                }
            }
        }
        // On passe la mÃ©thode createView() du formulaire Ã  la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire/Update:changer-mdp.html.twig', array(
            'form' => $form->createView(),
            
        ));
    }
    
    /**
     * @route("/modification-mot-de-passe-f/{id}",name="ctg_modification_mdp_f")
     */
    public function modificationMotDePasseActionF(Request $request, $id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = $repository->find($id);
        if($user->getForgetPass() != null){
            $user->setPassword($user->getForgetPass());
            $user->setForgetPass(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
        }
        else{
            return $this->redirectToRoute('ctg_education');
        }
    }
}

