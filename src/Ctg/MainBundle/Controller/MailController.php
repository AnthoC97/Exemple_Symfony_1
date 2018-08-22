<?php

namespace Ctg\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle\Swift_Mailer;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle\Swift_Messag;

class MailController extends Controller
{

    /**
     * @Route("/envoyer-mail", name="ctg_envoyer_mail")
     */ 
   public function envoyerMailAction(){
        
        $mailer = Swift_Mailer::newInstance($transport);
        $name = "Fabrcie Contrevilliers";
        $message = (new \Swift_Message('Inscription Réussie'))
            ->setFrom('noreply@ayanexis.com')
            ->setTo('contrevilliers@yahoo.fr')
            ->setBody(
                $this->renderView(
                    'Email/enregistrement.html.twig',
                    array('name' => $name)
                ),    
                'text/html'
            )
        ;
        $mailer->send($message);
        
        
        
        $urlBac1a5 = $this->get('router')->generate('ctg_bac1a5');
        $urlContact = $this->get('router')->generate('ctg_contact');
        $urlProcedureAttribution = $this->get('router')->generate('ctg_procedure_attribution');
        $urlListePiece = $this->get('router')->generate('ctg_liste_piece');
        $urlReglement = $this->get('router')->generate('ctg_reglement'); 
        
        return $this->render('CtgMainBundle:Formulaire:contact.html.twig', array(
            'form' => $form->createView(),
            'urlReglement' => $urlReglement, 
            'urlBac1a5' => $urlBac1a5,
            'urlProcedureAttribution' => $urlProcedureAttribution,
            'urlListePiece' => $urlListePiece,
            'urlContact' => $urlContact
            
        ));
    }    
}