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
    	$this->addPlayer(["Daksh", "Tirth"]);
        $this->start()->shouldReturn(true);
    }

    public function it_cant_start_game_without_minimum_of_two_players()
    {
    	$this->addPlayer("Daksh");
    	$this->start()->shouldReturn(false);

    	$this->addPlayer("Tirth");
    	$this->start()->shouldReturn(true);
    }

    public function it_can_not_add_player_after_game_started()
    {
    	$this->addPlayer(["Daksh", "Tirth"]);
        $this->start()->shouldReturn(true);

        $this->addPlayer("Ila")->shouldReturn(false);
    }

    public function it_can_not_be_started_twice()
    {
    	$this->addPlayer(["Daksh", "Tirth"]);
        $this->start()->shouldReturn(true);
        $this->start()->shouldReturn(false);
    }

    public function it_automatically_start_first_round()
    {
    	$this->addPlayer(["Daksh", "Tirth"]);
        $this->start();
        $this->getRoundNo()->shouldReturn(1);
    }

    public function it_can_not_record_score_for_player_not_playing()
    {
        $this->addPlayer(["Daksh", "Hiren"]);
        $this->start();
        $this->askScore("Tirth", 5)->shouldReturn(false);
        $this->askScore("Daksh", 5)->shouldReturn(true);
    }

    public function it_can_not_record_score_more_than_card_no()
    {
        $this->addPlayer(["Daksh", "Tirth"]);
        $this->start(); // 7 started
        $this->askScore("Daksh", 8)->shouldReturn(false);
        $this->nextRound(); // 6
        $this->askScore("Daksh", 7)->shouldReturn(false);

        $this->nextRound(); // 5
        $this->askScore("Daksh", 4)->shouldReturn(true);
        $this->askScore("Tirth", 5)->shouldReturn(true);
    }

    public function it_can_not_record_score_without_starting_game()
    {
        $this->askScore("Daksh", 3)->shouldReturn(false);

        $this->addPlayer(["Daksh", "Tirth"]);
        $this->start();
        $this->askScore("Daksh", 3)->shouldReturn(true);
    }

    public function it_can_ask_score_at_start_of_each_round()
    {
        $this->addPlayer(["Daksh", "Tirth"]);
        $this->start();
        $this->askScore("Daksh", 3)->shouldReturn(true);
    }

    public function it_can_not_give_score_when_round_not_completed()
    {
        $this->addPlayer(["Daksh", "Tirth"]);
        $this->start();
        $this->askScore("Daksh", 3)->shouldReturn(true);
        $this->getScore("Daksh")->shouldReturn(false);
    }

    public function it_can_not_give_score_for_player_do_not_exists()
    {
        $this->addPlayer(["Daksh", "Hiren"]);
        $this->start();
        $this->askScore("Daksh", 3)->shouldReturn(true);
        $this->getScore("Tirth")->shouldReturn(false);
    }

    public function it_can_declare_score_positively()
    {
        $this->addPlayer(["Daksh", "Tirth"]);
        $this->start();
        $this->askScore("Daksh", 3);
        $this->submitScore("Daksh", true);
        $this->getScore("Daksh")->shouldReturn(30);
    }

    public function it_can_declare_score_negetively()
    {
        $this->addPlayer(["Daksh", "Tirth"]);
        $this->start();
        $this->askScore("Daksh", 3);
        $this->submitScore("Daksh", false);
        $this->getScore("Daksh")->shouldReturn(-30);
    }

    public function it_can_declare_score_for_0()
    {
        $this->addPlayer(["Daksh", "Tirth"]);
        $this->start();

        $this->askScore("Daksh", 0);
        $this->askScore("Tirth", 0);

        $this->submitScore("Daksh", true);
        $this->submitScore("Tirth", false);

        $this->getScore("Daksh")->shouldReturn(10);
        $this->getScore("Tirth")->shouldReturn(-10);
    }

    public function it_should_follow_KCFL_color()
    {
    	$this->addPlayer(["Daksh", "Tirth"]);
        $this->start();

        $this->getRoundColor()->shouldReturn('K');

        $this->nextRound();
        $this->getRoundColor()->shouldReturn('C');

        $this->nextRound();
        $this->getRoundColor()->shouldReturn('F');

        $this->nextRound();
        $this->getRoundColor()->shouldReturn('L');

        $this->nextRound();
        $this->getRoundColor()->shouldReturn('K');

        $this->nextRound();
        $this->getRoundColor()->shouldReturn('C');

        $this->nextRound();
        $this->getRoundColor()->shouldReturn('F');

        $this->nextRound();
        $this->getRoundColor()->shouldReturn('L');

    }

    public function it_cant_return_any_round_information_when_game_not_started()
    {
        $this->getRoundColor()->shouldReturn(false);
        $this->getRoundNo()->shouldReturn(false);
        $this->getRoundCardsCount()->shouldReturn(false);
    }

    public function it_should_return_correct_round_no()
    {
    	$this->addPlayer(["Daksh", "Tirth"]);
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

    public function it_will_increases_card_decreases_card()
    {
        $this->addPlayer(["Daksh", "Tirth"]);
        $this->start();

        $this->getRoundCardsCount()->shouldReturn(7);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(6);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(5);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(4);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(3);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(2);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(1);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(2);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(3);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(4);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(5);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(6);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(7);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(1);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(2);
        $this->nextRound();
        $this->getRoundCardsCount()->shouldReturn(3);
    }
}
