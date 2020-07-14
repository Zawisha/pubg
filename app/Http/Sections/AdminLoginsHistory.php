<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnEditable;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use AdminSection;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;

/**
 * Class Lessons
 *
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class AdminLoginsHistory extends Section
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
    }

    public function isCreatable()
    {
        return false;
    }

    public function isEditable(\Illuminate\Database\Eloquent\Model $model)
    {
        return false;
    }

    public function getIcon()
    {
        return 'fa fa-file';
    }

    public function getTitle()
    {
        return __('Авторизации');
    }

    public function getEditTitle()
    {
        return __('Просмотр');
    }

    public function getCreateTitle()
    {
        return __('Добавление');
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display = AdminDisplay::table();

        $display->setApply(function ($query) {
            $query->orderBy('stamp', 'desc');
        });

//        $display->setColumnFilters(
//                NULL
//                , AdminColumnFilter::text()->setPlaceholder(__('Название'))->setOperator(FilterInterface::CONTAINS)
//                , AdminColumnFilter::select()->setModelForOptions(\onthetop\Models\Tutorial\Theme::class)->setDisplay('name')->setPlaceholder(__('Тема'))->setColumnName('theme_id')
//                , AdminColumnFilter::text()->setPlaceholder(__('Ссылка на видео'))->setOperator(FilterInterface::CONTAINS)
//                , NULL
//                , NULL
//        )->setPlacement('table.header');

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns(
                AdminColumn::datetime('stamp', __('Дата и время')),
                AdminColumn::text('ip', 'ip')->setWidth('30px'),
                AdminColumn::text('agent', __('agent'))
            )->paginate(20);

        //$display->setWidth('1024px');
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
        $panel = AdminForm::panel()->addBody([
            AdminFormElement::text('name', __('Название темы'))->required(),
            AdminFormElement::text('order', __('Порядок'))->required()
            , AdminFormElement::select('theme_id', __('Тема'))
                ->required()
                ->setModelForOptions(\onthetop\Models\Tutorial\Theme::class)
                ->setFetchColumns(['name', 'module_id', 'id'])
                ->setDisplay(function ($model) {
                    return "{$model->module->name} - {$model->name} / {$model->module->lang_code}";
                })
            , AdminFormElement::checkbox('active', __('Показывать на сайте'))
            , AdminFormElement::text('youtube', __('Ссылка на видео'))->required(),
            AdminFormElement::multiselect("statuses", __("Статусы"))
                ->setModelForOptions(Status::class, 'id')
                ->setFetchColumns(['name', 'id'])
                ->setDisplay('name')
                ->setHelpText(__('Статусы для достижения которых необходимо просмотреть данный урок'))
            , AdminFormElement::ckeditor('attachment', __('Приложение'))
        ]);

//        $panel->with('theme');

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
