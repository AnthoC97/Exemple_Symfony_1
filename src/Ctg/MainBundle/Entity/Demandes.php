<?php

namespace Ctg\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Demandes
 *
 * @ORM\Table(name="demandes")
 * @ORM\Entity(repositoryClass="Ctg\MainBundle\Repository\DemandesRepository")
 */
class Demandes
{
    /**
     * @var bigint
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Ctg\MainBundle\Entity\Utilisateurs", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $demandeur;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Ctg\MainBundle\Entity\Utilisateurs", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $gestionnaire;

    /**
     * @var string
     *
     * @ORM\Column(name="type_de_demande", type="string", length=40)
     */
    private $typeDeDemande;
    /**
     * @var string
     *
     * @ORM\Column(name="etablissement_n_1", type="string", length=40)
     */
    private $etablissement_n_1;
    /**
     * @var string
     *
     * @ORM\Column(name="adresse_n_1", type="string", length=40)
     */
    private $adresse_n_1;
    /**
     * @var string
     *
     * @ORM\Column(name="code_postal_n_1", type="string", length=40)
     */
    private $code_postal_n_1;
    /**
     * @var string
     *
     * @ORM\Column(name="ville_n_1", type="string", length=40)
     */
    private $ville_n_1;
     /**
     * @var string
     *
     * @ORM\Column(name="classe_frequentee_n_1", type="string", length=40)
     */
    private $classe_frequentee_n_1;
    /**
     * @var string
     *
     * @ORM\Column(name="specialite_n_1", type="string", length=40)
     */
    private $specialite_n_1;
    /**
     * @var string
     *
     * @ORM\Column(name="etablissement", type="string", length=40)
     */
    private $etablissement;
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=40)
     */
    private $adresse;
    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=40)
     */
    private $code_postal;
    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=40)
     */
    private $ville;
    /**
     * @var string
     *
     * @ORM\Column(name="classe_frequentee", type="string", length=40)
     */
    private $classe_frequentee;
    /**
     * @var string
     *
     * @ORM\Column(name="specialite", type="string", length=40)
     */
    private $specialite;
    /**
     * @var string
     *
     * @ORM\Column(name="memoire", type="string", length=40, nullable=true)
     */
    private $memoire;

    /**
     * @var string
     *
     * @ORM\Column(name="certificat_de_scolarite", type="string", length=40, nullable=true)
     */
    private $certificat_de_scolarite;
    
    /**
     * @var string
     *
     * @ORM\Column(name="certificat_de_scolarite_N_1", type="string", length=40, nullable=true)
     */
    private $certificat_de_scolarite_N_1;    

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_demande", type="date")
     */
    private $dateDemande;
    
    /**
     * @var string
     *
     * @ORM\Column(name="notification_CROUS", type="string", length=40, nullable=true)
     */
    private $notification_CROUS;
    
    /**
     * @var string
     *
     * @ORM\Column(name="avis_d_imposition_N", type="string", length=40, nullable=true)
     */
    private $avis_d_imposition_N;
    /**
     * @var string
     *
     * @ORM\Column(name="avis_d_imposition_N_1", type="string", length=40, nullable=true)
     */
    private $avis_d_imposition_N_1;

    /**
     * @var string
     *
     * @ORM\Column(name="autre_1", type="string", length=40, nullable=true)
     */
    private $autre_1;
    
    /**
     * @var string
     *
     * @ORM\Column(name="autre_2", type="string", length=40, nullable=true)
     */
    private $autre_2;
    
    /**
     * @var string
     *
     * @ORM\Column(name="autre_3", type="string", length=40, nullable=true)
     */
    private $autre_3;
    
    /**
     * @var string
     *
     * @ORM\Column(name="aides", type="array", length=60, nullable=false)
     */
    private $aides;
    
    /**
     * @var string
     *
     * @ORM\Column(name="complet", type="string", length=60, nullable=true)
     */
    private $complet;
    
    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=60, nullable=true)
     */
    private $etat;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="bourseEchelon", type="string", length=60, nullable=true)
     */
    private $bourseEchelon;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="revenuReference", type="string", length=60, nullable=true)
     */
    private $revenuReference;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="referenceCROUS", type="string", length=60, nullable=true)
     */
    private $referenceCROUS;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="rejet", type="string", nullable=true)
     */
    private $rejet;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="string", length=60, nullable=true)
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="lieuEtude", type="string", length=60, nullable=true)
     */
    private $lieuEtude;
    
    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="string", length=255, nullable=true)
     */
    private $observations;
    
    /**
     * @var string
     *
     * @ORM\Column(name="dateComplet", type="date", length=60, nullable=true)
     */
    private $dateComplet;
    
    /**
     * @var string
     *
     * @ORM\Column(name="dateInstruction", type="date", length=60, nullable=true)
     */
    private $dateInstruction;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set typeDeDemande
     *
     * @param string $typeDeDemande
     *
     * @return Demandes
     */
    public function setTypeDeDemande($typeDeDemande)
    {
        $this->typeDeDemande = $typeDeDemande;

        return $this;
    }

    /**
     * Get typeDeDemande
     *
     * @return string
     */
    public function getTypeDeDemande()
    {
        return $this->typeDeDemande;
    }

    /**
     * Set dateDemande
     *
     * @param \DateTime $dateDemande
     *
     * @return Demandes
     */
    public function setDateDemande($dateDemande)
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    /**
     * Get dateDemande
     *
     * @return \DateTime
     */
    public function getDateDemande()
    {
        return $this->dateDemande;
    }

    /**
     * Set demandeur
     *
     * @param \Ctg\MainBundle\Entity\Utilisateurs $demandeur
     *
     * @return Demandes
     */
    public function setDemandeur(\Ctg\MainBundle\Entity\Utilisateurs $demandeur)
    {
        $this->demandeur = $demandeur;

        return $this;
    }

    /**
     * Get demandeur
     *
     * @return \Ctg\MainBundle\Entity\Utilisateurs
     */
    public function getDemandeur()
    {
        return $this->demandeur;
    }

    /**
     * Set etablissementN1
     *
     * @param string $etablissementN1
     *
     * @return Demandes
     */
    public function setEtablissementN1($etablissementN1)
    {
        $this->etablissement_n_1 = $etablissementN1;

        return $this;
    }

    /**
     * Get etablissementN1
     *
     * @return string
     */
    public function getEtablissementN1()
    {
        return $this->etablissement_n_1;
    }

    /**
     * Set adresseN1
     *
     * @param string $adresseN1
     *
     * @return Demandes
     */
    public function setAdresseN1($adresseN1)
    {
        $this->adresse_n_1 = $adresseN1;

        return $this;
    }

    /**
     * Get adresseN1
     *
     * @return string
     */
    public function getAdresseN1()
    {
        return $this->adresse_n_1;
    }

    /**
     * Set codePostalN1
     *
     * @param string $codePostalN1
     *
     * @return Demandes
     */
    public function setCodePostalN1($codePostalN1)
    {
        $this->code_postal_n_1 = $codePostalN1;

        return $this;
    }

    /**
     * Get codePostalN1
     *
     * @return string
     */
    public function getCodePostalN1()
    {
        return $this->code_postal_n_1;
    }

    /**
     * Set classeFrequenteeN1
     *
     * @param string $classeFrequenteeN1
     *
     * @return Demandes
     */
    public function setClasseFrequenteeN1($classeFrequenteeN1)
    {
        $this->classe_frequentee_n_1 = $classeFrequenteeN1;

        return $this;
    }

    /**
     * Get classeFrequenteeN1
     *
     * @return string
     */
    public function getClasseFrequenteeN1()
    {
        return $this->classe_frequentee_n_1;
    }

    /**
     * Set specialiteN1
     *
     * @param string $specialiteN1
     *
     * @return Demandes
     */
    public function setSpecialiteN1($specialiteN1)
    {
        $this->specialite_n_1 = $specialiteN1;

        return $this;
    }

    /**
     * Get specialiteN1
     *
     * @return string
     */
    public function getSpecialiteN1()
    {
        return $this->specialite_n_1;
    }

    /**
     * Set etablissement
     *
     * @param string $etablissement
     *
     * @return Demandes
     */
    public function setEtablissement($etablissement)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return string
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Demandes
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return Demandes
     */
    public function setCodePostal($codePostal)
    {
        $this->code_postal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->code_postal;
    }

    /**
     * Set classeFrequentee
     *
     * @param string $classeFrequentee
     *
     * @return Demandes
     */
    public function setClasseFrequentee($classeFrequentee)
    {
        $this->classe_frequentee = $classeFrequentee;

        return $this;
    }

    /**
     * Get classeFrequentee
     *
     * @return string
     */
    public function getClasseFrequentee()
    {
        return $this->classe_frequentee;
    }

    /**
     * Set specialite
     *
     * @param string $specialite
     *
     * @return Demandes
     */
    public function setSpecialite($specialite)
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * Get specialite
     *
     * @return string
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }

    /**
     * Set memoire
     *
     * @param string $memoire
     *
     * @return Demandes
     */
    public function setMemoire($memoire)
    {
        $this->memoire = $memoire;

        return $this;
    }

    /**
     * Get memoire
     *
     * @return string
     */
    public function getMemoire()
    {
        return $this->memoire;
    }

    /**
     * Set villeN1
     *
     * @param string $villeN1
     *
     * @return Demandes
     */
    public function setVilleN1($villeN1)
    {
        $this->ville_n_1 = $villeN1;

        return $this;
    }

    /**
     * Get villeN1
     *
     * @return string
     */
    public function getVilleN1()
    {
        return $this->ville_n_1;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Demandes
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }


    /**
     * Set certificatDeScolarite
     *
     * @param string $certificatDeScolarite
     *
     * @return Demandes
     */
    public function setCertificatDeScolarite($certificatDeScolarite)
    {
        $this->certificat_de_scolarite = $certificatDeScolarite;

        return $this;
    }

    /**
     * Get certificatDeScolarite
     *
     * @return string
     */
    public function getCertificatDeScolarite()
    {
        return $this->certificat_de_scolarite;
    }

    /**
     * Set notificationCROUS
     *
     * @param string $notificationCROUS
     *
     * @return Demandes
     */
    public function setNotificationCROUS($notificationCROUS)
    {
        $this->notification_CROUS = $notificationCROUS;

        return $this;
    }

    /**
     * Get notificationCROUS
     *
     * @return string
     */
    public function getNotificationCROUS()
    {
        return $this->notification_CROUS;
    }

    /**
     * Set avisDImpositionN
     *
     * @param string $avisDImpositionN
     *
     * @return Demandes
     */
    public function setAvisDImpositionN($avisDImpositionN)
    {
        $this->avis_d_imposition_N = $avisDImpositionN;

        return $this;
    }

    /**
     * Get avisDImpositionN
     *
     * @return string
     */
    public function getAvisDImpositionN()
    {
        return $this->avis_d_imposition_N;
    }

    /**
     * Set gestionnaire
     *
     * @param \Ctg\MainBundle\Entity\Utilisateurs $gestionnaire
     *
     * @return Demandes
     */
    public function setGestionnaire(\Ctg\MainBundle\Entity\Utilisateurs $gestionnaire)
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }



    /**
     * Get gestionnaire
     *
     * @return \Ctg\MainBundle\Entity\Utilisateurs
     */
    public function getGestionnaire()
    {
        return $this->gestionnaire;
    }

    /**
     * Set certificatDeScolariteN1
     *
     * @param string $certificatDeScolariteN1
     *
     * @return Demandes
     */
    public function setCertificatDeScolariteN1($certificatDeScolariteN1)
    {
        $this->certificat_de_scolarite_N_1 = $certificatDeScolariteN1;

        return $this;
    }

    /**
     * Get certificatDeScolariteN1
     *
     * @return string
     */
    public function getCertificatDeScolariteN1()
    {
        return $this->certificat_de_scolarite_N_1;
    }

    /**
     * Set avisDImpositionN1
     *
     * @param string $avisDImpositionN1
     *
     * @return Demandes
     */
    public function setAvisDImpositionN1($avisDImpositionN1)
    {
        $this->avis_d_imposition_N_1 = $avisDImpositionN1;

        return $this;
    }

    /**
     * Get avisDImpositionN1
     *
     * @return string
     */
    public function getAvisDImpositionN1()
    {
        return $this->avis_d_imposition_N_1;
    }

    /**
     * Set autre1
     *
     * @param string $autre1
     *
     * @return Demandes
     */
    public function setAutre1($autre1)
    {
        $this->autre_1 = $autre1;

        return $this;
    }

    /**
     * Get autre1
     *
     * @return string
     */
    public function getAutre1()
    {
        return $this->autre_1;
    }

    /**
     * Set autre2
     *
     * @param string $autre2
     *
     * @return Demandes
     */
    public function setAutre2($autre2)
    {
        $this->autre_2 = $autre2;

        return $this;
    }

    /**
     * Get autre2
     *
     * @return string
     */
    public function getAutre2()
    {
        return $this->autre_2;
    }

    /**
     * Set autre3
     *
     * @param string $autre3
     *
     * @return Demandes
     */
    public function setAutre3($autre3)
    {
        $this->autre_3 = $autre3;

        return $this;
    }

    /**
     * Get autre3
     *
     * @return string
     */
    public function getAutre3()
    {
        return $this->autre_3;
    }

    /**
     * Set aides
     *
     * @param string $aides
     *
     * @return Demandes
     */
    public function setAides($aides)
    {
        $this->aides = $aides;

        return $this;
    }

    /**
     * Get aides
     *
     * @return string
     */
    public function getAides()
    {
        return $this->aides;
    }

    /**
     * Set complet
     *
     * @param array $complet
     *
     * @return Demandes
     */
    public function setComplet($complet)
    {
        $this->complet = $complet;

        return $this;
    }

    /**
     * Get complet
     *
     * @return array
     */
    public function getComplet()
    {
        return $this->complet;
    }

    /**
     * Set etat
     *
     * @param array $etat
     *
     * @return Demandes
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return array
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set bourseEchelon
     *
     * @param string $bourseEchelon
     *
     * @return Demandes
     */
    public function setBourseEchelon($bourseEchelon)
    {
        $this->bourseEchelon = $bourseEchelon;

        return $this;
    }

    /**
     * Get bourseEchelon
     *
     * @return string
     */
    public function getBourseEchelon()
    {
        return $this->bourseEchelon;
    }

    /**
     * Set revenuReference
     *
     * @param string $revenuReference
     *
     * @return Demandes
     */
    public function setRevenuReference($revenuReference)
    {
        $this->revenuReference = $revenuReference;

        return $this;
    }

    /**
     * Get revenuReference
     *
     * @return \int
     */
    public function getRevenuReference()
    {
        return $this->revenuReference;
    }

    /**
     * Set referenceCROUS
     *
     * @param string $referenceCROUS
     *
     * @return Demandes
     */
    public function setReferenceCROUS($referenceCROUS)
    {
        $this->referenceCROUS = $referenceCROUS;

        return $this;
    }

    /**
     * Get referenceCROUS
     *
     * @return string
     */
    public function getReferenceCROUS()
    {
        return $this->referenceCROUS;
    }

    /**
     * Set rejet
     *
     * @param string $rejet
     *
     * @return Demandes
     */
    public function setRejet($rejet)
    {
        $this->rejet = $rejet;

        return $this;
    }

    /**
     * Get rejet
     *
     * @return string
     */
    public function getRejet()
    {
        return $this->rejet;
    }

    /**
     * Set montant
     *
     * @param string $montant
     *
     * @return Demandes
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return \int
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set lieuEtude
     *
     * @param string $lieuEtude
     *
     * @return Demandes
     */
    public function setLieuEtude($lieuEtude)
    {
        $this->lieuEtude = $lieuEtude;

        return $this;
    }

    /**
     * Get lieuEtude
     *
     * @return string
     */
    public function getLieuEtude()
    {
        return $this->lieuEtude;
    }

    /**
     * Set observations
     *
     * @param string $observations
     *
     * @return Demandes
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * Get observations
     *
     * @return string
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * Set dateComplet
     *
     * @param \DateTime $dateComplet
     *
     * @return Demandes
     */
    public function setDateComplet($dateComplet)
    {
        $this->dateComplet = $dateComplet;

        return $this;
    }

    /**
     * Get dateComplet
     *
     * @return \DateTime
     */
    public function getDateComplet()
    {
        return $this->dateComplet;
    }

    /**
     * Set dateInstruction
     *
     * @param \DateTime $dateInstruction
     *
     * @return Demandes
     */
    public function setDateInstruction($dateInstruction)
    {
        $this->dateInstruction = $dateInstruction;

        return $this;
    }

    /**
     * Get dateInstruction
     *
     * @return \DateTime
     */
    public function getDateInstruction()
    {
        return $this->dateInstruction;
    }
}
