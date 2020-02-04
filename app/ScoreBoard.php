<?php

namespace App;

class ScoreBoard
{

    public $username;

    public $score;

    public $position;

    public function __construct($username, $score, $position = null)
    {
        $this->username = $username;
        $this->score = $score;
        $this->position = $position;
    }
}
