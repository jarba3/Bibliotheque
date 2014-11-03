<?php

namespace Bibliotheque\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livres
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bibliotheque\UserBundle\Repository\LivresRepository")
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
     * @var string
     *
     * @ORM\Column(name="theme", type="string", length=255)
     */
    private $theme;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_auteur", type="integer")
     */
    private $idAuteur;

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
     * @return Livres
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
     * @return Livres
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
     * @return Livres
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
     * @return Livres
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
     * Set theme
     *
     * @param string $theme
     * @return Livres
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return string 
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set idAuteur
     *
     * @param integer $idAuteur
     * @return Livres
     */
    public function setIdAuteur($idAuteur)
    {
        $this->idAuteur = $idAuteur;

        return $this;
    }

    /**
     * Get idAuteur
     *
     * @return integer 
     */
    public function getIdAuteur()
    {
        return $this->idAuteur;
    }

    /**
     * Set idEditeur
     *
     * @param integer $idEditeur
     * @return Livres
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
