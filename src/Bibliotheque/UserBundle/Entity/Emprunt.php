<?php

namespace Bibliotheque\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Emprunt
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bibliotheque\UserBundle\Repository\EmpruntRepository")
 */
class Emprunt
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_emprunt", type="date")
     */
    private $dateEmprunt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_retour", type="date")
     */
    private $dateRetour;

    /**
    * @var ArrayCollection $user
    *
    * @ORM\OneToMany(targetEntity="User", mappedBy="emprunt", cascade={"persist"})
    */
    private $user;

    /**
    * @var ArrayCollection $exemplaire
    *
    * @ORM\OneToMany(targetEntity="Exemplaire", mappedBy="emprunt", cascade={"persist"})
    */
    private $exemplaire;

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
     * Set dateEmprunt
     *
     * @param \DateTime $dateEmprunt
     * @return Emprunt
     */
    public function setDateEmprunt($dateEmprunt)
    {
        $this->dateEmprunt = $dateEmprunt;

        return $this;
    }

    /**
     * Get dateEmprunt
     *
     * @return \DateTime 
     */
    public function getDateEmprunt()
    {
        return $this->dateEmprunt;
    }

    /**
     * Set dateRetour
     *
     * @param \DateTime $dateRetour
     * @return Emprunt
     */
    public function setDateRetour($dateRetour)
    {
        $this->dateRetour = $dateRetour;

        return $this;
    }

    /**
     * Get dateRetour
     *
     * @return \DateTime 
     */
    public function getDateRetour()
    {
        return $this->dateRetour;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->exemplaire = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \Bibliotheque\UserBundle\Entity\User $user
     * @return Emprunt
     */
    public function addUser(\Bibliotheque\UserBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Bibliotheque\UserBundle\Entity\User $user
     */
    public function removeUser(\Bibliotheque\UserBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add exemplaire
     *
     * @param \Bibliotheque\UserBundle\Entity\Exemplaire $exemplaire
     * @return Emprunt
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
}
