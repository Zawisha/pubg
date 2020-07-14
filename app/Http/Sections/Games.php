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
class Games extends Section implements Initializable
{

    protected $gameCode = Game::GAME_PUBG;
    protected $isMultiple = false;
    /**
     * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
     *
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

    public function isDeletable(\Illuminate\Database\Eloquent\Model $model)
    {
//        return false;
        return auth()->user()->isAdmin()
            || auth()->user()->isSuperAdmin();
    }

    public function isCreatable()
    {
        return true;
    }

    public function initialize()
    {
        $this->saved(function ($config, $game) {
            if ($game->is_king) {
                event(new GameChanged($game));
            } else {
                event(new GamesChanged());
            }

        });

        $this->deleting(function ($config, $game) {
            if ($game->status != Game::STATUS_FINISHED) {
                // Возвращаем всем средства
                foreach ($game->members as $member) {
                    $member->increment('balance', $game->price);

                    Transaction::create([
                        'user_id' => $member->id,
                        'amount' => $game->price,
                        'type' => Transaction::TYPE_GAME_RETURN,
                        'status' => Transaction::STATUS_NORMAL,
                        'comment' => '',
                    ]);

                    event(new UserChanged($member));
                }

                Notification::send($game->members,
                    new GameCanceled($game->planned_at
                        ->setTimezone('Europe/Moscow')
                        ->format('d.m.Y at H:i'),
                        $game->getGameNameNotificationString()
                    ));

                // Отписываем всех от игры
                $game->members()->detach();
            }
        });

        $this->deleted(function ($config, $game) {
            if ($game->status != Game::STATUS_FINISHED) {
                // Уведомляем о изменении состава игр
                event(new GamesChanged());
            }
        });

//        $this->created(function () {
//            event(new GamesChanged());
//        });
        // Добавление пункта меню и счетчика кол-ва записей в разделе
//        $this->addToNavigation($priority = 500, function () {
//            return \App\Models\User::count();
//        });
    }

    public function getIcon()
    {
        return 'fa fa-gamepad';
    }

    public function getTitle()
    {
        return __('admin.games.title');
    }

    public function getEditTitle()
    {
        return __('admin.games.title_edit') . ' ' . Game::GAME_NAMES[$this->gameCode];
    }

    public function getCreateTitle()
    {
        return __('admin.games.title_create') . ' ' . Game::GAME_NAMES[$this->gameCode];
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay($memberId)
    {
        $tabs = AdminDisplay::tabbed();
        debug($memberId);

        Log::debug($memberId);

//        if (is_array($memberId)) {
//            $memberId = $memberId['memberId'];
//        }


        $display = AdminDisplay::datatablesAsync()->setName('only_new');
        $display->setOrder([[5, 'asc']]);
        $display->getScopes()->push('newOrStarted');
        $display->getScopes()->push('notKing');
        if ($this->isMultiple) {
            $display->getScopes()->push('isMultiple');
        } else {
            $display->getScopes()->push('notMultiple');
        }

        $display->getScopes()->push('byGameCode', $this->gameCode);

        if ($memberId) {
            $display->getScopes()->push(['byMember', $memberId]);
            $display->setPayload(['memberId' => is_array($memberId) ? $memberId['memberId'] : $memberId]);
        }
//            ->setFilters(
////                AdminDisplayFilter::scope('latest'); // ?latest
////        AdminDisplayFilter::scope('type'); // ?type=news | ?latest&type=news
//    );

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns(
                AdminColumn::link('id', '#')->setWidth('30px')
                , AdminColumn::text('name', __('admin.games.name'))
                , AdminColumn::boolean('use_max_kill', 'x0.75')
                , AdminColumn::custom('type', function ($model) {
                return Game::getTypeNames()[$model->type];
            })->setLabel('Тип')
                , AdminColumn::count('members', __('admin.games.members'))
                , AdminColumn::datetime('planned_at', __('admin.games.planned_at'))
                , AdminColumn::datetime('created_at', __('admin.games.created_at'))
            )->paginate(30);

        //$display->with('interfaceLanguage', 'profileThemes');

        $countQuery = Game::newOrStarted()->notKing()->byGamecode($this->gameCode);
        if ($this->isMultiple) {
            $countQuery->isMultiple();
        } else {
            $countQuery->notMultiple();
        }

        if ($memberId) {
            $countQuery->byMember($memberId)->count();
        }

        $count = $countQuery->count();


        $tabs->appendTab($display, __('admin.games.planned'))
            ->setBadge($count);

        $display = AdminDisplay::datatablesAsync()->setName('finished_items');
        $display->setOrder([[4, 'desc']]);
        $display->getScopes()->push('finished');
        $display->getScopes()->push('notKing');

        if ($this->isMultiple) {
            $display->getScopes()->push('isMultiple');
        } else {
            $display->getScopes()->push('notMultiple');
        }

        $display->getScopes()->push('byGameCode', $this->gameCode);

        if ($memberId) {
            $display->getScopes()->push(['byMember', $memberId]);
            $display->setPayload(['memberId' => is_array($memberId) ? $memberId['memberId'] : $memberId]);
        }


        $cols = [AdminColumn::link('id', '#')->setWidth('30px')
            , AdminColumn::text('name', __('admin.games.name'))
            , AdminColumn::custom('type', function ($model) {
                return Game::getTypeNames()[$model->type];
            })->setLabel('Тип')
            , AdminColumn::text('total_payed', __('admin.games.total_payed'))
        ];

        if ($this->isMultiple) {
            $cols = array_merge($cols, [
                AdminColumn::text('total_payed2', __('admin.games.total_payed2'))
            ]);
        }

        $cols = array_merge($cols, [
            AdminColumn::datetime('planned_at', __('admin.games.planned_at'))
            , AdminColumn::datetime('created_at', __(__('admin.games.created_at')))
        ]);

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns(
                $cols
            )->paginate(30);

        $countQuery = Game::finished()->notKing()->byGamecode($this->gameCode);
        if ($this->isMultiple) {
            $countQuery->isMultiple();
        } else {
            $countQuery->notMultiple();
        }

        if ($memberId) {
            $countQuery->byMember($memberId)->count();
        }

        $count = $countQuery->count();

        $tabs->appendTab($display, __('admin.games.finished'))->setBadge($count);

        return $tabs;

    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit(null);
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
//                            ->setReadonly($haveMembers),
                AdminFormElement::select('status', __('admin.games.status'))
                    ->setOptions(Game::getStatusNames())
                    ->setDefaultValue(Game::STATUS_NEW)
                    ->required(),
                AdminFormElement::number('price', __('admin.games.price'))
                    ->setMin(0)
                    ->setMax(10000)
                    ->setDefaultValue(0.1)
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
                AdminFormElement::text('login', __('admin.games.login')),
                AdminFormElement::text('password', __('admin.games.password')),
                AdminFormElement::text('total_payed', __('admin.games.total_payed'))->setReadonly(true)
            ]
        ];
    }

    protected function extendEditTabs($game, $tabs)
    {
        $games = new GamesEditorForm();
        $games->setGame($game);
        $tabs->appendTab($games, __('admin.games.tabs.members'))->setBadge($game ? $game->members->count() : 0);
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        /** @var Game $game */
        $game = Game::find($id);

        $haveMembers = (bool)$game && $game->members()->exists();

        $images = [
            '0' => __('admin.games.image') . ' 1',
            '1' => __('admin.games.image') . ' 2',
            '2' => __('admin.games.image') . ' 3',
            '3' => __('admin.games.image') . ' 4',
            '4' => __('admin.games.image') . ' 5',
            '5' => __('admin.games.image') . ' 6',
            '6' => __('admin.games.image') . ' 7',
            '7' => __('admin.games.image') . ' 8',
        ];

        $games = Game::whereIn('status', [Game::STATUS_NEW, Game::STATUS_STARTED])
            ->orderBy('planned_at', 'asc')
            ->where('game_code', $this->gameCode)
            ->notKing()
            ->take(7)
            ->get();

        foreach ($games as $g) {
            if ($g->image != null && $g->image != '') {
                if ($game && $game->id == $g->id) {
                    $images[$g->image] .= ' ' . __('admin.games.image_current');
                } else {
                    $images[$g->image] .= ' ' . __('admin.games.image_used');
                }
            }
        }

        $form = AdminForm::panel();
        $form->addBody(
            new \SleepingOwl\Admin\Form\FormElements([
                AdminFormElement::columns($this->getCols($haveMembers, $images))
            ])
//            $tabs
        );

        $form->getButtons()->setButtons([
            'save' => (new Save())->setText(__('admin.games.buttons.save')),
            'save_and_close' => (new SaveAndClose())->setText(__('admin.games.buttons.save_and_close')),
            'delete' => (new Delete())->setText(__('admin.games.buttons.delete')),
            'cancel' => (new Cancel())->setText(__('admin.games.buttons.cancel')),
        ]);

        $tabs = AdminDisplay::tabbed();
        $tabs->appendTab($form, __('admin.games.tabs.general'));

        if ($game) {
            $this->extendEditTabs($game, $tabs);
        }

//        $tabs = AdminDisplay::tabbed();
//        $tabs->setElements([
//            __('Общие') => new \SleepingOwl\Admin\Form\FormElements([
//
//            ])]);

        return $tabs;
    }

    /**
     * @return void
     */
    public function onDelete($id)
    {
        // remove if unused
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }

}