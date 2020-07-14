<?php
/**
 * Created by PhpStorm.
 * User: don3d_000
 * Date: 24.10.2019
 * Time: 23:33
 */

namespace App\Http\Sections;


use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Admin\Display\GamesEditorForm;
use App\Admin\Display\KingGamesEditorForm;
use App\Models\Game;
use Illuminate\Support\Facades\Log;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Delete;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;

class KingGames extends Games
{
    public function getIcon()
    {
        return 'fa fa-crown';
    }

    public function getTitle()
    {
        return __('admin.games.king_title');
    }

    public function getEditTitle()
    {
        return __('admin.games.king_title_edit');
    }

    public function getCreateTitle()
    {
        return __('admin.games.king_title_create');
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
        $display->getScopes()->push('new');
        $display->getScopes()->push('isKing');
        $display->setParameter('is_king', true);
        if ($memberId) {
            $display->getScopes()->push(['byMember', $memberId]);
            $display->setPayload(['memberId' => is_array($memberId) ? $memberId['memberId'] : $memberId]);
        }

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns(
                AdminColumn::link('id', '#')->setWidth('30px')
                , AdminColumn::text('name', __('admin.games.name'))
                , AdminColumn::custom('type', function ($model) {
                return Game::getTypeNames()[$model->type];
            })->setLabel('Тип')
                , AdminColumn::count('members', __('admin.games.members'))
                , AdminColumn::datetime('planned_at', __('admin.games.planned_at'))
                , AdminColumn::datetime('created_at', __('admin.games.created_at'))
            )->paginate(30);

        //$display->with('interfaceLanguage', 'profileThemes');

        $count = $memberId
            ? Game::new()->isKing()->byMember($memberId)->count()
            : Game::new()->isKing()->count();


        $tabs->appendTab($display, __('admin.games.planned'))
            ->setBadge($count);

        $display = AdminDisplay::datatablesAsync()->setName('finished_items');
        $display->getScopes()->push('finished');
        $display->getScopes()->push('isKing');
        $display->setParameter('is_king', true);
        if ($memberId) {
            $display->getScopes()->push(['byMember', $memberId]);
            $display->setPayload(['memberId' => is_array($memberId) ? $memberId['memberId'] : $memberId]);
        }


        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns(
                AdminColumn::link('id', '#')->setWidth('30px')
                , AdminColumn::text('name', __('admin.games.name'))
                , AdminColumn::custom('type', function ($model) {
                return Game::getTypeNames()[$model->type];
            })->setLabel('Тип')
                , AdminColumn::datetime('planned_at', __('admin.games.planned_at'))
                , AdminColumn::datetime('created_at', __(__('admin.games.created_at')))
            )->paginate(30);

        $count = $memberId
            ? Game::finished()->isKing()->byMember($memberId)->count()
            : Game::finished()->isKing()->count();

        $tabs->appendTab($display, __('admin.games.finished'))->setBadge($count);

        return $tabs;

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

        $form = AdminForm::panel();
        $form->addBody(
            new \SleepingOwl\Admin\Form\FormElements([
                AdminFormElement::columns([
                    [
                        AdminFormElement::hidden('is_king')->setDefaultValue(true),
                        AdminFormElement::text('name', __('admin.games.name')),
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
                            ->setDefaultValue(100)
                            ->required()
                            ->setReadonly($haveMembers),
                        AdminFormElement::number('top1_prize', __('admin.games.top1_bonus'))
                            ->setDefaultValue(100),
                        AdminFormElement::number('top2_prize', __('admin.games.top2_bonus'))
                            ->setDefaultValue(0),
                        AdminFormElement::number('top3_prize', __('admin.games.top3_bonus'))
                            ->setDefaultValue(0),
                        AdminFormElement::datetime('planned_at', __('admin.games.king_planned_at'))
                            ->required(),
                        AdminFormElement::datetime('planned_at2', __('admin.games.king_planned_at2'))
                            ->required(),
                        AdminFormElement::datetime('planned_at3', __('admin.games.king_planned_at3'))
                            ->required(),
                        AdminFormElement::hidden('image')->setDefaultValue('king'),
                        AdminFormElement::textarea('comment', __('admin.games.comment')),
                    ],
                    [
                        AdminFormElement::hidden('map_name')->setDefaultValue(__('admin.games.king_maps')),
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
                        AdminFormElement::text('login', __('admin.games.login1')),
                        AdminFormElement::text('password', __('admin.games.password1')),
                        AdminFormElement::text('login2', __('admin.games.login2')),
                        AdminFormElement::text('password2', __('admin.games.password2')),
                        AdminFormElement::text('login3', __('admin.games.login3')),
                        AdminFormElement::text('password3', __('admin.games.password3')),
                        AdminFormElement::text('total_payed', __('admin.games.total_payed'))->setReadonly(true)
                    ]
                ])])
//            $tabs
        );

        $form->getButtons()->setButtons([
            'save' => (new Save())->setText(__('admin.games.buttons.save')),
            'save_and_close' => (new SaveAndClose())->setText(__('admin.games.buttons.save_and_close')),
            'delete' => (new Delete())->setText(__('admin.games.buttons.delete')),
//                ->setIconClass('fa fa-paper-plane')
//                ->setHtmlAttributes(['class' => 'btn-success'])
//                ->setName('save_and_close'),
            'cancel' => (new Cancel())->setText(__('admin.games.buttons.cancel')),
        ]);

        $tabs = AdminDisplay::tabbed();
        $tabs->appendTab($form, __('admin.games.tabs.general'));

        if ($game) {
            $games = new KingGamesEditorForm();
            $games->setGame($game);
            $tabs->appendTab($games, __('admin.games.tabs.members'))->setBadge($game ? $game->members->count() : 0);
        }

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