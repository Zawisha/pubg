<?php

namespace App\Http\Sections\Games;

use AdminDisplay;
use AdminColumn;
use AdminForm;
use AdminFormElement;
use AdminColumnFilter;

use App\Http\Sections\Games;
use App\Models\Game;
use App\Models\Games\GamePUBG;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;

use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;

/**
 * Class PUBGGames
 *
 * @property GamePUBG $model
 */
class FreeFireGames extends Games
{

    protected $gameCode = Game::GAME_FREEFIRE;
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $alias;

    public function getTitle()
    {
        return 'FREE FIRE';
    }

    public function getIcon()
    {
        return '';
    }
}
