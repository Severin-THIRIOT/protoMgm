<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Item;
use AppBundle\Entity\Param;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * ParamsTypeInt
 *
 * @ORM\Table(name="params_type_int")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParamsTypeIntRepository")
 */
class ParamsTypeInt
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
     * @var int
     *
     * @ORM\Column(name="intValue", type="integer")
     */
    private $intValue;

    /**
     * @ManyToOne(targetEntity="Param", inversedBy="paramsTypeInt")
     * @JoinColumn(name="Param_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $Param;


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
     * Set intValue
     *
     * @param integer $intValue
     *
     * @return ParamsTypeInt
     */
    public function setIntValue($intValue)
    {
        $this->intValue = $intValue;

        return $this;
    }

    /**
     * Get intValue
     *
     * @return integer
     */
    public function getIntValue()
    {
        return $this->intValue;
    }

    /**
     * Set param
     *
     * @param \AppBundle\Entity\Param $param
     *
     * @return ParamsTypeInt
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
