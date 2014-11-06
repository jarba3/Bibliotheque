<?php

namespace Bibliotheque\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exemplaire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bibliotheque\UserBundle\Repository\ExemplaireRepository")
 */
class Exemplaire
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
     * @ORM\Column(name="date_acquisition", type="date")
     */
    private $dateAcquisition;

    /**
     * @var string
     *
     * @ORM\Column(name="usure", type="string", length=255)
     */
    private $usure;

    /**
     * @ORM\ManyToOne(targetEntity="Bibliotheque\UserBundle\Entity\Livres")
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
     * Set dateAcquisition
     *
     * @param \DateTime $dateAcquisition
     * @return Exemplaire
     */
    public function setDateAcquisition($dateAcquisition)
    {
        $this->dateAcquisition = $dateAcquisition;

        return $this;
    }

    /**
     * Get dateAcquisition
     *
     * @return \DateTime 
     */
    public function getDateAcquisition()
    {
        return $this->dateAcquisition;
    }

    /**
     * Set usure
     *
     * @param string $usure
     * @return Exemplaire
     */
    public function setUsure($usure)
    {
        $this->usure = $usure;

        return $this;
    }

    /**
     * Get usure
     *
     * @return string 
     */
    public function getUsure()
    {
        return $this->usure;
    }

    /**
     * Set livre
     *
     * @param \Bibliotheque\UserBundle\Entity\Livres $livre
     * @return Exemplaire
     */
    public function setLivre(\Bibliotheque\UserBundle\Entity\Livres $livre = null)
    {
        $this->livre = $livre;

        return $this;
    }

    /**
     * Get livre
     *
     * @return \Bibliotheque\UserBundle\Entity\Livres 
     */
    public function getLivre()
    {
        return $this->livre;
    }
}
