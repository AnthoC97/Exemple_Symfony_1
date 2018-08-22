<?php

namespace Ctg\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Documents
 *
 * @ORM\Table(name="documents")
 * @ORM\Entity(repositoryClass="Ctg\MainBundle\Repository\DocumentsRepository")
 */
class Documents
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
     * @ORM\ManyToOne(targetEntity="Ctg\MainBundle\Entity\Utilisateurs", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    
    /**
     * @var string
     *
     * @ORM\Column(name="chemin", type="string", length=255, unique=true)
     */
    private $chemin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_depot", type="date")
     */
    private $dateDepot;

    /**
     * @var string
     *
     * @ORM\Column(name="type_de_document", type="string", length=60)
     */
    private $typeDeDocument;


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
     * Set chemin
     *
     * @param string $chemin
     *
     * @return Documents
     */
    public function setChemin($chemin)
    {
        $this->chemin = $chemin;

        return $this;
    }

    /**
     * Get chemin
     *
     * @return string
     */
    public function getChemin()
    {
        return $this->chemin;
    }

    /**
     * Set dateDepot
     *
     * @param \DateTime $dateDepot
     *
     * @return Documents
     */
    public function setDateDepot($dateDepot)
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    /**
     * Get dateDepot
     *
     * @return \DateTime
     */
    public function getDateDepot()
    {
        return $this->dateDepot;
    }

    /**
     * Set typeDeDocument
     *
     * @param string $typeDeDocument
     *
     * @return Documents
     */
    public function setTypeDeDocument($typeDeDocument)
    {
        $this->typeDeDocument = $typeDeDocument;

        return $this;
    }

    /**
     * Get typeDeDocument
     *
     * @return string
     */
    public function getTypeDeDocument()
    {
        return $this->typeDeDocument;
    }

    /**
     * Set utilisateur
     *
     * @param \Ctg\MainBundle\Entity\Utilisateurs $utilisateur
     *
     * @return Documents
     */
    public function setUtilisateur(\Ctg\MainBundle\Entity\Utilisateurs $utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \Ctg\MainBundle\Entity\Utilisateurs
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
}
