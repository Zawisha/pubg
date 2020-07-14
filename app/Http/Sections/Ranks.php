<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;

/**
 * Class Ranks
 *
 * @property \Rank $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Ranks extends Section implements Initializable
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
        return false;
//        return auth()->user()->isAdmin();
    }

    public function isCreatable()
    {
        return true;
    }

    public function initialize()
    {
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
        return 'fa fa-skull';
    }

    public function getTitle()
    {
        return __('admin.ranks.title');
    }

    public function getEditTitle()
    {
        return __('admin.ranks.title_edit');
    }

    public function getCreateTitle()
    {
        return __('admin.ranks.title_create');
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay($pid = null, $refid = null)
    {
        $display = AdminDisplay::datatablesAsync();

//        $display->setDisplaySearch(true);

        $display->setColumns([
            AdminColumn::image('image')->setImageWidth(40),
            AdminColumn::text('id', __('admin.ranks.rows.id'))->setWidth(50),
            AdminColumn::text('name', __('admin.ranks.rows.name')),
            AdminColumn::text('rq_battles', __('admin.ranks.rows.games')),
            AdminColumn::text('rq_kills', __('admin.ranks.rows.frags')),
            AdminColumn::text('cashback', __('admin.ranks.rows.cashback')),
            AdminColumn::text('kill_reward', __('admin.ranks.rows.frag_reward')),
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
        $form->addBody(
            AdminFormElement::columns([
                [
                    AdminFormElement::text('name', __('admin.ranks.inputs.name'))->required(),
                    AdminFormElement::number('rq_battles', __('admin.ranks.inputs.games'))->setMin(0),
                    AdminFormElement::number('rq_kills', __('admin.ranks.inputs.frags'))->setMin(0),
                    AdminFormElement::number('cashback', __('admin.ranks.inputs.cashback'))->setMin(0)->setMax(100),
                    AdminFormElement::number('kill_reward', __('admin.ranks.inputs.frag_reward'))->setMin(0)->setMax(100)
                        ->setStep(0.01),
                    AdminFormElement::number('kill_reward2', __('admin.ranks.inputs.frag_reward2'))->setMin(0)->setMax(100)
                        ->setStep(0.01),
                    AdminFormElement::image('image', __('admin.ranks.inputs.image'))->required(),
                ],
                [
                    AdminFormElement::textarea('requirements', __('admin.ranks.inputs.requirements'))->setRows(3)->required(),
                    AdminFormElement::textarea('requirements_en', __('admin.ranks.inputs.requirements_en'))->setRows(3)->required(),
                    AdminFormElement::textarea('description', __('admin.ranks.inputs.description'))->setRows(3)->required(),
                    AdminFormElement::textarea('description_en', __('admin.ranks.inputs.description_en'))->setRows(3)->required(),
                ]
            ])
        );
//        $tabs = AdminDisplay::tabbed();
//        $tabs->setElements([
//            __('Общие') => new \SleepingOwl\Admin\Form\FormElements([
//
//            ])]);

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
