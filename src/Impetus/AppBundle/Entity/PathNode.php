<?php

namespace Impetus\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="path_node")
 */
class PathNode {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="float")
     */
    protected $h_pos;

    /**
     * @ORM\Column(type="float")
     */
    protected $v_pos;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $color;

    /**
     * @ORM\OneToOne(targetEntity="PathData")
     */
    protected $data;

    /**
     * @ORM\ManyToMany(targetEntity="PathNode")
     * @ORM\JoinTable(
     *     name="path_node_prereqs",
     *     joinColumns={ @ORM\JoinColumn(name="node_id", referencedColumnName="id") },
     *     inverseJoinColumns={ @ORM\JoinColumn(name="prereq_node_id", referencedColumnName="id") }
     * )
     */
    protected $prereqs;


    public function __construct()
    {
        $this->prereqs = new \Doctrine\Common\Collections\ArrayCollection();
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
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Set h_pos
     *
     * @param float $hPos
     */
    public function setHPos($hPos)
    {
        $this->h_pos = $hPos;
    }

    /**
     * Get h_pos
     *
     * @return float
     */
    public function getHPos()
    {
        return $this->h_pos;
    }

    /**
     * Set v_pos
     *
     * @param float $vPos
     */
    public function setVPos($vPos)
    {
        $this->v_pos = $vPos;
    }

    /**
     * Get v_pos
     *
     * @return float
     */
    public function getVPos()
    {
        return $this->v_pos;
    }

    /**
     * Set color
     *
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set data
     *
     * @param Impetus\AppBundle\Entity\PathData $data
     */
    public function setData(\Impetus\AppBundle\Entity\PathData $data)
    {
        $this->data = $data;
    }

    /**
     * Get data
     *
     * @return Impetus\AppBundle\Entity\PathData
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Add prereqs
     *
     * @param Impetus\AppBundle\Entity\PathNode $prereqs
     */
    public function addPrereqs(\Impetus\AppBundle\Entity\PathNode $prereqs)
    {
        $this->prereqs[] = $prereqs;
    }

    /**
     * Get prereqs
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPrereqs()
    {
        return $this->prereqs;
    }
}