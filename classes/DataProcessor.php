<?php

namespace classes;


class DataProcessor
{
    /**
     * @var Chemicals $chemicals
     */
    protected $chemicals;

    public function __construct($path)
    {
        $this->loadJson($path);
        $this->sortData();
        $this->startGenerateExtraData();
    }

    /**
     * @return Chemical[]
     */
    public function getChemicals(): array
    {
        return $this->chemicals->getChemicalStorage();
    }

    protected function loadJson($path)
    {
        $json = json_decode(file_get_contents($path));
        $this->chemicals = new Chemicals();

        foreach ($json as $id => $data) {
            $chemical = $this->chemicals->checkAdd($id);
            $chemical->setName($data->name ?? $id);
            $chemical->setProduced($data->produced ?? 1);
            $chemical->setRequiredHeat($data->requiresHeat ?? 0);
            $chemical->setTags($data->tags ?? []);


            foreach ($data->parents ?? [] as $key => $parentData) {
                $parent = $this->chemicals->checkAdd($key);
                $chemical->addParent($parent, $parentData);
            }
        }
    }

    protected function sortData()
    {
        foreach ($this->chemicals->getChemicalStorage() as $chemical) {
            if (in_array('medicine', $chemical->getTags())) {
                $medicine[$chemical->getId()] = $chemical;
            } elseif (in_array('poison', $chemical->getTags())) {
                $poison[$chemical->getId()] = $chemical;
            } elseif (in_array('disease', $chemical->getTags())) {
                $disease[$chemical->getId()] = $chemical;
            } elseif (in_array('acid', $chemical->getTags())) {
                $acid[$chemical->getId()] = $chemical;
            } elseif (in_array('pyrotechnic', $chemical->getTags())) {
                $pyrotechnic[$chemical->getId()] = $chemical;
            } else {
                $other[$chemical->getId()] = $chemical;
            }
        }
        $this->chemicals->setChemicalStorage(array_merge($medicine, $poison, $pyrotechnic, $disease, $acid, $other));
    }

    protected function startGenerateExtraData()
    {
        foreach ($this->chemicals->getChemicalStorage() as $chemical) {
            $steps = new Steps();
            $chemical->setSteps($steps);
            $this->checkParentBase($chemical);
            $this->buildRecipe($chemical);
        }
//        $this->buildRecipe($this->chemicals->getChemicalStorage()['epinephrine']);

    }

    protected function getLowestBaseNumber(Chemical $chemical, $parentValue = 1, $parentProduced = 1)
    {
        $forReturn = [];
        $myAmmount = $parentValue / $parentProduced;

        $forReturn = ['chemical' => $chemical->getId(), 'ammount' => $myAmmount, 'heat' => ($chemical->getRequiredHeat() > 0 ? $chemical->getRequiredHeat() : 0), 'isBase' => !is_array($chemical->getParents())];

        if (is_array($chemical->getParents())) {
            foreach ($chemical->getParents() as $name => $value) {
                $parent = $this->chemicals->getChemicalStorage()[$name];
                $returned = $this->getLowestBaseNumber($parent, $myAmmount * $value, $chemical->getProduced());
                $forReturn['child'][] = $returned;
            }
        }
        return $forReturn;
    }

    protected function isModuloNotZero($chem, $multiplikator)
    {
        $number = round(($chem['ammount'] * $multiplikator), 1);
        $forReturn = fmod($number, 1) != 0;
        if($forReturn==true)
        {
            return $forReturn;
        }
        if (isset($chem['child'])) {
            foreach ($chem['child'] as $child) {
                if($this->isModuloNotZero($child, $multiplikator))
                {
                    return true;
                }
                else {
                    $forReturn = false;
                }
            }
        }
        return $forReturn;
    }

    protected function buildSteps($chemical, steps $steps, $multiplikator)
    {
        if (isset($chemical['child'])) {
            foreach ($chemical['child'] as $child) {
                $this->buildSteps($child, $steps, $multiplikator);
            }
        }
        if ($chemical['isBase']) {
            $steps->getStep()->addChemical($this->chemicals->getChemicalStorage()[$chemical['chemical']], $chemical['ammount'] * $multiplikator);
        }
        if($chemical['heat']>0)
        {
            $steps->getStep()->addComment('heat');
            $steps->nextStep();
        }

    }


    protected function buildRecipe(Chemical $chemical)
    {
        $results = $this->getLowestBaseNumber($chemical);
        $multiplikator = 0;
        $moduloNotZero = true;
        do {
            $multiplikator++;
            if ($multiplikator >= 6000) {
                break;
            }
            if (isset($results['child'])) {
                $moduloNotZero = $this->isModuloNotZero($results, $multiplikator);
            } else {
                $number = round(($results['ammount'] * $multiplikator), 1);
                if (fmod($number, 1) == 0) {
                    $moduloNotZero = false;
                }
            }
        } while ($moduloNotZero);

        $steps = $chemical->getSteps();
        $this->buildSteps($results, $steps, $multiplikator);
        echo $chemical->getId().'('.$multiplikator.'):    ';
        /**
         * @var Step $step
         */
        foreach ($steps->getSteps() as $step)
        {
            echo $step->generateString();
            echo '<br />';
        }
    }

    protected function checkParentBase(Chemical $parent)
    {
        $forReturn = [];
        $chemicalStorage = $this->chemicals->getChemicalStorage();
        if (is_array($parent->getParents())) {
            foreach ($parent->getParents() as $key => $singleParent) {
                $chemical = $chemicalStorage[$key];
                if (isset($chemical)) {
                    if (is_array($chemical->getMissingParentsBase()) || $this->checkParentBase($chemicalStorage[$key]) !== true) {
                        $forReturn[] = $key;
                    }
                }
            }
            if (count($forReturn) > 0) {
                $parent->setMissingParentsBase($forReturn);
                return false;
            }
            return true;
        } else {
            if (in_array('base', $parent->getTags())) {
                return true;
            }
            $parent->setMissingParentsBase([]);
            return false;
        }
    }
}