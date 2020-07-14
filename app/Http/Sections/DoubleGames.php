<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Admin\Display\GamesEditorForm;
use App\Events\GameChanged;
use App\Events\GamesChanged;
use App\Events\UserChanged;
use App\Models\Game;
use App\Models\Transaction;
use App\Notifications\GameCanceled;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use SleepingOwl\Admin\Admin;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Delete;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Section;

/**
 * Class Ranks
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class DoubleGames extends Games implements Initializable
{
    protected $isMultiple = true;

    public function getIcon()
    {
        return 'fa fa-chess';
    }

    public function getTitle()
    {
        return __('admin.double_games.title');
    }

    public function getEditTitle()
    {
        return __('admin.double_games.title_edit') . ' ' . Game::GAME_NAMES[$this->gameCode];
    }

    public function getCreateTitle()
    {
        return __('admin.double_games.title_create') . ' ' . Game::GAME_NAMES[$this->gameCode];
    }

    protected function getCols($haveMembers, $images)
    {
        return [
            [
//                        AdminFormElement::hidden('is_king')->setDefaultValue(false),
                AdminFormElement::text('name', __('admin.games.name')),
                AdminFormElement::hidden('game_code')->setValue($this->gameCode)
                    ->setDefaultValue($this->gameCode)
                ,
                AdminFormElement::select('type', __('admin.games.mode'))
                    ->setOptions(Game::getTypeNames())
                    ->required(),
                AdminFormElement::hidden('mul')->setDefaultValue(2)->setValue(2),
//                            ->setReadonly($haveMembers),
                AdminFormElement::select('status', __('admin.games.status'))
                    ->setOptions(Game::getStatusNames())
                    ->setDefaultValue(Game::STATUS_NEW)
                    ->required(),
                AdminFormElement::number('price', __('admin.games.price'))
                    ->setMin(0)
                    ->setMax(10000)
                    ->setDefaultValue(100)
                    ->required()
                    ->setReadonly($haveMembers),
                AdminFormElement::checkbox('use_max_kill', __('admin.games.use_max_kill'))->setDefaultValue(false),
                AdminFormElement::number('top1_prize', __('admin.games.top1_bonus'))
                    ->setDefaultValue(100),
                AdminFormElement::number('top2_prize', __('admin.games.top2_bonus'))
                    ->setDefaultValue(0),
                AdminFormElement::number('top3_prize', __('admin.games.top3_bonus'))
                    ->setDefaultValue(0),
                AdminFormElement::datetime('planned_at', __('admin.games.planned_at'))
                    ->required(),
                AdminFormElement::select('image', __('admin.games.cover'), $images),
                AdminFormElement::textarea('comment', __('admin.games.comment')),


//                    AdminFormElement::timestamp('started_at', 'Начался')
//                        ->setReadonly(true),
//                    AdminFormElement::timestamp('finished_at', 'Закончен')
//                        ->setReadonly(true),
//                        AdminFormElement::image('image', 'Изображение')
//                            ->setHelpText('Если не указать, назначится одно из изображений по умолчанию'),
            ],
            [
                AdminFormElement::text('map_name', __('admin.games.map_name'))
                    ->required()
                    ->setReadonly($haveMembers),
                AdminFormElement::select('face', __('admin.games.face'))
                    ->setOptions(Game::getFaceNames())
                    ->setDefaultValue(0)
                    ->required(),
                AdminFormElement::number('max_players', __('admin.games.max_players'))
                    ->setMin(1)
                    ->setMax(100)
                    ->setDefaultValue(100)
                    ->required()
                    ->setReadonly($haveMembers),
                AdminFormElement::text('login', __('admin.double_games.login1')),
                AdminFormElement::text('password', __('admin.double_games.password1')),
                AdminFormElement::text('login2', __('admin.double_games.login2')),
                AdminFormElement::text('password2', __('admin.double_games.password2')),
                AdminFormElement::text('total_payed', __('admin.games.total_payed'))->setReadonly(true),
                AdminFormElement::text('total_payed2', __('admin.games.total_payed2'))->setReadonly(true)
            ]
        ];
    }

    protected function extendEditTabs($game, $tabs)
    {
        for ($i = 0; $i < $game->mul; $i++) {
            $games = new GamesEditorForm();
            $games->setGame($game);
            $games->setMultiple($game->mul);
            $games->setIndex($i);
            $tabs->appendTab($games, __('admin.games.tabs.members') . ' #' . ($i + 1) .
                ($i == 0 ? '(NOOB)' : '(PRO)')
            )
                ->setBadge($game ? $game->members()->wherePivot('gi', $i)->count() : 0);
        }

        $games = new GamesEditorForm();
        $games->setGame($game);
        $games->setMultiple($game->mul);
        $games->setIndex(-1);
        $tabs->appendTab($games, __('admin.games.tabs.members_no_teams'))
            ->setBadge($game ? $game->members()->wherePivot('gi', null)->count() : 0);
    }
}