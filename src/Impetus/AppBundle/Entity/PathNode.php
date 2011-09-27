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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $h_pos;

    /**
     * @ORM\Column(type="float")
     */
    private $v_pos;

    /**
     * @ORM\ManyToMany(targetEntity="PathNode")
     * @ORM\JoinTable(
     *     name="path_node_prereqs",
     *     joinColumns={ @ORM\JoinColumn(name="node_id", referencedColumnName="id") },
     *     inverseJoinColumns={ @ORM\JoinColumn(name="prereq_node_id", referencedColumnName="id") }
     * )
     */
    private $prereqs;

    public function __construct() {
        $this->prereqs = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setHPos($hPos)
    {
        $this->h_pos = $hPos;
    }

    public function getHPos()
    {
        return $this->h_pos;
    }

    public function setVPos($vPos)
    {
        $this->v_pos = $vPos;
    }

    public function getVPos()
    {
        return $this->v_pos;
    }

    public function addPathNode(\Impetus\AppBundle\Entity\PathNode $prereqs)
    {
        $this->prereqs[] = $prereqs;
    }

    public function getPrereqs()
    {
        return $this->prereqs;
    }
}