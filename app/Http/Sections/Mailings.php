<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Jobs\DeliverMailings;
use App\Models\Game;
use App\Models\Mailing;
use App\Models\Rank;
use App\Models\User;
use Carbon\Carbon;
use DB;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Section;

/**
 * Class Ranks
 *
 * @property \App\Models\Mailing $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Mailings extends Section implements Initializable
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

    public function isDeletable(\Illuminate\Database\Eloquent\Model $model)
    {
        return true;
//        return auth()->user()->isAdmin();
    }

    public function isCreatable()
    {
        return true;
    }

    public function initialize()
    {
        $this->saved(/**
         * @param $config
         * @param Mailing $mailing
         */
            function ($config, $mailing) {
                if (request('next_action', false) == 'save_and_close') {
                    DeliverMailings::dispatch($mailing);
                    $mailing->mailed_at = now();
                    $mailing->status = Mailing::STATUS_MAILED;
                    $mailing->save();
                }
            });
    }

    public function getIcon()
    {
        return 'fa fa-mail-bulk';
    }

    public function getTitle()
    {
        return __('admin.mailing.title');
    }

    public function getEditTitle()
    {
        return __('admin.mailing.title_edit');
    }

    public function getCreateTitle()
    {
        return __('admin.mailing.title_create');
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay($pid = null, $refid = null)
    {
        $display = AdminDisplay::datatablesAsync();

//        $display->setDisplaySearch(true);

        $display->setColumns([
            AdminColumn::image('image')->setImageWidth(150),
            AdminColumn::text('id', __('admin.mailing.rows.id'))->setWidth(50),
            AdminColumn::text('name', __('admin.mailing.rows.name')),
            AdminColumn::boolean('to_lk', __('admin.mailing.rows.to_lk')),
            AdminColumn::boolean('to_email', __('admin.mailing.rows.to_email')),
            AdminColumn::boolean('to_bot', __('admin.mailing.rows.to_bot')),
            AdminColumn::boolean('status', __('admin.mailing.rows.status')),
            AdminColumn::text('mailed_at', __('admin.mailing.rows.mailed_at')),
        ]);

        $display->setHtmlAttribute('class', 'table-primary');

        return $display;
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
        $form = AdminForm::panel();

        $ranks = [];

        foreach (Rank::pluck('name', 'id') as $rankId => $rankName) {
            $ranks[$rankId] = $rankName;
        }

        $gameCodes = Game::GAME_NAMES;

        $memberships = [
            Mailing::MEMBERSHIP_IGNORE => __('admin.mailing.inputs.membership_types.no_metter'),
            Mailing::MEMBERSHIP_MEMBER => __('admin.mailing.inputs.membership_types.member'),
            Mailing::MEMBERSHIP_NOT_MEMBER => __('admin.mailing.inputs.membership_types.not_member'),
        ];

        $form->addBody(
            AdminFormElement::columns([
                [
                    AdminFormElement::text('name', __('admin.mailing.rows.name'))->required(),
                    AdminFormElement::checkbox('to_lk', __('admin.mailing.inputs.to_lk')),
                    AdminFormElement::checkbox('to_email', __('admin.mailing.inputs.to_email')),
                    AdminFormElement::checkbox('to_bot', __('admin.mailing.inputs.to_bot')),
                    AdminFormElement::number('min_balance', __('admin.mailing.inputs.min_balance'))->setDefaultValue(0),
                    AdminFormElement::number('max_balance', __('admin.mailing.inputs.max_balance'))->setDefaultValue(0),
//                    AdminFormElement::date('created_from', __('admin.mailing.inputs.created_from')),
//                    AdminFormElement::date('created_to', __('admin.mailing.inputs.created_to')),
                    AdminFormElement::select('membership_type', __('admin.mailing.inputs.membership_type'), $memberships)->setDefaultValue(Mailing::MEMBERSHIP_IGNORE),
                    AdminFormElement::select('game_code', __('admin.mailing.inputs.game_code'), $gameCodes),
//                    AdminFormElement::multiselect('ranks', 'Ранги', Rank::class)
                    AdminFormElement::multiselect(
                        'ranks',
                        __('admin.mailing.inputs.ranks'),
                        $ranks)->mutateValue(function ($value) {
                        return is_array($value)
                            ? array_map(function ($elem) {
                                return (integer)$elem;
                            }, $value)
                            : $value;
                    }),
                    AdminFormElement::multiselect(
                        'games',
                        __('admin.mailing.inputs.games'))
                        ->setModelForOptions(Game::class, 'name')
                        ->setFetchColumns(['id', 'name',
                            'planned_at',
                            DB::raw("date_format(convert_tz(planned_at,'+00:00', '+3:00'), '%e.%c.%Y %T') as fdate")])
//                        ->setSearch(DB::raw("date_format(convert_tz(planned_at,'+00:00', '+3:00'), '%e.%d.%Y %T'))")
//                        ->setLoadOptionsQueryPreparer(function ($item, $query) {
//                            return $query->where(DB::raw(date_format(convert_tz(planned_at,'+00:00', '+3:00'), '%e.%d.%Y %T')), 'like', );
//                        })
                        //->set select date_format(convert_tz(planned_at,'+00:00', '+3:00'), '%e.%d.%Y %T')
                        ->setDisplay('fdate'),
                    AdminFormElement::multiselectajax("users",
                        __('admin.mailing.inputs.users'))
                        ->setModelForOptions(User::class, 'name')
                        ->setFetchColumns('name')
                        ->setDisplay('name'),
                ],
                [
                    AdminFormElement::textarea('message', __('admin.mailing.inputs.message'))->setRows(3)
                        ->setValidationRules([
                            'max:4000'
                        ])->setHelpText(__('admin.mailing.inputs.message_hint')),
                    AdminFormElement::textarea('short_message', __('admin.mailing.inputs.short_message'))->setRows(3),
                    AdminFormElement::image('image', __('admin.mailing.inputs.image')),
                ]
            ])
        );
        $tabs = AdminDisplay::tabbed();
//        $tabs->setElements([
//            __('Общие') => new \SleepingOwl\Admin\Form\FormElements([
//
//            ])]);

        $form->getButtons()->setButtons([
            'save' => (new Save())->setText(__('admin.mailing.buttons.save')),
            'save_and_send' => (new SaveAndClose())->setText(__('admin.mailing.buttons.save_and_send'))
                ->setIconClass('fa fa-paper-plane')
                ->setHtmlAttributes(['class' => 'btn-success'])
                ->setName('save_and_close'),
            'cancel' => (new Cancel())->setText(__('admin.mailing.buttons.cancel')),
        ]);


        return $form;
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
