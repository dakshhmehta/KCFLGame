<?php

namespace spec\Dakshhmehta\Kcfl;

use Dakshhmehta\Kcfl\Game;
use PhpSpec\ObjectBehavior;

class GameSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Game::class);
    }

    public function it_can_add_player()
    {
        $this->addPlayer("Daksh")->shouldReturn(true);
    }

    public function it_can_add_multiple_players_at_once()
    {
        $this->addPlayer(["Daksh", "Tirth"]);
        $this->getPlayers()->shouldHaveCount(2);
    }

    public function it_can_list_players()
    {
        $this->addPlayer("Daksh");
        $this->getPlayers()->shouldHaveCount(1);

        $this->addPlayer("Tirth");
        $this->getPlayers()->shouldHaveCount(2);
    }

    public function it_can_start_game()
    {
        $this->start()->shouldReturn(true);
    }

    public function it_can_not_add_player_after_game_started()
    {
        $this->start()->shouldReturn(true);

        $this->addPlayer("Ila")->shouldReturn(false);
    }

    public function it_can_not_be_started_twice()
    {
        $this->start()->shouldReturn(true);
        $this->start()->shouldReturn(false);
    }

    public function it_should_follow_KCFL_color()
    {
    	$this->start();

        $this->nextRound();
        $this->getRoundColor()->shouldReturn('K');

        $this->nextRound();
        $this->getRoundColor()->shouldReturn('C');

        $this->nextRound();
        $this->getRoundColor()->shouldReturn('F');

        $this->nextRound();
        $this->getRoundColor()->shouldReturn('L');

        $this->nextRound();
        $this->getRoundColor()->shouldReturn('K');
    }

    public function it_cant_return_any_round_information_when_game_not_started()
    {
    	$this->getRoundColor()->shouldReturn(false);
    	$this->getRoundNo()->shouldReturn(false);
    	$this->getRoundCardsCount()->shouldReturn(false);
    }

    public function it_should_return_correct_round_no()
    {
    	$this->start();

        $this->getRoundNo()->shouldReturn(1);

        $this->nextRound();
        $this->getRoundNo()->shouldReturn(2);
    }

    public function it_should_return_correct_card_count()
    {
        $this->addPlayer(["Daksh", "Tirth"]);

        $this->start();
        $this->getRoundCardsCount()->shouldReturn(7);
    }

    public function when_round_increases_card_decreases()
    {
        $this->start();
        $this->getRoundCardsCount()->shouldReturn(7);

        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(6);

        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(5);

        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(4);
    }
}
