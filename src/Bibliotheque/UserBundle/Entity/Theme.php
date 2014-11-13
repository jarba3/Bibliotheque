<?php

namespace Bibliotheque\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Theme
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bibliotheque\UserBundle\Repository\ThemeRepository")
 */
class Theme
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
     * @ORM\Column(name="intitule", type="string", length=255)
     */
    private $intitule;

    /**
    * @var ArrayCollection $livre
    *
    * @ORM\OneToMany(targetEntity="Livres", mappedBy="theme", cascade={"persist", "remove"})
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
     * Set intitule
     *
     * @param string $intitule
     * @return Theme
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string 
     */
    public function getIntitule()
    {
        return $this->intitule;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->livre = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add livre
     *
     * @param \Bibliotheque\UserBundle\Entity\Livres $livre
     * @return Theme
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
