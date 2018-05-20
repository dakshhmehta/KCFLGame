<?php

namespace Dakshhmehta\Kcfl;

class Game
{
    protected $players    = [];
    protected $isStarted  = false;
    protected $roundNo    = 0;
    protected $roundColor = null;
    protected $cardCount  = 0;

    public function addPlayer($name)
    {
        if ($this->isStarted) {
            return false;
        }

        if (is_array($name)) {
            foreach ($name as &$n) {
                $this->players[] = $n;
            }

            return true;
        }

        $this->players[] = $name;

        return true;
    }

    public function start()
    {
        if ($this->isStarted) {
            return false;
        }

        $this->isStarted = true;

        $this->nextRound();

        return $this->isStarted;
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function nextRound()
    {
        $this->roundNo++;

        if ($this->cardCount == 0) {
            // We are starting the game I guess.
            $this->cardCount = ((count($this->players) < 8) ? 7 : count($this->players) - 7);
        }

        return $this;
    }

    public function getRoundColor()
    {
        if(! $this->isStarted) return false;

        $colors = ["K", "C", "F", "L"];

        if ($this->roundNo <= 4) {
            return $colors[$this->roundNo - 1];
        }

        return "K";
    }

    public function getRoundCardsCount()
    {
        if(! $this->isStarted) return false;

        return $this->cardCount;
    }

    public function getRoundNo()
    {
        if(! $this->isStarted) return false;

        return $this->roundNo;
    }
}
