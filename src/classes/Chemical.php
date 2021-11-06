<?php

namespace Chemtool;

use http\Encoding\Stream;

class Chemical
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $id;
    /**
     * @var Chemical[]
     */
    protected $parents;

    /**
     * @var Chemical[];
     */
    protected $missingParentsBase=null;

    /**
     * @var int
     */
    protected $produced=1;
    /**
     * @var int
     */
    protected $requiredHeat = 0;

    /**
     * @var string[]
     */
    protected $tags=[];

    /**
     * @var Steps;
     */
    protected $steps;

    /**
     * Chemical constructor.
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Chemical[]
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * @param Chemical $parent
     * @param int $amount
     * @return $this
     */
    public function addParent(Chemical $parent, int $amount)
    {
        $this->parents[$parent->getId()] = $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getProduced()
    {
        return $this->produced;
    }

    /**
     * @param int $produced
     * @return Chemical
     */
    public function setProduced($produced)
    {
        $this->produced = $produced;
        return $this;
    }

    /**
     * @return int
     */
    public function getRequiredHeat()
    {
        return $this->requiredHeat;
    }

    /**
     * @param int $requiredHeat
     * @return Chemical
     */
    public function setRequiredHeat($requiredHeat)
    {
        $this->requiredHeat = $requiredHeat;
        return $this;
    }

    /**
     * @param string $id
     * @return Chemical
     */
    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     * @return Chemical
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function addTag($tag)
    {
        $this->tags[] = $tag;
        return $this;
    }

    /**
     * @return ?Chemical[]
     */
    public function getMissingParentsBase(): ?array
    {
        return $this->missingParentsBase;
    }

    /**
     * @param Chemical[] $missingParentsBase
     * @return Chemical
     */
    public function setMissingParentsBase(array $missingParentsBase)
    {
        $this->missingParentsBase = $missingParentsBase;
        return $this;
    }

    /**
     * @return Steps
     */
    public function getSteps(): Steps
    {
        return $this->steps;
    }

    /**
     * @param Steps $steps
     * @return Chemical
     */
    public function setSteps(Steps $steps)
    {
        $this->steps = $steps;
        return $this;
    }
}