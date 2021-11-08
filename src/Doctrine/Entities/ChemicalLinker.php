<?php

namespace Chemtool\Doctrine\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="chemical_linker", uniqueConstraints={@ORM\UniqueConstraint(name="chemical_parent_unique", columns={"chemical_id", "parent_id"})})
 */
class ChemicalLinker
{
    /**
     * @ORM\id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Chemical", inversedBy="parent", cascade={"persist"})
     * @ORM\JoinColumn(name="chemical_id", referencedColumnName="id", nullable=false)
     * @var Chemical
     */
    protected Chemical $chemical;

    /**
     * @ORM\ManyToOne(targetEntity="Chemical", inversedBy="childs", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=false)
     * @var Chemical
     */
    protected Chemical $parent;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected int $amount;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Chemical
     */
    public function getChemical(): Chemical
    {
        return $this->chemical;
    }

    /**
     * @return Chemical
     */
    public function getParent(): Chemical
    {
        return $this->parent;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return ChemicalLinker
     */
    public function setAmount(int $amount): ChemicalLinker
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param Chemical $chemical
     * @return ChemicalLinker
     */
    public function setChemical(Chemical $chemical): ChemicalLinker
    {
        $this->chemical = $chemical;
        return $this;
    }

    /**
     * @param Chemical $parent
     * @return ChemicalLinker
     */
    public function setParent(Chemical $parent): ChemicalLinker
    {
        $this->parent = $parent;
        return $this;
    }


}