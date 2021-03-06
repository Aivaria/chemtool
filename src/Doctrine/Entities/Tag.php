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
     * @ORM\Column(type="integer", nullable=true)
     * @var ?int
     */
    protected ?int $priority = 0;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    protected bool $hidden = false;

    /**
     * @ORM\Column (type="string", nullable=true);
     * @var string
     */
    protected ?string $color;


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

    /**
     * @return ?int
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return Tag
     */
    public function setPriority(int $priority): Tag
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     * @return Tag
     */
    public function setHidden(bool $hidden): Tag
    {
        $this->hidden = $hidden;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return Tag
     */
    public function setColor(string $color): Tag
    {
        $this->color = $color;
        return $this;
    }


}