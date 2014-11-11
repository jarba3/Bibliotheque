<?php

namespace Bibliotheque\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(name="isbn", type="string", length=16, unique=true)
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
     * @ORM\ManyToOne(targetEntity="Bibliotheque\UserBundle\Entity\Theme")     
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
     * @ORM\ManyToOne(targetEntity="Bibliotheque\UserBundle\Entity\Auteur")
     */
    private $auteur;

    /**
     * @ORM\ManyToOne(targetEntity="Bibliotheque\UserBundle\Entity\Editeur")     
     */
    private $editeur;

    /**
    * @var ArrayCollection $exemplaire
    *
    * @ORM\OneToMany(targetEntity="Exemplaire", mappedBy="livre", cascade={"persist", "remove"})
    */
    private $exemplaire;

    /**
    * @var $reservation
    *
    * @ORM\ManyToOne(targetEntity="Reservation", inversedBy="livre", cascade={"persist"})
    * @ORM\JoinColumns({@ORM\JoinColumn(name="reservation_id", referencedColumnName="id")})
    */
    private $reservation;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->exemplaire = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set theme
     *
     * @param \Bibliotheque\UserBundle\Entity\Theme $theme
     * @return Livres
     */
    public function setTheme(\Bibliotheque\UserBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return \Bibliotheque\UserBundle\Entity\Theme 
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set auteur
     *
     * @param \Bibliotheque\UserBundle\Entity\Auteur $auteur
     * @return Livres
     */
    public function setAuteur(\Bibliotheque\UserBundle\Entity\Auteur $auteur = null)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return \Bibliotheque\UserBundle\Entity\Auteur 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set editeur
     *
     * @param \Bibliotheque\UserBundle\Entity\Editeur $editeur
     * @return Livres
     */
    public function setEditeur(\Bibliotheque\UserBundle\Entity\Editeur $editeur = null)
    {
        $this->editeur = $editeur;

        return $this;
    }

    /**
     * Get editeur
     *
     * @return \Bibliotheque\UserBundle\Entity\Editeur 
     */
    public function getEditeur()
    {
        return $this->editeur;
    }

    /**
     * Add exemplaire
     *
     * @param \Bibliotheque\UserBundle\Entity\Exemplaire $exemplaire
     * @return Livres
     */
    public function addExemplaire(\Bibliotheque\UserBundle\Entity\Exemplaire $exemplaire)
    {
        $this->exemplaire[] = $exemplaire;

        return $this;
    }

    /**
     * Remove exemplaire
     *
     * @param \Bibliotheque\UserBundle\Entity\Exemplaire $exemplaire
     */
    public function removeExemplaire(\Bibliotheque\UserBundle\Entity\Exemplaire $exemplaire)
    {
        $this->exemplaire->removeElement($exemplaire);
    }

    /**
     * Get exemplaire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExemplaire()
    {
        return $this->exemplaire;
    }

    /**
     * Set reservation
     *
     * @param \Bibliotheque\UserBundle\Entity\Reservation $reservation
     * @return Livres
     */
    public function setReservation(\Bibliotheque\UserBundle\Entity\Reservation $reservation = null)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get reservation
     *
     * @return \Bibliotheque\UserBundle\Entity\Reservation 
     */
    public function getReservation()
    {
        return $this->reservation;
    }
}
