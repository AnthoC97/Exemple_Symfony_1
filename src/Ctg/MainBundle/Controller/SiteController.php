<?php

namespace Ctg\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Ctg\MainBundle\Entity\Utilisateurs;
use Ctg\MainBundle\Entity\Demandes;

class SiteController extends Controller
{
    /**
     * @Route("/aides", name="ctg_aides")
     */
    public function aideAction(){
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

        $content =  $this->get('templating')->render('CtgMainBundle:Default:aides.html.twig', array('DCU' => $demandeCurrUser
        ));

        return new Response($content);
    }
    
    /**
     * @Route("/faq", name="ctg_faq")
     */
    public function faqAction(){
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

        $content =  $this->get('templating')->render('CtgMainBundle:Default:faq.html.twig', array('DCU' => $demandeCurrUser
        ));

        return new Response($content);
    }
    
    /**
     * @Route("/education/enseignement-supérieur/bac1a5",name="ctg_bac1a5")
     */ 
    public function bac1a5(){
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlContact = $this->get('router')->generate('ctg_contact');
        $urlProcedureAttribution = $this->get('router')->generate('ctg_procedure_attribution');
        $urlListePiece = $this->get('router')->generate('ctg_liste_piece');
        $urlReglement = $this->get('router')->generate('ctg_reglement'); 

        //Utilisateur courant
        
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
        
        $content =  $this->get('templating')->render('CtgMainBundle:Default:bac1a5.html.twig', array('urlReglement' => $urlReglement, 
                                                                                                                    'urlBac1a5' => $urlBac1a5, 
                                                                                                                    'urlProcedureAttribution' => $urlProcedureAttribution,
                                                                                                                    'urlListePiece' => $urlListePiece,
                                                                                                                    'urlContact' => $urlContact,
														    'DCU' => $demandeCurrUser
        ));
        return new Response($content);
    }
    
    /**
     * @Route("/creer-compte",name="ctg_creer_compte")
     */ 
    public function creerCompteAction(){
        $urlFormulaireCCE = $this->get('router')->generate('ctg_form_creation_compte_etudiant');
        $urlFormulaireCCP = $this->get('router')->generate('ctg_form_creation_compte_pro');
        
        $content = $this->get('templating')->render('CtgMainBundle:Default:creer-compte.html.twig', array('urlFormulaireCCE' => $urlFormulaireCCE, 'urlFormulaireCCP' => $urlFormulaireCCP));
        return new Response($content);
    }
    
    /**
     * @Route("/")
     */
    public function indexAction(){
        $urlEnseignement_superieur = $this->get('router')->generate('ctg_enseignement_superieur');
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlLoremIpsum = $this->get('router')->generate('ctg_lorem_ipsum');
        $urlEducation = $this->get('router')->generate('ctg_education');
        
        $content =  $this->get('templating')->render('CtgMainBundle:Default:education.html.twig', array(
            'urlEnsuignement_superieur' => $urlEnseignement_superieur, 
            'urlBac1a5' => $urlBac1a5,
            'urlLoremIpsum' => $urlLoremIpsum,
            ));
        return new Response($content);
    }
    
    /**
     * @Route("/gestionnaire")

     */
    public function gestionnaireAction(){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
        $listeDemandes = $repository->findAll();
        
        $content = $this->get('templating')->render('CtgMainBundle:Default:gestionnaire.html.twig', array('listeDemandes' => $listeDemandes));

        return new Response($content);
    }
    
    /**
     * @Route("/education", name="ctg_education")
     */ 
    public function education(){
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
        $urlEnseignement_superieur = $this->get('router')->generate('ctg_enseignement_superieur');
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlLoremIpsum = $this->get('router')->generate('ctg_lorem_ipsum');
        
        $content =  $this->get('templating')->render('CtgMainBundle:Default:education.html.twig', array(
            'urlEnsuignement_superieur' => $urlEnseignement_superieur, 
            'urlBac1a5' => $urlBac1a5,
            'urlLoremIpsum' => $urlLoremIpsum,
	    'DCU' => $demandeCurrUser
            ));
        return new Response($content);
    }
    
    /**
     * @Route("/education/enseignement-supérieur", name="ctg_enseignement_superieur")
     */
    public function EnseignementSuperieurAction(){
    $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
    $urlContact = $this->get('router')->generate('ctg_contact');
    $urlProcedureAttribution = $this->get('router')->generate('ctg_procedure_attribution');
    $urlListePiece = $this->get('router')->generate('ctg_liste_piece');
    $urlReglement = $this->get('router')->generate('ctg_reglement'); 
    $urlCreerCompte = $this->get('router')->generate('ctg_creer_compte');
    $urlLoremIpsum = $this->get('router')->generate('ctg_lorem_ipsum');
    $login = $this->get('router')->generate('login');
    
    $content = $this->get('templating')->render('CtgMainBundle:Default:enseignement-superieur.html.twig', array(
        'urlBac1a5' => $urlBac1a5, 
        "urlCreerCompte" => $urlCreerCompte, 
        "login" => $login,
        'urlLoremIpsum' => $urlLoremIpsum,
        'urlReglement' => $urlReglement, 
        'urlBac1a5' => $urlBac1a5, 
        'urlProcedureAttribution' => $urlProcedureAttribution,
        'urlListePiece' => $urlListePiece,
        'urlContact' => $urlContact
    ));
    return new Response($content);
    }
    
    /**
     * @Route("/education/enseignement-supérieur/bac1a5/liste-piece",name="ctg_liste_piece")
     */
     public function listePieceAction(){
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlContact = $this->get('router')->generate('ctg_contact');
        $urlProcedureAttribution = $this->get('router')->generate('ctg_procedure_attribution');
        $urlListePiece = $this->get('router')->generate('ctg_liste_piece');
        $urlReglement = $this->get('router')->generate('ctg_reglement'); 
         
        $content =  $this->get('templating')->render('CtgMainBundle:Default:liste-piece.html.twig', array('urlReglement' => $urlReglement, 
                                                                                                                    'urlBac1a5' => $urlBac1a5, 
                                                                                                                    'urlProcedureAttribution' => $urlProcedureAttribution,
                                                                                                                    'urlListePiece' => $urlListePiece,
                                                                                                                    'urlContact' => $urlContact
        ));
        return new Response($content);   
     }

    /**
     * @Route("/lorem-ipsum", name="ctg_lorem_ipsum")
     */ 
    public function loremIpsumAction(){
        $urlEnseignement_superieur = $this->get('router')->generate('ctg_enseignement_superieur');
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlLoremIpsum = $this->get('router')->generate('ctg_lorem_ipsum');
        
        $content =  $this->get('templating')->render('CtgMainBundle:Default:lorem-ipsum.html.twig', array(
            'urlEnsuignement_superieur' => $urlEnseignement_superieur, 
            'urlBac1a5' => $urlBac1a5, 
            'urlLoremIpsum' => $urlLoremIpsum
        ));
        return new Response($content);
    }
    
    /**
     * @Route("/nouvelle-demande",name="ctg_nouvelle_demande")
     * @Security("has_role('ROLE_UTILISATEUR')")
     */ 
    public function nouvelleDemandeAction(){

        $urlFormulaireDemande = $this->get('router')->generate('ctg_form_creation_demande_bac1a5');
        //$urlFormulaireBac6a8 = $this->get('router')->generate('ctg_form_creation_compte_pro');

        //Utilisateur courant
        
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

        $content = $this->get('templating')->render('CtgMainBundle:Default:nouvelle-demande.html.twig', array('urlFormulaireDemande' => $urlFormulaireDemande, 'DCU' => $demandeCurrUser));
        return new Response($content);
    }
    
    /**
     * @Route("/perso",name="ctg_perso")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function persoAction(Request $request){
        if($request->isMethod('POST')){
            //echo $request.'</br>';
            $idg = $request->request->get('o');
            $id = $request->request->get('id');
            $idu = $request->request->get('id_user_add');
            $idd = $request->request->get('id_doc');

            //Changement de gestionnaire
            if($id != null){
                //echo 'id ='.$id;
                //echo 'idg = '.$idg;
                $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
                $dem = $rep->find($id);
                $rep2 = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
                $u = $rep2->find($idg);
                $dem->setGestionnaire($u);
				$dem->setEtat('Transmis');
                $em = $this->getDoctrine()->getManager();
                $em->persist($dem);
                $em->flush();
            }
            //Upload
            else{
                //Ajout de doc lié à l'utilisateur
                if($idu != null){
                    //echo $idu;
                    $f = $request->request->get('filename');
                    
                    if (strcmp($f,'_piece_identite.pdf') == 0){
                        if(isset($_FILES['file'])){
                            //echo 'yes<br>';
                            $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
                            $utilisateur = $rep->find($idu);
                            $dossier = $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail().'/';
                            $filename = $utilisateur->getMail().$f;
                            //echo $dossier.'<br>';
                            //echo $filename.'<br>';
                            if(rename($_FILES['file']['tmp_name'], $dossier . $filename)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                            {
                                //echo 'Upload effectué avec succès !';
                                chmod($dossier . $filename, 0755);
                                $utilisateur->setCni($filename);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($utilisateur);
                                $em->flush();
                            }
                            else //Sinon (la fonction renvoie FALSE).
                            {
                               // echo 'Echec de l\'upload !';
                            }
                        }
                        else{
                            //echo 'no ';
                        }
                    }
                    
                    else if (strcmp($f,'_rib.pdf') == 0){
                        if(isset($_FILES['file'])){
                            $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
                            $utilisateur = $rep->find($idu);
                            $dossier = $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail().'/';
                            $filename = $utilisateur->getMail().$f;
                            if(rename($_FILES['file']['tmp_name'], $dossier . $filename)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                            {
                                chmod($dossier . $filename, 0755);
                                $utilisateur->setRibFile($filename);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($utilisateur);
                                $em->flush();
                                echo 'yese';
                            }
                        }
                    } 
                    
                    else if (strcmp($f,'_cv.pdf') == 0){
                        if(isset($_FILES['file'])){
                            $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
                            $utilisateur = $rep->find($idu);
                            $dossier = $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail().'/';
                            $filename = $utilisateur->getMail().$f;
                            if(rename($_FILES['file']['tmp_name'], $dossier . $filename)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                            {
                                chmod($dossier . $filename, 0755);
                                $utilisateur->setCv($filename);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($utilisateur);
                                $em->flush();
                            }
                        }
                    } 
                    
                    else if (strcmp($f,'_justificatif_domicile.pdf') == 0){
                        if(isset($_FILES['file'])){
                            $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
                            $utilisateur = $rep->find($idu);
                            $dossier = $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail().'/';
                            $filename = $utilisateur->getMail().$f;
                            if(rename($_FILES['file']['tmp_name'], $dossier . $filename)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                            {
                                chmod($dossier . $filename, 0755);
                                $utilisateur->setJustificatifDeDomicile($filename);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($utilisateur);
                                $em->flush();
                            }
                        }
                    }
                    
                    else if (strcmp($f,'_diplome.pdf') == 0){
                        if(isset($_FILES['file'])){
                            $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
                            $utilisateur = $rep->find($idu);
                            $dossier = $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail().'/';
                            $filename = $utilisateur->getMail().$f;
                            if(rename($_FILES['file']['tmp_name'], $dossier . $filename)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                            {
                                chmod($dossier . $filename, 0755);
                                $utilisateur->setDiplome($filename);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($utilisateur);
                                $em->flush();
                            }
                        }
                    }
                }
                //Ajout de doc lié à la demande
                else if($idd != null){
                    $idu = $request->request->get('id_user');
                    $f = $request->request->get('filename');
                    if(strcmp($f,'_avis_imposition_n.pdf') == 0){
                        if(isset($_FILES['file'])){
                            $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
                            $rep2 = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
                            $utilisateur = $rep2->find($idu);
                            $demande = $rep->find($idd);
                            $dossier = $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail().'/';
                            $filename = $utilisateur->getMail().$f;
                            if(rename($_FILES['file']['tmp_name'], $dossier . $filename)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                            {
                                chmod($dossier . $filename, 0755);
                                $demande->setAvisDImpositionN($filename);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($demande);
                                $em->flush();
                            }
                        }    
                    }
                    
                    else if(strcmp($f,'_certificat_scolarite.pdf') == 0){
                        if(isset($_FILES['file'])){
                            $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
                            $rep2 = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
                            $utilisateur = $rep2->find($idu);
                            $demande = $rep->find($idd);
                            $dossier = $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail().'/';
                            $filename = $utilisateur->getMail().$f;
                            if(rename($_FILES['file']['tmp_name'], $dossier . $filename)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                            {
                                chmod($dossier . $filename, 0755);
                                $demande->setCertificatDeScolarite($filename);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($demande);
                                $em->flush();
                            }
                        }    
                    }
                    
                    else if(strcmp($f,'_notification_crous.pdf') == 0){
                        if(isset($_FILES['file'])){
                            $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
                            $rep2 = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
                            $utilisateur = $rep2->find($idu);
                            $demande = $rep->find($idd);
                            $dossier = $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail().'/';
                            $filename = $utilisateur->getMail().$f;
                            if(rename($_FILES['file']['tmp_name'], $dossier . $filename)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                            {
                                chmod($dossier . $filename, 0755);
                                $demande->setNotificationCROUS($filename);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($demande);
                                $em->flush();
                            }
                        }    
                    }
                    
                    else if(strcmp($f,'_avis_imposition_n_1.pdf') == 0){
                        if(isset($_FILES['file'])){
                            $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
                            $rep2 = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
                            $utilisateur = $rep2->find($idu);
                            $demande = $rep->find($idd);
                            $dossier = $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail().'/';
                            $filename = $utilisateur->getMail().$f;
                            if(rename($_FILES['file']['tmp_name'], $dossier . $filename)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                            {
                                chmod($dossier . $filename, 0755);
                                $demande->setAvisDImpositionN1($filename);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($demande);
                                $em->flush();
                            }
                        }    
                    }
                    
                    else if(strcmp($f,'_certificat_scolarite_n_1.pdf') == 0){
                        if(isset($_FILES['file'])){
                            $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
                            $rep2 = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
                            $utilisateur = $rep2->find($idu);
                            $demande = $rep->find($idd);
                            $dossier = $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail().'/';
                            $filename = $utilisateur->getMail().$f;
                            if(rename($_FILES['file']['tmp_name'], $dossier . $filename)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                            {
                                chmod($dossier . $filename, 0755);
                                $demande->setCertificatDeScolariteN1($filename);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($demande);
                                $em->flush();
                            }
                        }    
                    }
                }
                
                else if($idd == null){
                    if(strcmp($f,'_avis_imposition_n.pdf') == 0){
                        if(isset($_FILES['file'])){
                            $rep2 = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
                            $utilisateur = $rep2->find($idu);
                            $dossier = $this->getParameter('document_directory').'/'.$utilisateur->getId().'_'.$utilisateur->getMail().'/';
                            $filename = $utilisateur->getMail().$f;
                            if(rename($_FILES['file']['tmp_name'], $dossier . $filename)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                            {
                                chmod($dossier . $filename, 0755);
                                $demande->setAvisDImpositionN($filename);
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($demande);
                                $em->flush();
                            }
                        }    
                    }
                }   
                }
        }
        
        $urlEnseignement_superieur = $this->get('router')->generate('ctg_enseignement_superieur');
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlLoremIpsum = $this->get('router')->generate('ctg_lorem_ipsum');
        $urlContact = $this->get('router')->generate('ctg_contact');
        $urlNouvelle_demande = $this->get('router')->generate('ctg_nouvelle_demande');
        
        
        //Utilisateur courant
        
        $security = $this->get('security.token_storage');
        // On récupère le token
        $token = $security->getToken();
        // Si la requête courante n'est pas derrière un pare-feu, $token est null
        // Sinon, on récupère l'utilisateur
        $user = $token->getUser();
        
        
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
        $qb = $repository->createQueryBuilder('a');
        
        $qb
            ->where('a.gestionnaire IS NULL')
        ;
        $listeDemandes = $qb->getQuery()->getResult();
        
        $qb
            ->where('a.gestionnaire IS NOT NULL')
        ;
        $listeDemandes2 = $qb->getQuery()->getResult();
        
        $listeDemandes3 = $repository->findBy(
            array('etat' => 'Commission')
            );
        $listeDemandesGest3 = $repository->findBy(
            array(
                'gestionnaire' => $user->getId(),
                'etat' => 'Commission',
            ));
        
        $listeDemandes4 = $repository->findBy(
            array('etat' => 'Validée')
            );
            
        $listeDemandesGest4 = $repository->findBy(
            array(
                'gestionnaire' => $user->getId(),
                'etat' => 'Validée',
            ));
            
        $listeDemandes5 = $repository->findBy(
            array('etat' => 'Rejetée')
            );
            
        $listeDemandesGest5 = $repository->findBy(
            array(
                'gestionnaire' => $user->getId(),
                'etat' => 'Rejetée',
            ));
            
        $listeDemandes6 = $repository->findBy(
            array('etat' => 'Paiement')
            );
            
        $listeDemandesGest6 = $repository->findBy(
            array(
                'gestionnaire' => $user->getId(),
                'etat' => 'Paiement',
            ));

        $listeDemandes7 = $repository->findBy(
            array('etat' => 'Clôturée')
            );
            
        $listeDemandesGest7 = $repository->findBy(
            array(
                'gestionnaire' => $user->getId(),
                'etat' => 'Clôturée',
            ));

        $repositoryGest = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $listeGest = $repositoryGest->findBy(
            array('role'=>'ROLE_GESTIONNAIRE')
            );
        $demandeCurrUser = $repository->findOneBy(
                array('demandeur' => $user->getId())
            );
        //Afficher toute les demandes pour un gestionnaire
        $listeDemandeForGuest = $repository->findBy(
            array(
                'gestionnaire' => $user->getId(),
                'etat' => 'Transmis'
            ));
            
        $listeDemandeComplet = $repository->findBy(
            array(
                'gestionnaire' => $user->getId(),
                'etat' => 'Complet'
                )
        );
        
        $content = $this->get('templating')->render('CtgMainBundle:Default:perso.html.twig', array(
            'urlEnsuignement_superieur' => $urlEnseignement_superieur, 
            'urlBac1a5' => $urlBac1a5,
            'urlLoremIpsum' => $urlLoremIpsum,
            'urlContact' => $urlContact,
            'urlNouvelle_demande' => $urlNouvelle_demande,
            'listeDemandes' => $listeDemandes,
            'listeDemandes2' => $listeDemandes2,
            'listeDemandes3' => $listeDemandes3,
            'listeDemandesGest3' => $listeDemandesGest3,
            'listeDemandes4' => $listeDemandes4,
            'listeDemandesGest4' => $listeDemandesGest4,
            'listeDemandes5' => $listeDemandes5,
            'listeDemandesGest5' => $listeDemandesGest5,
            'listeDemandes6' => $listeDemandes6,
            'listeDemandesGest6' => $listeDemandesGest6,
            'listeDemandes7' => $listeDemandes7,
            'listeDemandesGest7' => $listeDemandesGest7,
            'listeGest' => $listeGest,
            'listeDemandeForGuest' => $listeDemandeForGuest,
            'listeDemandeComplet' => $listeDemandeComplet,
            'DCU' => $demandeCurrUser
        ));
        
        //Fomulaire pour uploader des fichiers
        
        
        
        return new Response($content);
    }
    
    /**
     * @Route("/education/enseignement-supérieur/bac1a5/procedure-attribution",name="ctg_procedure_attribution")
     */
     public function procedureAttributionAction(){
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlContact = $this->get('router')->generate('ctg_contact');
        $urlProcedureAttribution = $this->get('router')->generate('ctg_procedure_attribution');
        $urlListePiece = $this->get('router')->generate('ctg_liste_piece');
        $urlReglement = $this->get('router')->generate('ctg_reglement');
        $urlContact = $this->get('router')->generate('ctg_contact');
         
        $content =  $this->get('templating')->render('CtgMainBundle:Default:procedure-attribution.html.twig', array('urlReglement' => $urlReglement, 
                                                                                                                    'urlBac1a5' => $urlBac1a5, 
                                                                                                                    'urlProcedureAttribution' => $urlProcedureAttribution,
                                                                                                                    'urlListePiece' => $urlListePiece,
                                                                                                                    'urlContact' => $urlContact,
        ));
        return new Response($content);   
     }
     
     /**
      * @Route("/profil/{id}", name="ctg_profil")
      * @Security("has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
      */
    public function profilAction($id){
        $security = $this->get('security.token_storage');
        // On récupère le token
        $token = $security->getToken();
        // Si la requête courante n'est pas derrière un pare-feu, $token est null
        // Sinon, on récupère l'utilisateur
        $user = $token->getUser();
        
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $repositoryD = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
        $utilisateur = new Utilisateurs();
    
        $utilisateur = $repository->findOneBy(
                array('id' => $id)
            );
            
        $ageUser = (int)((time() - strtotime($utilisateur->getDateNaissance()->format('Y-m-d'))) /3600 /24 /365 );
        
        $demandeCurrUser = $repositoryD->findOneBy(
                array('demandeur' => $utilisateur->getId())
            );
        
        if(strcmp('ROLE_GESTIONNAIRE',$user->getRole()) == 0 && strcmp('Commission',$demandeCurrUser->getEtat()) == 0){
            return $this->redirectToRoute('ctg_perso');
        }
        else{
            $content = $this->get('templating')->render('CtgMainBundle:Default:fiche.html.twig', array(
                'utilisateur' => $utilisateur,
                'DCU' => $demandeCurrUser,
                'ageUser' => $ageUser
            ));
            return new Response($content);   
        }
    }
    
    /**
     * @Route("/referent")
     * @Security("has_role('ROLE_REFERENT')")
     */
    public function referentAction(){
        return new Response("Hello World ! vous êtes sur votre page référent");
    }
    
    /**
     * @Route("/education/enseignement-supérieur/bac1a5/reglement",name="ctg_reglement")
     */
     public function reglementAction(){
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlContact = $this->get('router')->generate('ctg_contact');
        $urlProcedureAttribution = $this->get('router')->generate('ctg_procedure_attribution');
        $urlListePiece = $this->get('router')->generate('ctg_liste_piece');
        $urlReglement = $this->get('router')->generate('ctg_reglement'); 
         
        $content =  $this->get('templating')->render('CtgMainBundle:Default:reglement.html.twig', array('urlReglement' => $urlReglement, 
                                                                                                        'urlBac1a5' => $urlBac1a5,
                                                                                                        'urlProcedureAttribution' => $urlProcedureAttribution,
                                                                                                        'urlListePiece' => $urlListePiece,
                                                                                                        'urlContact' => $urlContact
        ));
        return new Response($content);   
     }
}