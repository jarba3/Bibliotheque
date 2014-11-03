<?php

namespace Bibliotheque\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Isbn;

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
     * @var string
     *
     * @ORM\Column(name="isbn", type="string", length=16)
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
     * @var string
     * @Assert\File( maxSize = "2048k", mimeTypesMessage = "Please upload a valid Image")
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="urlimage", type="string", length=255)
     */
    private $urlimage;

    /**
     * @var string
     *
     * @ORM\Column(name="altimage", type="string", length=255)
     */
    private $altimage;

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
     * @param string $isbn
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
     * @return string 
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
     * Set image
     *
     * @param string $image
     * @return Livres
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set urlimage
     *
     * @param string $urlimage
     * @return Livres
     */
    public function setUrlimage($urlimage)
    {
        $this->urlimage = $urlimage;

        return $this;
    }

    /**
     * Get urlimage
     *
     * @return string 
     */
    public function getUrlimage()
    {
        return $this->urlimage;
    }

    /**
     * Set altimage
     *
     * @param string $altimage
     * @return Livres
     */
    public function setAltimage($altimage)
    {
        $this->altimage = $altimage;

        return $this;
    }

    /**
     * Get altimage
     *
     * @return string 
     */
    public function getAltimage()
    {
        return $this->altimage;
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
