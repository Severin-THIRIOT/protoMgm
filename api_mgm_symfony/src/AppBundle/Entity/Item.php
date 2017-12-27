<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use AppBundle\Entity\ItemList;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemRepository")
 */
class Item
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="File should not be blank.")
     * @Assert\File(
     *     mimeTypes={"image/jpeg", "image/png", "image/gif"},
     *     maxSize="1074000000"
     * )
     *
     * @var UploadedFile
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var boolean
     *
     * @ORM\Column(name="completed", type="boolean")
     */
    private $completed = false;


    /**
     * @ManyToOne(targetEntity="ItemList")
     * @JoinColumn(name="ItemList_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $itemList;

    /**
     * @ManyToMany(targetEntity="ParamsTypeChoice")
     * @JoinColumn(name="ParamsTypeChoice_id", referencedColumnName="id")
     */
    private $paramsTypeChoice;

    /**
     * @ManyToMany(targetEntity="ParamsTypeInt")
     * @JoinColumn(name="ParamsTypeInt_id", referencedColumnName="id")
     */
    private $paramsTypeInt;

    /**
     * @ManyToMany(targetEntity="ParamsTypeDate")
     * @JoinColumn(name="ParamsTypeDate_id", referencedColumnName="id")
     */
    private $paramsTypeDate;

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
     * Get id
     *
     * @return integer
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
     * @return Item
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Item
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set img
     *
     * @param string $img
     *
     * @return Item
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Item
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
     * Set price
     *
     * @param float $price
     *
     * @return Item
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set completed
     *
     * @param boolean $completed
     *
     * @return Item
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return boolean
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set itemList
     *
     * @param \AppBundle\Entity\ItemList $itemList
     *
     * @return Item
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
     * Add paramsTypeChoice
     *
     * @param \AppBundle\Entity\ParamsTypeChoice $paramsTypeChoice
     *
     * @return Item
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
     * @return Item
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
     * @return Item
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
}
