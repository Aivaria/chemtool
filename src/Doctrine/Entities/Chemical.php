<?php

namespace Chemtool\Doctrine\Entities;

use Doctrine\Common\Collections\Criteria;
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
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    protected ?string $name;

    /**
     * @ORM\Column(type="string", name="id_name", unique="true")
     * @var string
     */
    protected string $idName;

    /**
     * @ORM\Column (type="integer")
     * @var int
     */
    protected int $produced = 1;

    /**
     * @ORM\Column (type="integer", name="require_heat")
     * @var int
     */
    protected int $requireHeat = 0;

    /**
     * @ORM\OneToMany (targetEntity="ChemicalLinker", mappedBy="chemical", cascade={"persist"})
     * @var Collection
     */
    protected Collection $parent;

    /**
     * @ORM\OneToMany (targetEntity="ChemicalLinker", mappedBy="parent", cascade={"persist"})
     * @var Collection
     */
    protected Collection $childs;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="chemicals")
     * @ORM\JoinTable(name="chemical_tag")
     * @var Collection
     */
    protected Collection $tags;

    public function __construct()
    {
        $this->parent = new ArrayCollection();
        $this->childs = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Chemical
     */
    public function setName(string $name): Chemical
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdName(): string
    {
        return $this->idName;
    }

    /**
     * @param string $idName
     * @return Chemical
     */
    public function setIdName(string $idName): Chemical
    {
        $this->idName = $idName;
        return $this;
    }

    /**
     * @return int
     */
    public function getProduced(): int
    {
        return $this->produced;
    }

    /**
     * @param int $produced
     * @return Chemical
     */
    public function setProduced(int $produced): Chemical
    {
        $this->produced = $produced;
        return $this;
    }

    /**
     * @return int
     */
    public function getRequireHeat(): int
    {
        return $this->requireHeat;
    }

    /**
     * @param int $requireHeat
     * @return Chemical
     */
    public function setRequireHeat(int $requireHeat): Chemical
    {
        $this->requireHeat = $requireHeat;
        return $this;
    }

    public function addOrUpdateParent(Chemical $parent, int $amount = 1)
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where($expr->eq('parent', $parent));

        $linker = $this->parent->matching($criteria)->getKeys();
        var_dump($linker);die;
        $parents = $this->parent->filter(['chemical_id' => $this->getId(), 'parent_id' => $parent->getId()]);
        var_dump($parents);
        die;

    }

    public function addParent(Chemical $parent, int $amount = 1)
    {
        $chemicalLink = new ChemicalLinker();
        $chemicalLink->setAmount($amount);
        $chemicalLink->setChemical($this);
        $chemicalLink->setParent($parent);
        $this->parent->add($chemicalLink);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getParent(): ArrayCollection|Collection
    {
        return $this->parent;
    }

    /**
     * @return Collection
     */
    public function getChilds(): ArrayCollection|Collection
    {
        return $this->childs;
    }

    /**
     * @return Collection
     */
    public function getTags(): ArrayCollection|Collection
    {
        return $this->tags;
    }

    /**
     * @param Collection $tags
     * @return Chemical
     */
    public function setTags(ArrayCollection|Collection $tags): Chemical
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @param Tag $tag
     * @return Chemical
     */
    public function addTag(Tag $tag):Chemical{
        $this->tags->add($tag);
        return $this;
    }


}