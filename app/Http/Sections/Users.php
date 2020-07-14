<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnEditable;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use AdminDisplayFilter;
use App\Models\Game;
use App\Models\Rank;
use App\Models\TelegramHistory;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Facades\Meta;
use SleepingOwl\Admin\Section;
use AdminSection;

/**
 * Class Users
 *
 * @property PUBGMB\User $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Users extends Section implements Initializable
{

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

    /**
     * @param User $model
     * @return bool
     */
    public function isDeletable(\Illuminate\Database\Eloquent\Model $model)
    {
        if ($model->relatedGames()->where('status', Game::STATUS_NEW)->exists()) {
            return false;
        } else {
            return auth()->user()->isSuperAdmin();
        }
//        return true;
    }

    public function isCreatable()
    {
        return false;
    }

    public function initialize()
    {
        $this->deleting(/**
         * @param $config
         * @param User $user
         */
            function ($config, $user) {
                $user->relatedGames()->detach();
            });

//        $this->saved(function ($config, $user) {
//            ChatSendSystem::dispatch($user->id, ['type' => 'refreshUser']);
//        });
        // Добавление пункта меню и счетчика кол-ва записей в разделе
//        $this->addToNavigation($priority = 500, function () {
//            return \App\Models\User::count();
//        });
    }

    public function getIcon()
    {
        return 'fa fa-user';
    }

    public function getTitle()
    {
        return __('admin.users.title');
    }

    public function getEditTitle()
    {
        return __('admin.users.title_edit');
    }

    public function getCreateTitle()
    {
        return __('admin.users.title_create');
    }


    protected function getDisplayCols()
    {
        $cols =
            [
                AdminColumn::text('id', __('admin.users.rows.id'))->setWidth(50),
                AdminColumn::text('name', __('admin.users.rows.name')),
                AdminColumn::text('pubg_id', __('admin.users.rows.pubg_id')),
                AdminColumn::text('name_cod', __('admin.users.rows.name_cod')),
                AdminColumn::text('cod_id', __('admin.users.rows.cod_id')),
                AdminColumn::text('name_freefire', __('admin.users.rows.name_freefire')),
                AdminColumn::text('freefire_id', __('admin.users.rows.freefire_id')),
                AdminColumn::text('rank_id', __('admin.users.rows.rank_id'), 'rank.name'),
                AdminColumn::url('vk_link', __('admin.users.rows.facebook_link')),
                AdminColumn::text('kd', 'KD'),
                AdminColumn::text('kills', __('admin.users.rows.kills')),
                AdminColumn::text('games', __('admin.users.rows.games')),
                AdminColumn::text('balance', __('admin.users.rows.balance')),
//            AdminColumn::datetime('created_at', 'Дата регистрации')
        ];

        if (Auth::user()->isSuperAdmin()) {
            array_push($cols, AdminColumn::email('email', __('admin.users.rows.email')));
        }

        return $cols;
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay($pid = null, $refid = null)
    {

        $tabs = AdminDisplay::tabbed();

        $display = AdminDisplay::datatablesAsync()->setName('all');
        $display->setDisplaySearch(true);

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns($this->getDisplayCols())->paginate(30);

        $tabs->appendTab($display, 'Все')
            ->setBadge(User::count());

        $display = AdminDisplay::datatablesAsync()->setName('pubg');
        $display->getScopes()->push('havePubgId');
        $display->setDisplaySearch(true);

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns($this->getDisplayCols())->paginate(30);

        $tabs->appendTab($display, 'PUBG')
            ->setBadge(User::havePubgId()->count());

        $display = AdminDisplay::datatablesAsync()->setName('cod');
        $display->getScopes()->push('haveCodId');
        $display->setDisplaySearch(true);

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns($this->getDisplayCols())->paginate(30);

        $tabs->appendTab($display, 'COD')
            ->setBadge(User::haveCodId()->count());

        $display = AdminDisplay::datatablesAsync()->setName('Free Fire');
        $display->getScopes()->push('haveFreeFireId');
        $display->setDisplaySearch(true);

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns($this->getDisplayCols())->paginate(30);

        $tabs->appendTab($display, 'Free Fire')
            ->setBadge(User::haveFreeFireId()->count());

        return $tabs;
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit(null);
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {

        $user = User::find($id);

        $readOnly = !Auth::user()->isSuperAdmin();

        $tabs = AdminDisplay::tabbed();

        $form = AdminForm::panel();
        $form->addBody(
            AdminFormElement::columns([
                [
                    AdminFormElement::text('name', __('admin.users.inputs.name')),
                    AdminFormElement::text('pubg_id', __('admin.users.inputs.pubg_id')),
                    AdminFormElement::text('name_cod', __('admin.users.rows.name_cod')),
                    AdminFormElement::text('cod_id', __('admin.users.rows.cod_id')),
                    AdminFormElement::text('name_freefire', __('admin.users.rows.name_freefire')),
                    AdminFormElement::text('freefire_id', __('admin.users.rows.freefire_id')),
                    AdminFormElement::text('reflink', __('admin.users.inputs.reflink')),
                    Auth::user()->isSuperAdmin()
                        ? AdminFormElement::text('email', __('admin.users.inputs.email'))->required()
                        : '',
                    AdminFormElement::text('vk_link', __('admin.users.inputs.facebook_link'))->setReadonly($readOnly),
                    AdminFormElement::select('rank_id', __('admin.users.inputs.rank_id'))
                        ->required()
                        ->setModelForOptions(Rank::class)
//                    ->setFetchColumns('eng_name')
                        ->setDisplay('name')
                        ->setReadonly($readOnly),
                    AdminFormElement::text('kd', 'KD'),
                    AdminFormElement::number('telegram_id', __('admin.users.inputs.telegram_id'))->setReadonly($readOnly),
                    AdminFormElement::number('games', __('admin.users.inputs.games'))->setMin(0)->setReadonly($readOnly),
                    AdminFormElement::number('kills', __('admin.users.inputs.kills'))->setMin(0)->setReadonly($readOnly),
                    AdminFormElement::checkbox('no_mail', __('admin.users.inputs.no_mail')),
                    AdminFormElement::checkbox('active', __('admin.users.inputs.active')),
                    AdminFormElement::checkbox('telegram_ban', __('admin.users.inputs.telegram_ban')),
                    Auth::user()->isSuperAdmin()
                        ? AdminFormElement::number('cbl', __('admin.users.inputs.cbl'))->setDefaultValue(0)
                        : '',
                    AdminFormElement::password('password', __('admin.users.inputs.password'))
                        ->hashWithBcrypt()
                        ->setValueSkipped(function () {
                            return is_null(request('password'));
                        })
                        ->setHelpText(__('admin.users.inputs.password_hint'))

                ],
                [
                    '<img src="' . $user->avatar . '" style="width: 200px; border-radius: 10px;" /><br />',
//                    AdminColumn::text('telegramCode.code'),
                    true || Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()
                        ? "<a href=\"/missioncontrol/loginasuser/{$user->id}\" target='_blank'>" . __('admin.users.inputs.auth') . "</a>"
                        : '',
                    AdminFormElement::datetime('created_at', __('admin.users.inputs.created_at'))->setReadOnly(true),
                    AdminFormElement::number('balance', __('admin.users.inputs.balance'))->setReadonly($readOnly),
                    AdminFormElement::checkbox('bonus_used', __('admin.users.inputs.bonus_used')),
                    AdminFormElement::number('bonus_games', __('admin.users.inputs.bonus_games')),
                    AdminFormElement::textarea('comment', __('admin.users.inputs.comment')),
                    '<label>Пригласивший</label><br />',
                    $user->referral_id
                        ? AdminColumn::relatedLink('referral.name', __('admin.users.inputs.referral_name'))
                        : 'Нет'
//                            '<input name="sum_to_add"> <button>Пополнить</button><br />',
                ]
            ])
        );
        $tabs = AdminDisplay::tabbed();
//        $tabs->setElements([
//            __('Общие') => new \SleepingOwl\Admin\Form\FormElements([
//
//            ])]);

        $tabs->appendTab($form, __('admin.users.tabs.general'));

        // Список транзакций
        if (true || auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            $tabs->appendTab(new \SleepingOwl\Admin\Form\FormElements([
                view('admin.payments', ['transactions' => $user->transactions()
                    ->latest()
                    ->where('created_at', '>', now()->subMonth())
                    ->get()])->render()
            ]), __('admin.users.tabs.payments'));
        }

        $subs = AdminSection::getModel(Game::class);
        $subs = $subs->fireDisplay(['memberId' => $id]);
        $tabs->appendTab(new \SleepingOwl\Admin\Form\FormElements([
            $subs
        ]), __('admin.users.tabs.games'))->setBadge(Game::byMember($id)->count());

        $subs = AdminSection::getModel(TelegramHistory::class);
        $subs = $subs->fireDisplay(['user_id' => $id]);
        $tabs->appendTab(new \SleepingOwl\Admin\Form\FormElements([
            $subs
        ]), __('admin.users.tabs.telegram'))->setBadge(TelegramHistory::byUser($id)->count());


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
