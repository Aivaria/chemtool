<?php


namespace classes;


class Step
{
    /**
     * @var array
     */
    protected $chemicals;

    /**
     * @var string
     */
    protected $comment;

    public function __toString(): string
    {
        return $this->generateString();
    }

    public function generateString(): string
    {
        $forReturn = "";
        foreach ($this->chemicals??[] as $chemical => $ammount) {
            $forReturn .= $chemical . '=' . $ammount . ";";
        }
        return $forReturn;
    }

    public function addChemical(Chemical $chemical, $ammount): Step
    {
        if (isset($this->chemicals[$chemical->getId()])) {
            $this->chemicals[$chemical->getId()] += $ammount;
        } else {
            $this->chemicals[$chemical->getId()] = $ammount;
        }
        return $this;
    }

    public function addComment($comment): Step
    {
        $this->comment = $comment;
        return $this;
    }

    public function getComment(): string
    {
        return $this->comment;

    }
}