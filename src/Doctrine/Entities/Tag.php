<?php

namespace Chemtool\Doctrine\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag
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
     * @ORM\ManyToMany(targetEntity="Chemical", mappedBy="tags")
     * @var Collection
     */
    protected Collection $chemicals;

    public function __construct()
    {
        $this->chemicals = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Tag
     */
    public function setName(string $name): Tag
    {
        $this->name = $name;
        return $this;
    }


}