<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use AppBundle\Entity\ItemList;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Param
 *
 * @ORM\Table(name="param")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParamRepository")
 */
class Param
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMax", type="datetime", nullable=true)
     */
    private $dateMax;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMin", type="datetime", nullable=true)
     */
    private $dateMin;

    /**
     * @var int
     *
     * @ORM\Column(name="intMax", type="integer", nullable=true)
     */
    private $intMax;

    /**
     * @var int
     *
     * @ORM\Column(name="intMin", type="integer", nullable=true)
     */
    private $intMin;

    /**
     * @ManyToOne(targetEntity="ItemList")
     * @JoinColumn(name="ItemList_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $itemList;


    /**
     * @OneToMany(targetEntity="ParamsTypeChoice", mappedBy="Param")
     * @JoinColumn(name="ParamsTypeChoice_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $paramsTypeChoice;

    /**
     * @OneToMany(targetEntity="ParamsTypeInt", mappedBy="Param")
     * @JoinColumn(name="ParamsTypeInt_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $paramsTypeInt;

    /**
     * @OneToMany(targetEntity="ParamsTypeDate", mappedBy="Param")
     * @JoinColumn(name="ParamsTypeDate_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $paramsTypeDate;



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
     * @return Param
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
     * Set type
     *
     * @param string $type
     *
     * @return Param
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set itemList
     *
     * @param \AppBundle\Entity\ItemList $itemList
     *
     * @return Param
     */
    public function setItemList(\AppBundle\Entity\ItemList $itemList = null)
    {
        $this->itemList = $itemList;

        return $this;
    }

    /**
     * Get itemList
     *
     * @return \AppBundle\Entity\ItemList
     */
    public function getItemList()
    {
        return $this->itemList;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->paramsTypeChoice = new \Doctrine\Common\Collections\ArrayCollection();
        $this->paramsTypeInt = new \Doctrine\Common\Collections\ArrayCollection();
        $this->paramsTypeDate = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add paramsTypeChoice
     *
     * @param \AppBundle\Entity\ParamsTypeChoice $paramsTypeChoice
     *
     * @return Param
     */
    public function addParamsTypeChoice(\AppBundle\Entity\ParamsTypeChoice $paramsTypeChoice)
    {
        $this->paramsTypeChoice[] = $paramsTypeChoice;

        return $this;
    }

    /**
     * Remove paramsTypeChoice
     *
     * @param \AppBundle\Entity\ParamsTypeChoice $paramsTypeChoice
     */
    public function removeParamsTypeChoice(\AppBundle\Entity\ParamsTypeChoice $paramsTypeChoice)
    {
        $this->paramsTypeChoice->removeElement($paramsTypeChoice);
    }

    /**
     * Get paramsTypeChoice
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParamsTypeChoice()
    {
        return $this->paramsTypeChoice;
    }

    /**
     * Add paramsTypeInt
     *
     * @param \AppBundle\Entity\ParamsTypeInt $paramsTypeInt
     *
     * @return Param
     */
    public function addParamsTypeInt(\AppBundle\Entity\ParamsTypeInt $paramsTypeInt)
    {
        $this->paramsTypeInt[] = $paramsTypeInt;

        return $this;
    }

    /**
     * Remove paramsTypeInt
     *
     * @param \AppBundle\Entity\ParamsTypeInt $paramsTypeInt
     */
    public function removeParamsTypeInt(\AppBundle\Entity\ParamsTypeInt $paramsTypeInt)
    {
        $this->paramsTypeInt->removeElement($paramsTypeInt);
    }

    /**
     * Get paramsTypeInt
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParamsTypeInt()
    {
        return $this->paramsTypeInt;
    }

    /**
     * Add paramsTypeDate
     *
     * @param \AppBundle\Entity\ParamsTypeDate $paramsTypeDate
     *
     * @return Param
     */
    public function addParamsTypeDate(\AppBundle\Entity\ParamsTypeDate $paramsTypeDate)
    {
        $this->paramsTypeDate[] = $paramsTypeDate;

        return $this;
    }

    /**
     * Remove paramsTypeDate
     *
     * @param \AppBundle\Entity\ParamsTypeDate $paramsTypeDate
     */
    public function removeParamsTypeDate(\AppBundle\Entity\ParamsTypeDate $paramsTypeDate)
    {
        $this->paramsTypeDate->removeElement($paramsTypeDate);
    }

    /**
     * Get paramsTypeDate
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParamsTypeDate()
    {
        return $this->paramsTypeDate;
    }

    /**
     * Set dateMax
     *
     * @param \DateTime $dateMax
     *
     * @return Param
     */
    public function setDateMax($dateMax)
    {
        $this->dateMax = $dateMax;

        return $this;
    }

    /**
     * Get dateMax
     *
     * @return \DateTime
     */
    public function getDateMax()
    {
        return $this->dateMax;
    }

    /**
     * Set dateMin
     *
     * @param \DateTime $dateMin
     *
     * @return Param
     */
    public function setDateMin($dateMin)
    {
        $this->dateMin = $dateMin;

        return $this;
    }

    /**
     * Get dateMin
     *
     * @return \DateTime
     */
    public function getDateMin()
    {
        return $this->dateMin;
    }

    /**
     * Set intMax
     *
     * @param integer $intMax
     *
     * @return Param
     */
    public function setIntMax($intMax)
    {
        $this->intMax = $intMax;

        return $this;
    }

    /**
     * Get intMax
     *
     * @return integer
     */
    public function getIntMax()
    {
        return $this->intMax;
    }

    /**
     * Set intMin
     *
     * @param integer $intMin
     *
     * @return Param
     */
    public function setIntMin($intMin)
    {
        $this->intMin = $intMin;

        return $this;
    }

    /**
     * Get intMin
     *
     * @return integer
     */
    public function getIntMin()
    {
        return $this->intMin;
    }
}
