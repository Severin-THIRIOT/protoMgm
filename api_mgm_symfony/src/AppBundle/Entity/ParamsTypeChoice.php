<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Item;
use AppBundle\Entity\Param;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * ParamsTypeChoice
 *
 * @ORM\Table(name="params_type_choice")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParamsTypeChoiceRepository")
 */
class ParamsTypeChoice
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * @ManyToOne(targetEntity="Param", inversedBy="paramsTypeChoice")
     * @JoinColumn(name="Param_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $Param;


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
     * Set name
     *
     * @param string $name
     *
     * @return ParamsTypeChoice
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->item = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set param
     *
     * @param \AppBundle\Entity\Param $param
     *
     * @return ParamsTypeChoice
     */
    public function setParam(\AppBundle\Entity\Param $param = null)
    {
        $this->Param = $param;

        return $this;
    }

    /**
     * Get param
     *
     * @return \AppBundle\Entity\Param
     */
    public function getParam()
    {
        return $this->Param;
    }

}
