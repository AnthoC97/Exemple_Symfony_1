<?php

namespace Ctg\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Utilisateurs
 *
 * @ORM\Table(name="utilisateurs")
 * @ORM\Entity(repositoryClass="Ctg\MainBundle\Repository\UtilisateursRepository")
 */
class Utilisateurs implements UserInterface
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
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=60, unique=true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=60)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=60)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_naissance", type="date",  nullable=true)
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=20)
     */
    private $role;
    
    /**
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;
    
    /**
     * @ORM\Column(name="rue", type="string", length=255, nullable=true)
     */
    private $rue;
    
    /**
     * @ORM\Column(name="code_postal", type="string", length=255, nullable=true)
     */ 
    private $code_postal;
    
    /**
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */ 
    private $ville;
    
    /**
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */ 
    private $telephone;

    /**
     * @ORM\Column(name="cni", type="string", length=255, nullable=true)
     * 
     */ 
    private $cni;

    /**
     * @ORM\Column(name="justificatif_de_domicile", type="string", length=255,  nullable=true)
     */ 
    private $justificatif_de_domicile;
    
    /**
     * @ORM\Column(name="niveau_d_etude", type="string", length=255,  nullable=true)
     */ 
    private $niveau_d_etude;
    
    /**
     * @ORM\Column(name="dernier_diplome", type="string", length=255,  nullable=true)
     */ 
    private $dernier_diplome;
    
    /**
     * @ORM\Column(name="confirm_password", type="string", length=255, nullable=true)
     */ 
    private $confirm_password;

    /**
     * @ORM\Column(name="rib", type="string", length=255, nullable=true)
     */ 
    private $rib;
    
    /**
     * @ORM\Column(name="diplome", type="string", length=255,  nullable=true)
     */ 
    private $diplome;
    
    /**
     * @ORM\Column(name="rib_file", type="string", length=255, nullable=true)
     */ 
    private $rib_file;
    
    /**
     * @ORM\Column(name="denomination", type="string", length=255,  nullable=true)
     */ 
    private $denomination;
    
    /**
     * @ORM\Column(name="siret", type="string", length=255,  nullable=true)
     */ 
    private $siret;
    
    /**
     * @ORM\Column(name="kbis", type="string", length=255,  nullable=true)
     */ 
    private $kbis;
    
    /**
     * @ORM\Column(name="cv", type="string", length=255, nullable=true)
     */ 
    private $cv;
    /**
     * @ORM\Column(name="lieu_de_naissance", type="string", length=255,  nullable=true)
     */ 
    private $lieu_de_naissance;
    /**
     * @ORM\Column(name="forgetPass", type="string", length=255,  nullable=true)
     */ 
    private $forgetPass;




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
     * Set mail
     *
     * @param string $mail
     *
     * @return Utilisateurs
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Utilisateurs
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Utilisateurs
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Utilisateurs
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return Utilisateurs
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Utilisateurs
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return Utilisateurs
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
    
        public function eraseCredentials()
    {
    }
    
    public function getRoles(){
        return array($this->getRole());
    }
    
    public function getUsername(){
        
    }

    /**
     * Set rue
     *
     * @param string $rue
     *
     * @return Utilisateurs
     */
    public function setRue($rue)
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * Get rue
     *
     * @return string
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return Utilisateurs
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
     * Set ville
     *
     * @param string $ville
     *
     * @return Utilisateurs
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
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Utilisateurs
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set cni
     *
     * @param string $cni
     *
     * @return Utilisateurs
     */
    public function setCni($cni)
    {
        $this->cni = $cni;

        return $this;
    }

    /**
     * Get cni
     *
     * @return string
     */
    public function getCni()
    {
        return $this->cni;
    }

    /**
     * Set justificatifDeDomicile
     *
     * @param string $justificatifDeDomicile
     *
     * @return Utilisateurs
     */
    public function setJustificatifDeDomicile($justificatifDeDomicile)
    {
        $this->justificatif_de_domicile = $justificatifDeDomicile;

        return $this;
    }

    /**
     * Get justificatifDeDomicile
     *
     * @return string
     */
    public function getJustificatifDeDomicile()
    {
        return $this->justificatif_de_domicile;
    }

    /**
     * Set niveauDEtude
     *
     * @param string $niveauDEtude
     *
     * @return Utilisateurs
     */
    public function setNiveauDEtude($niveauDEtude)
    {
        $this->niveau_d_etude = $niveauDEtude;

        return $this;
    }

    /**
     * Get niveauDEtude
     *
     * @return string
     */
    public function getNiveauDEtude()
    {
        return $this->niveau_d_etude;
    }

    /**
     * Set dernierDiplome
     *
     * @param string $dernierDiplome
     *
     * @return Utilisateurs
     */
    public function setDernierDiplome($dernierDiplome)
    {
        $this->dernier_diplome = $dernierDiplome;

        return $this;
    }

    /**
     * Get dernierDiplome
     *
     * @return string
     */
    public function getDernierDiplome()
    {
        return $this->dernier_diplome;
    }

    /**
     * Set confirmPassword
     *
     * @param string $confirmPassword
     *
     * @return Utilisateurs
     */
    public function setConfirmPassword($confirmPassword)
    {
        $this->confirm_password = $confirmPassword;

        return $this;
    }

    /**
     * Get confirmPassword
     *
     * @return string
     */
    public function getConfirmPassword()
    {
        return $this->confirm_password;
    }

    /**
     * Set rib
     *
     * @param string $rib
     *
     * @return Utilisateurs
     */
    public function setRib($rib)
    {
        $this->rib = $rib;

        return $this;
    }

    /**
     * Get rib
     *
     * @return string
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     * Set diplome
     *
     * @param string $diplome
     *
     * @return Utilisateurs
     */
    public function setDiplome($diplome)
    {
        $this->diplome = $diplome;

        return $this;
    }

    /**
     * Get diplome
     *
     * @return string
     */
    public function getDiplome()
    {
        return $this->diplome;
    }

    /**
     * Set ribFile
     *
     * @param string $ribFile
     *
     * @return Utilisateurs
     */
    public function setRibFile($ribFile)
    {
        $this->rib_file = $ribFile;

        return $this;
    }

    /**
     * Get ribFile
     *
     * @return string
     */
    public function getRibFile()
    {
        return $this->rib_file;
    }

    /**
     * Set denomination
     *
     * @param string $denomination
     *
     * @return Utilisateurs
     */
    public function setDenomination($denomination)
    {
        $this->denomination = $denomination;

        return $this;
    }

    /**
     * Get denomination
     *
     * @return string
     */
    public function getDenomination()
    {
        return $this->denomination;
    }

    /**
     * Set siret
     *
     * @param string $siret
     *
     * @return Utilisateurs
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return string
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * Set kbis
     *
     * @param string $kbis
     *
     * @return Utilisateurs
     */
    public function setKbis($kbis)
    {
        $this->kbis = $kbis;

        return $this;
    }

    /**
     * Get kbis
     *
     * @return string
     */
    public function getKbis()
    {
        return $this->kbis;
    }

    /**
     * Set cv
     *
     * @param string $cv
     *
     * @return Utilisateurs
     */
    public function setCv($cv)
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * Get cv
     *
     * @return string
     */
    public function getCv()
    {
        return $this->cv;
    }

    /**
     * Set lieuDeNaissance
     *
     * @param string $lieuDeNaissance
     *
     * @return Utilisateurs
     */
    public function setLieuDeNaissance($lieuDeNaissance)
    {
        $this->lieu_de_naissance = $lieuDeNaissance;

        return $this;
    }

    /**
     * Get lieuDeNaissance
     *
     * @return string
     */
    public function getLieuDeNaissance()
    {
        return $this->lieu_de_naissance;
    }

    /**
     * Set forgetPass
     *
     * @param string $forgetPass
     *
     * @return Utilisateurs
     */
    public function setForgetPass($forgetPass)
    {
        $this->forgetPass = $forgetPass;

        return $this;
    }

    /**
     * Get forgetPass
     *
     * @return string
     */
    public function getForgetPass()
    {
        return $this->forgetPass;
    }
}
