<?php

namespace App\Models;

use App\Models\Game;
use Illuminate\Database\Eloquent\Model;

class GameRu extends Game
{
    protected $table = 'games';
    protected $connection = 'mysql_ru';
}
