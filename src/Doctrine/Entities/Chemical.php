<?php

namespace Chemtool\Doctrine\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="chemical")
 */
class Chemical
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", unique="true")
     * @var string
     */
    protected string $name;

    /**
     * @ORM\Column(type="string", name="id_name", unique="true")
     * @var string
     */
    protected string $idName;

    /**
     * @ORM\Column (type="integer")
     * @var int
     */
    protected int $produced;

    /**
     * @ORM\Column (type="integer", name="require_heat")
     * @var int
     */
    protected int $requireHeat;

    /**
     * @ORM\ManyToOne(targetEntity="Chemical", inversedBy="childs")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @var Collection
     */
    protected Collection $parent;

    /**
     * @ORM\OneToMany(targetEntity="Chemical", mappedBy="parent")
     * @var Collection
     */
    protected Collection $childs;
}