<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnEditable;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Admin\Display\GamesEditorForm;
use App\Models\Blogger;
use App\Models\Game;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Admin;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;

/**
 * Class Ranks
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Bloggers extends Section implements Initializable
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
//        return auth()->user()->isAdmin() || auth()->user()->isSuperAdmin();
    }

    public function isCreatable()
    {
        return false;
    }

    public function isEditable(Model $model)
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
        return 'fa fab fas fa-newspaper';
    }

    public function getTitle()
    {
        return __('admin.bloggers.title');
    }

    public function getEditTitle()
    {
        return __('admin.bloggers.title_edit');
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
        $tabs = AdminDisplay::tabbed();

        $display = AdminDisplay::datatablesAsync()->setName('only_new');
        $display->getScopes()->push('new');
//            ->setFilters(
////                AdminDisplayFilter::scope('latest'); // ?latest
////        AdminDisplayFilter::scope('type'); // ?type=news | ?latest&type=news
//    );

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns(
                AdminColumn::text('id', '#')->setWidth('30px')
                , AdminColumn::text('name', __('admin.bloggers.name'))
                , AdminColumn::text('phone', __('admin.bloggers.method'))
                , AdminColumn::url('vk', __('admin.bloggers.contact'))
                , AdminColumn::text('email', __('admin.bloggers.email'))
                , \AdminColumnEditable::checkbox('processed',
                __('admin.bloggers.status_checked'),
                __('admin.bloggers.status_unchecked'),
                __('admin.bloggers.status')),
                AdminColumn::datetime('created_at', __('admin.bloggers.created'))
            )->paginate(30);

        //$display->with('interfaceLanguage', 'profileThemes');

        $tabs->appendTab($display, __('admin.bloggers.new'))->setBadge(Blogger::new()->count());

        $display = AdminDisplay::datatablesAsync()->setName('finished_items');
        $display->getScopes()->push('processed');
        $display->setDisplaySearch(true);


        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns(
                AdminColumn::text('id', '#')->setWidth('30px')
                , AdminColumn::text('name', __('admin.bloggers.name'))
                , AdminColumn::text('phone', __('admin.bloggers.method'))
                , AdminColumn::url('vk', __('admin.bloggers.contact'))
                , AdminColumn::text('email', __('admin.bloggers.email'))
                , \AdminColumnEditable::checkbox('processed',
                __('admin.bloggers.status_checked'),
                __('admin.bloggers.status_unchecked'),
                __('admin.bloggers.status'))
            )->paginate(30);

        $tabs->appendTab($display, __('admin.bloggers.processed'))->setBadge(Blogger::processed()->count());


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
        return AdminForm::panel()->addBody([
            AdminFormElement::text('name', 'Имя')->setReadOnly(true)
            , AdminFormElement::text('phone', 'Телефон')->setReadOnly(true)
            , AdminFormElement::text('email', 'E-mail')->setReadOnly(true)
            , AdminFormElement::text('vk', 'ВКонтакте')->setReadOnly(true)
            , AdminFormElement::checkbox('processed',
                'Обработано'),
            AdminFormElement::textarea('comment', 'Комментарий')
        ]);
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