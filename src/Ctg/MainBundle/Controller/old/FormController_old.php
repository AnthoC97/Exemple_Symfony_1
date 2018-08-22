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

class FormController extends Controller{
    
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
        
        $formBuilder
            ->add('Mail',TextType::class, array('required' => true))
            ->add('Nom',TextType::class, array('required' => true))
            ->add('Prenom',TextType::class, array('label' => 'Prénom','required' => true))
            ->add('Objet',TextType::class, array('required' => false))
            ->add('Message',TextareaType::class, array('required' => true))
            ->add('Envoyer',SubmitType::class)
        ;
        
        
        //On génère le formulaire à partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requête est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $image contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {
                $nom = $form['Nom']->getData();
                $prenom = $form['Prenom']->getData();
                $objet = $form['Objet']->getData();
                $mail = $form['Mail']->getData();
                $message = $form['Message']->getData();
                
                $message = \Swift_Message::newInstance()
                    ->setSubject($objet." ".$mail)
                    ->setFrom('noreply@ctguyane.fr')
                    ->setTo('anthony_contrevilliers@yahoo.fr')
                    ->setCharset('utf-8')
                    ->setContentType('text/html')
                    ->setBody($message)
                ;
                $this->get('mailer')->send($message);
                    
                 // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('ctg_contact');
            }
        }
        // On passe la méthode createView() du formulaire à la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire:contact.html.twig', array(
            'form' => $form->createView(),
            'urlReglement' => $urlReglement, 
            'urlBac1a5' => $urlBac1a5,
            'urlProcedureAttribution' => $urlProcedureAttribution,
            'urlListePiece' => $urlListePiece,
            'urlContact' => $urlContact
            
        ));
    }
    
    /**
     * @Route("/formulaire/creation-de-compte-gestionnaire",name="ctg_form_creation_compte_gestionnaire")
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
            ->add('Date_naissance',BirthdayType::class, array('required' => true))
            ->add('Rue',TextType::class, array('required' => true))
            ->add('Code_postal',TextType::class, array('required' => true))
            ->add('Ville',TextType::class, array('required' => true))
            ->add('Telephone',TextType::class, array('label' => 'Téléphone', 'required' => true ))
            ->add('Mail',TextType::class, array('required' => true))
            ->add('Password',  PasswordType::class, array('label' => 'Mot de passe', 'required' => true ))
            ->add('Confirm_password', PasswordType::class, array('label' => 'Confirmez le mot de passe', 'required' => true ))
            ->add('Valider',SubmitType::class)
        ;
        
        
        //On génère le formulaire à partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requête est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $image contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {
            // On enregistre notre objet $image dans la base de données, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($utilisateur);
                $em->flush();
                
                 // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('ctg_enseignement_superieur');
            }
        }
        // On passe la méthode createView() du formulaire à la vue
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
            ->add('Date_naissance',BirthdayType::class, array('label' => 'Date de naissance','years' => range(1970,date('Y')),'required' => true))
            ->add('Rue',TextType::class, array('required' => true))
            ->add('Code_postal',TextType::class, array('required' => true))
            ->add('Ville',TextType::class, array('required' => true))
            ->add('Telephone',TextType::class, array('label' => 'Téléphone','required' => true))
            ->add('CNI',FileType::class, array('label' => 'Pièce d\'identité' ,'required' => false))
            ->add('Justificatif_de_domicile',FileType::class, array('required' => false))
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
            ->add('Mail',TextType::class, array('required' => true))
            ->add('Password',  PasswordType::class, array('label' => 'Mot de passe','required' => true))
            ->add('Confirm_password', PasswordType::class, array('label' => 'Confirmez le mot de passe','required' => true))
            //->add('RIB',TextType::class, array('label' => 'RIB' ),array('required' => false))
            ->add('Diplome',FileType::class, array('label' => 'Diplôme','required' => false))
            ->add('RIB_file',FileType::class, array('label' => 'RIB','required' => false))
            ->add('cv',FileType::class, array('label' => 'CV', 'required' => false))
            ->add('Valider',SubmitType::class)
        ;
        
        
        //On génère le formulaire à partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requête est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $image contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
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
            
            // On enregistre notre objet $image dans la base de données, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($utilisateur);
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
                
                
                
                $message = \Swift_Message::newInstance()
                    ->setSubject('Inscription - Bourse enseignement supérieur')
                    ->setFrom('noreply@ctguyane.fr')
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
                
                 // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('ctg_enseignement_superieur');
            }
        }
        // On passe la méthode createView() du formulaire à la vue
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
        
        
        //On génère le formulaire à partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requête est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $image contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {
            // On enregistre notre objet $image dans la base de données, par exemple
                $em = $this->getDoctrine()->getManager();
                $em->persist($utilisateur);
                $em->flush();
                
                 // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('ctg_enseignement_superieur');
            }
        }
        // On passe la méthode createView() du formulaire à la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire/Add:compte-pro.html.twig', array(
            'form' => $form->createView(),
            'urlEnsuignement_superieur' => $urlEnseignement_superieur, 
            'urlBac1a5' => $urlBac1a5,
            'urlLoremIpsum' => $urlLoremIpsum
        ));
    }
    
    /**
     * @Route("/formulaire/creation-demande",name="ctg_form_creation_demande")
     * @Security("has_role('ROLE_UTILISATEUR')")
     */
    public function creationDemandeAction(Request $request){
        
        $urlEnseignement_superieur = $this->get('router')->generate('ctg_enseignement_superieur');
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlLoremIpsum = $this->get('router')->generate('ctg_lorem_ipsum');
        
        $security = $this->get('security.token_storage');

        // On récupère le token
        $token = $security->getToken();
       
        // Si la requête courante n'est pas derrière un pare-feu, $token est null
        
        // Sinon, on récupère l'utilisateur
        $user = $token->getUser();
        
        $demande = new Demandes();
        $demande->setDemandeur($user);
        $demande->setTypeDeDemande('');
        $demande->setDateDemande(new \Datetime);
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
            ->add('autre_1',FileType::class, array('label' => 'Autre document','required' => false))
            ->add('autre_2',FileType::class, array('label' => 'Autre document','required' => false))
            ->add('autre_3',FileType::class, array('label' => 'Autre document','required' => false))
            ->add('aides',ChoiceType::class, array('label' => 'Aides sollicités','required' => true, 'multiple' => true, 'expanded' => true, 'choices' => array(
                    'Installation' => 'Installation',
                    'Scolarité' => 'Scolarité',
                    'Transport' => 'Transport',
                    'Achat de matériels scolaire' => 'Achat de matériels scolaire'
                )))
            ->add('Valider',SubmitType::class)
        ;
        
        
        //On génère le formulaire à partir du formBuilder
        $form = $formBuilder->getForm();
        
        // Si la requête est en POST
        if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $image contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {
            // On enregistre notre objet $image dans la base de données, par exemple
                
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
                    echo $fileName_1;
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
                
                $message = \Swift_Message::newInstance()
                    ->setSubject('Inscription - Bourse enseignement supérieur')
                    ->setFrom('noreply@ctguyane.fr')
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
                
                 // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('ctg_perso');
            }
        }
        // On passe la méthode createView() du formulaire à la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('CtgMainBundle:Formulaire/Add:demande.html.twig', array(
            'form' => $form->createView(),
            'urlEnsuignement_superieur' => $urlEnseignement_superieur, 
            'urlBac1a5' => $urlBac1a5,
            'urlLoremIpsum' => $urlLoremIpsum
        ));
    }
}
