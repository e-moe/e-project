<?php

namespace Levi9\EProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Row
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Row
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\Length(max="255")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="1", max="255")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer")
     * @Assert\GreaterThan(value="0")
     */
    private $count;


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
     * @return Row
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
     * @return Row
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
     * Set count
     *
     * @param integer $count
     * @return Row
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }
}
