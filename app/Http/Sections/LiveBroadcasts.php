<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnEditable;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Admin\Display\GamesEditorForm;
use App\Events\LiveStreamChanged;
use App\Models\Blogger;
use App\Models\Game;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Admin;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Section;

/**
 * Class Ranks
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class LiveBroadcasts extends Section implements Initializable
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

    public function isEditable(Model $model)
    {
        return true;
    }

    public function initialize()
    {
        $this->saved(function ($config, $live) {
            event(new LiveStreamChanged(json_encode($live)));
        });
        // Добавление пункта меню и счетчика кол-ва записей в разделе
//        $this->addToNavigation($priority = 500, function () {
//            return \App\Models\User::count();
//        });
    }

    public function getIcon()
    {
        return 'fa fab fas fa-newspaper';
    }

    public function getTitle()
    {
        return __('admin.stream.title');
    }

    public function getEditTitle()
    {
        return __('admin.stream.title_edit');
    }

    public function getCreateTitle()
    {
        return __('');
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay($pid = null, $refid = null)
    {
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
        $panel = AdminForm::panel()->addBody([
            AdminFormElement::text('url', __('admin.stream.url_pubg')),
            AdminFormElement::text('url_cod', __('admin.stream.url_cod')),
            AdminFormElement::text('url_freefire', __('admin.stream.url_freefire')),
        ]);

        $panel->getButtons()->setButtons([
            'save' => (new Save())->setText(__('admin.stream.save')),
        ]);

        return $panel;
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