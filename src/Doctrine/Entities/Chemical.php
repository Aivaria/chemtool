<?php

namespace Chemtool\Doctrine\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\OneToMany (targetEntity="ChemicalLinker", mappedBy="parentChemical", cascade={"persist"})
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
        var_dump($linker);
        die;
        $parents = $this->parent->filter(['chemical_id' => $this->getId(), 'parent_id' => $parent->getId()]);
        var_dump($parents);
        die;

    }

    public function addParent(Chemical $parent, int $amount = 1)
    {
        $chemicalLink = new ChemicalLinker();
        $chemicalLink->setAmount($amount);
        $chemicalLink->setChemical($this);
        $chemicalLink->setParentChemical($parent);
        $this->parent->add($chemicalLink);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getParents(): ArrayCollection|Collection
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
    public function addTag(Tag $tag): Chemical
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
        return $this;
    }

    /**
     * @return
     */
    public function getRecipe($needed = 1, $multiplicator = 1, &$steps = [], $depth = 0)
    {
        if ($depth == 0) {
            $needed = $this->getLCM();
        }
        /**
         * @var ChemicalLinker $parent
         */
        $step = '';


        foreach ($this->getParents() as $parent) {
            if (count($parent->getParentChemical()->getParents()) > 0) {
                $parent->getParentChemical()->getRecipe($needed, 1, $steps, ++$depth);
                continue;
            }
            $step .= $parent->getParentChemical()->getName() . '=' . $parent->getAmount(). ';';
        }

        if ($this->getRequireHeat() > 0) {
            $step .= '   #heat!';
        }

        $steps['string'][] = $step;
        return $steps;
    }

    public function getLCM(): int
    {
        $myLCM = $this->getProduced();
        foreach ($this->getParents() as $relation) {
            $myLCM = gmp_lcm($relation->getParentChemical()->getLCM(), $myLCM);
        }
        return (int)$myLCM;

    }

//    public function getLCM(int $needed = 1, $_timesReceipt = 1): int  // 5 1 1 1 1, || 4
//    {
//        $myLCM = $this->getProduced();  // 3
//
//        $lcm = 1;
//        foreach ($this->getParents() as $relation) {
//            $lcm = gmp_lcm($relation->getParentChemical()->getProduced(), $lcm);  //1
//        }
//        $lcm = gmp_lcm($lcm, $_timesReceipt); //4
//
//        if (($needed * $lcm) % $this->getProduced() != 0) {
//            $timesReceipt = gmp_lcm($_timesReceipt, $this->getProduced());
//            var_dump(((int)$timesReceipt /  (int)$_timesReceipt));
//            return ((int)$timesReceipt / (int)$_timesReceipt);
//
//        } else {
//            $timesReceipt = $needed * $lcm / $this->getProduced(); // 1,33333333333
//        }

//        foreach ($this->getParents() as $relation) {
//            $myLCM = $relation->getParentChemical()->getLCM($relation->getAmount(), $timesReceipt) * $myLCM;
//        }
//
//        if(is_int($myLCM)){
//            $myLCM = gmp_lcm($myLCM, $lcm); //4
//        }
//        else{
//            var_dump($this->getName());
//            var_dump($myLCM);
//            die();
//
//        }
//        return (int)$myLCM;
//    }
}