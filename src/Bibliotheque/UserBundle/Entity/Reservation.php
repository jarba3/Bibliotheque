<?php

namespace Bibliotheque\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bibliotheque\UserBundle\Repository\ReservationRepository")
 */
class Reservation
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
     * @ORM\Column(name="date_reservation", type="date")
     */
    private $dateReservation;

     /**
    * @var ArrayCollection $user
    *
    * @ORM\OneToMany(targetEntity="User", mappedBy="reservation", cascade={"persist"})
    */
    private $user;

     /**
    * @var ArrayCollection $livre
    *
    * @ORM\OneToMany(targetEntity="Livres", mappedBy="reservation", cascade={"persist"})
    */
    private $livre;


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
     * Set dateReservation
     *
     * @param \DateTime $dateReservation
     * @return Reservation
     */
    public function setDateReservation($dateReservation)
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    /**
     * Get dateReservation
     *
     * @return \DateTime 
     */
    public function getDateReservation()
    {
        return $this->dateReservation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->livre = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \Bibliotheque\UserBundle\Entity\User $user
     * @return Reservation
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
     * Add livre
     *
     * @param \Bibliotheque\UserBundle\Entity\Livres $livre
     * @return Reservation
     */
    public function addLivre(\Bibliotheque\UserBundle\Entity\Livres $livre)
    {
        $this->livre[] = $livre;

        return $this;
    }

    /**
     * Remove livre
     *
     * @param \Bibliotheque\UserBundle\Entity\Livres $livre
     */
    public function removeLivre(\Bibliotheque\UserBundle\Entity\Livres $livre)
    {
        $this->livre->removeElement($livre);
    }

    /**
     * Get livre
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLivre()
    {
        return $this->livre;
    }
}
