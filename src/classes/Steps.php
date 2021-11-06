<?php


namespace Chemtool;


class Steps
{
    /**
     * @var array
     */
    protected $steps;

    public function __construct()
    {
        $this->steps[]=new Step();
    }

    /**
     * @return $this
     */
    public function nextStep(): Steps
    {
        $this->steps[] = new Step();
        return $this;
    }

    /**
     * @return Step
     */
    public function getStep(): Step
    {
        return end($this->steps);
    }

    /**
     * @return array
     */
    public function getSteps(): array
    {
        return $this->steps;
    }
}