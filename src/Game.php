<?php

namespace Dakshhmehta\Kcfl;

class Game
{
    protected $players    = [];
    protected $isStarted  = false;
    protected $roundNo    = 0;
    protected $roundColor = null;
    protected $cardCount  = 0;
    protected $scoreCard = [];

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
        else {
            $this->cardCount--;
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

        $i = ($this->roundNo-1)%4;

        return $colors[$i];
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

    public function askScore($playerName, $score)
    {
        if(! $this->isStarted) return false;
        if(! in_array($playerName, $this->players)) return false;
        if($score > $this->getRoundCardsCount()) return false;

        return true;
    }
}
