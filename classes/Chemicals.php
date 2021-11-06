<?php


namespace classes;


class Chemicals
{
    /**
     * @var Chemical[]
     */
    protected $chemicalStorage=[];

    /**
     * @param $id
     * @return Chemical
     */
    public function checkAdd($id)
    {
        $chemical= null;
        if (array_key_exists($id, $this->chemicalStorage)) {
            $chemical = $this->chemicalStorage[$id];
        } else {
            $chemical = new Chemical($id);
            $this->chemicalStorage[$id] = $chemical;
        }
        return $chemical;
    }

    /**
     * @return Chemical[]
     */
    public function getChemicalStorage()
    {
        return $this->chemicalStorage;
    }

    /**
     * @param Chemical[] $chemicalStorage
     * @return Chemicals
     */
    public function setChemicalStorage(array $chemicalStorage)
    {
        $this->chemicalStorage = $chemicalStorage;
        return $this;
    }


}