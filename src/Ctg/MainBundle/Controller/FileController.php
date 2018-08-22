<?php

namespace Ctg\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// Import the BinaryFileResponse
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Ctg\MainBundle\Entity\Utilisateurs;
use Ctg\MainBundle\Entity\Demandes;

class FileController extends Controller
{
    /**
     * @Route("/document",name="ctg_document")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function indexAction()
    {
        // You only need to provide the path to your static file
        // $filepath = 'path/to/TextFile.txt';

        // i.e Sending a file from the resources folder in /web
        // in this example, the TextFile.txt needs to exist in the server
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/';
        $filename = "affiche_ATE-Bourses.jpg";

        // This should return the file located in /mySymfonyProject/web/public-resources/TextFile.txt
        // to being viewed in the Browser
        return new BinaryFileResponse($publicResourcesFolderPath.$filename);
    }

    /**
     * @Route("/document/piece_idendite/{id}",name="ctg_document_piece-identite")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function pieceIdentite_Action($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_piece_identite.pdf';
        return new BinaryFileResponse($publicResourcesFolderPath.$filename);
    }
    
    /**
     * @Route("/document/piece_idendite/del/{id}",name="ctg_document_piece-identite_del")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function delPieceIdentiteAction($id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_piece_identite.pdf';
        
        //on supprime le nom dans la bdd
        $user->setCni(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        
        //on supprime physiquement le fichier
        unlink($publicResourcesFolderPath.$filename);
        return $this->redirectToRoute('ctg_perso');
    }

    /**
     * @Route("/document/rib/{id}",name="ctg_document_rib")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function ribAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_rib.pdf';
        return new BinaryFileResponse($publicResourcesFolderPath.$filename);
    }
    
    /**
     * @Route("/document/rib/del/{id}",name="ctg_document_rib_del")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function delRibAction($id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_rib.pdf';
        
        //on supprime le nom dans la bdd
        $user->setRibFile(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        
        //on supprime physiquement le fichier
        unlink($publicResourcesFolderPath.$filename);
        return $this->redirectToRoute('ctg_perso');
    }
    
    /**
     * @Route("/document/avis-imposition-N/{id}",name="ctg_document_avis-imposition-N")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function avisDImpositionNAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_avis_imposition_n.pdf';
        return new BinaryFileResponse($publicResourcesFolderPath.$filename);
    }
    
    /**
     * @Route("/document/avis-imposition-N/del/{id}",name="ctg_document_avis-imposition_del")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function delAvisImpositionAction($id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');    
        $dem = new Demandes();
        $dem = $rep->findOneBy(
                array('demandeur' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_avis_imposition_n.pdf';
        
        //on supprime le nom dans la bdd
        $dem->setAvisDImpositionN(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($dem);
        $em->flush();
        
        //on supprime physiquement le fichier
        unlink($publicResourcesFolderPath.$filename);
        return $this->redirectToRoute('ctg_perso');
    }
    
    /**
     * @Route("/document/avis-imposition-N1/{id}",name="ctg_document_avis-imposition-N1")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function avisDImpositionN1Action($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_avis_imposition_n_1.pdf';
        return new BinaryFileResponse($publicResourcesFolderPath.$filename);
    }
    
    /**
     * @Route("/document/avis-imposition-N1/del/{id}",name="ctg_document_avis-imposition_N1_del")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function delAvisImpositionN1Action($id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');    
        $dem = new Demandes();
        $dem = $rep->findOneBy(
                array('demandeur' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_avis_imposition_n_1.pdf';
        
        //on supprime le nom dans la bdd
        $dem->setAvisDImpositionN1(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($dem);
        $em->flush();
        
        //on supprime physiquement le fichier
        unlink($publicResourcesFolderPath.$filename);
        return $this->redirectToRoute('ctg_perso');
    }
    
    /**
     * @Route("/document/certificat-scolarite/{id}",name="ctg_document_certificat-scolarite")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function certificatScolariteAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_certificat_scolarite.pdf';
        return new BinaryFileResponse($publicResourcesFolderPath.$filename);
    }
    
    /**
     * @Route("/document/certificat-scolarite/del/{id}",name="ctg_document_certificat-scolarite_del")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function delCertificatScolariteAction($id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');    
        $dem = new Demandes();
        $dem = $rep->findOneBy(
                array('demandeur' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_certificat_scolarite.pdf';
        
        //on supprime le nom dans la bdd
        $dem->setCertificatDeScolarite(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($dem);
        $em->flush();
        
        //on supprime physiquement le fichier
        unlink($publicResourcesFolderPath.$filename);
        return $this->redirectToRoute('ctg_perso');
    }
    
    /**
     * @Route("/document/certificat-scolarite_n_1/{id}",name="ctg_document_certificat-scolarite-N1")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function certificatScolariteN1Action($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_certificat_scolarite_n_1.pdf';
        return new BinaryFileResponse($publicResourcesFolderPath.$filename);
    }
    
    /**
     * @Route("/document/certificat-scolarite-N1/del/{id}",name="ctg_document_certificat-scolarite_N1_del")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function delCertificatScolariteN1Action($id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');    
        $dem = new Demandes();
        $dem = $rep->findOneBy(
                array('demandeur' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_certificat_scolarite_n_1.pdf';
        
        //on supprime le nom dans la bdd
        $dem->setCertificatDeScolariteN1(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($dem);
        $em->flush();
        
        //on supprime physiquement le fichier
        unlink($publicResourcesFolderPath.$filename);
        return $this->redirectToRoute('ctg_perso');
    }
    
    /**
     * @Route("/document/notification-crous/{id}",name="ctg_document_notification-crous")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function notificationCrousAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_notification_crous.pdf';
        return new BinaryFileResponse($publicResourcesFolderPath.$filename);
    }
    
    /**
     * @Route("/document/notification-crous/del/{id}",name="ctg_document_notification-crous_del")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function delNotificationCrousAction($id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $rep = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');    
        $dem = new Demandes();
        $dem = $rep->findOneBy(
                array('demandeur' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_notification_crous.pdf';
        
        //on supprime le nom dans la bdd
        $dem->setNotificationCROUS(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($dem);
        $em->flush();
        
        //on supprime physiquement le fichier
        unlink($publicResourcesFolderPath.$filename);
        return $this->redirectToRoute('ctg_perso');
    }
    
    /**
     * @Route("/document/cv/{id}",name="ctg_document_cv")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function cvAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_cv.pdf';
        return new BinaryFileResponse($publicResourcesFolderPath.$filename);
    }
    
    /**
     * @Route("/document/cv/del/{id}",name="ctg_document_cv_del")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function delCvAction($id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_cv.pdf';
        
        //on supprime le nom dans la bdd
        $user->setCV(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        
        //on supprime physiquement le fichier
        unlink($publicResourcesFolderPath.$filename);
        return $this->redirectToRoute('ctg_perso');
    }
    
    /**
     * @Route("/document/diplome/{id}",name="ctg_document_diplome")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function diplomeAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_diplome.pdf';
        return new BinaryFileResponse($publicResourcesFolderPath.$filename);
    }
    
    /**
     * @Route("/document/diplome/del/{id}",name="ctg_document_diplome_del")
     * @Security("has_role('ROLE_UTILISATEUR') or has_role('ROLE_REFERENT') or has_role('ROLE_GESTIONNAIRE')")
     */
    public function delDiplomeAction($id){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Utilisateurs');
        $user = new Utilisateurs();
    
        $user = $repository->findOneBy(
                array('id' => $id)
            );
        
        $publicResourcesFolderPath = $this->get('kernel')->getRootDir() . '/../document/'.$user->getId().'_'.$user->getMail().'/';
        $filename = $user->getMail().'_diplome.pdf';
        
        //on supprime le nom dans la bdd
        $user->setDiplome(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        
        //on supprime physiquement le fichier
        unlink($publicResourcesFolderPath.$filename);
        return $this->redirectToRoute('ctg_perso');
    }
    
    /**
     * @Route("/document/commission",name="ctg_document_commission")
     * @Security("has_role('ROLE_REFERENT')")
     */
    public function exportToCsv(){
        $repository = $this->getDoctrine()->getManager()->getRepository('CtgMainBundle:Demandes');
        $listeDemandes3 = $repository->findBy(
            array('etat' => 'Commission')
            );
        $tabForCommission = [];
        $tabForCommission[] = array("N°",
                                    "Gestionnaire",
                                    "Nom",
                                    "Prenom",
                                    "Niveau d'études",
                                    "Spécialité",
                                    "Etablissement",
                                    "Montant",
                                    "Rejet"
        );
        foreach($listeDemandes3 as $l){
            $tabForCommission[] = array($l->getId(),
                                        $l->getGestionnaire()->getNom(),
                                        $l->getDemandeur()->getNom(),
                                        $l->getDemandeur()->getPrenom(),
                                        $l->getDemandeur()->getNiveauDEtude(),
                                        $l->getSpecialite(), 
                                        $l->getEtablissement(),
                                        $l->getMontant(),
                                        $l->getRejet()
            );
        }
        $dossier = $this->getParameter('document_directory').'/';
        $fichier = 'commission.csv';
        $delimiteur = ",";
        $fichier_csv = fopen($dossier.$fichier,'w+');
        foreach($tabForCommission as $ligne){
            fputcsv($fichier_csv,$ligne,$delimiteur);
        }
        fclose($fichier_csv);

        $response = new BinaryFileResponse($dossier.$fichier);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        
        return $response;
    }
    
}
