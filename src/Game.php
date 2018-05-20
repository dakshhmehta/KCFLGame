<?php

namespace Dakshhmehta\Kcfl;

class Game
{
    protected $players    = [];
    protected $isStarted  = false;
    protected $roundNo    = 0;
    protected $roundColor = null;
    protected $cardCount  = 0;
    protected $scoreCard  = [];
    protected $inverse    = true;

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
        if ($this->isStarted or count($this->getPlayers()) < 2) {
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

        $maxCards = ((count($this->players) < 8) ? 7 : 7 - (count($this->players) - 7));

        if ($this->getRoundNo() == 1) {
            // We are starting the game I guess.
            $this->cardCount = $maxCards;
        } else {
            if($this->inverse){                
                $this->cardCount--;
            }
            else {
                $this->cardCount++;
            }

            if ($maxCards == $this->cardCount-1 || $this->cardCount == 1) {
                $this->inverse = false;
                $this->cardCount = 1;
            }
        }

        return $this;
    }

    public function getRoundColor()
    {
        if (!$this->isStarted) {
            return false;
        }

        $colors = ["K", "C", "F", "L"];

        if ($this->roundNo <= 4) {
            return $colors[$this->roundNo - 1];
        }

        $i = ($this->roundNo - 1) % 4;

        return $colors[$i];
    }

    public function getRoundCardsCount()
    {
        if (!$this->isStarted) {
            return false;
        }

        return $this->cardCount;
    }

    public function getRoundNo()
    {
        if (!$this->isStarted) {
            return false;
        }

        return $this->roundNo;
    }

    public function askScore($playerName, $score)
    {
        if (!$this->isStarted) {
            return false;
        }

        if (!in_array($playerName, $this->getPlayers())) {
            return false;
        }

        if ($score > $this->getRoundCardsCount()) {
            return false;
        }

        $this->scoreCard[$this->getRoundNo()][$playerName] = [
            'color'  => $this->getRoundColor(),
            'cards'  => $this->getRoundCardsCount(),
            'score'  => $score,
            'result' => false,
        ];

        return true;
    }

    public function getScore($playerName)
    {
        if (!in_array($playerName, $this->getPlayers())) {
            return false;
        }

        $score = 0;
        foreach ($this->scoreCard as $rounds) {
            foreach ($rounds as $pName => $information) {
                if ($pName == $playerName) {
                    if ($information['result']) {
                        $score += $information['result'];
                    } else {
                        return false;
                    }
                }
            }
        }

        return $score;
    }

    public function submitScore($playerName, $result)
    {
        $scoreCard = &$this->scoreCard[$this->getRoundNo()][$playerName];

        if ($result == true) {
            $scoreCard['result'] = 10 * (($scoreCard['score'] == 0) ? 1 : $scoreCard['score']);
        } else {
            $scoreCard['result'] = -10 * (($scoreCard['score'] == 0) ? 1 : $scoreCard['score']);
        }

        return true;
    }
}
