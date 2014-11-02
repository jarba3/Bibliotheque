<?php

namespace Bibliotheque\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * livres
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bibliotheque\UserBundle\Repository\livresRepository")
 */
class Livres
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var integer
     *
     * @ORM\Column(name="isbn", type="integer")
     */
    private $isbn;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateparution", type="date")
     */
    private $dateparution;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_theme", type="integer")
     */
    private $idTheme;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_editeur", type="integer")
     */
    private $idEditeur;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return livres
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set isbn
     *
     * @param integer $isbn
     * @return livres
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get isbn
     *
     * @return integer 
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return livres
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateparution
     *
     * @param \DateTime $dateparution
     * @return livres
     */
    public function setDateparution($dateparution)
    {
        $this->dateparution = $dateparution;

        return $this;
    }

    /**
     * Get dateparution
     *
     * @return \DateTime 
     */
    public function getDateparution()
    {
        return $this->dateparution;
    }

    /**
     * Set idTheme
     *
     * @param integer $idTheme
     * @return livres
     */
    public function setIdTheme($idTheme)
    {
        $this->idTheme = $idTheme;

        return $this;
    }

    /**
     * Get idTheme
     *
     * @return integer 
     */
    public function getIdTheme()
    {
        return $this->idTheme;
    }

    /**
     * Set idEditeur
     *
     * @param integer $idEditeur
     * @return livres
     */
    public function setIdEditeur($idEditeur)
    {
        $this->idEditeur = $idEditeur;

        return $this;
    }

    /**
     * Get idEditeur
     *
     * @return integer 
     */
    public function getIdEditeur()
    {
        return $this->idEditeur;
    }
}
