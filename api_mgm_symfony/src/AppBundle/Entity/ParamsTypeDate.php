<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Item;
use AppBundle\Entity\Param;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * ParamsTypeDate
 *
 * @ORM\Table(name="params_type_date")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParamsTypeDateRepository")
 */
class ParamsTypeDate
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
     * @var \DateTime
     *
     * @ORM\Column(name="singleDate", type="datetime")
     */
    private $dateValue;

    /**
     * @ManyToOne(targetEntity="Param" , inversedBy="paramsTypeDate")
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
     * Set dateValue
     *
     * @param \DateTime $dateValue
     *
     * @return ParamsTypeDate
     */
    public function setDateValue($dateValue)
    {
        $this->dateValue = $dateValue;

        return $this;
    }

    /**
     * Get dateValue
     *
     * @return \DateTime
     */
    public function getDateValue()
    {
        return $this->dateValue;
    }

    /**
     * Set param
     *
     * @param \AppBundle\Entity\Param $param
     *
     * @return ParamsTypeDate
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
