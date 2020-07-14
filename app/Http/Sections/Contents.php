<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnEditable;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use onthetop\Models\Content;
use onthetop\Models\InterfaceLanguage;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;

/**
 * Class Prices
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Contents extends Section
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

//    public function isDeletable(\Illuminate\Database\Eloquent\Model $model) {
//        return false;
//    }
//
//    public function isCreatable() {
//        return false;
//    }
//
//    public function isEditable(\Illuminate\Database\Eloquent\Model $model) {
//        return false;
//    }

    public function getIcon()
    {
        return 'fa fa-book';
    }

    public function getTitle()
    {
        return __('admin.contents.title');
    }

    public function getEditTitle()
    {
        return __('admin.contents.title_edit');
    }

    public function getCreateTitle()
    {
        return __('admin.contents.title_create');
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display = AdminDisplay::datatables();

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns(
                AdminColumn::link('id', '#')->setWidth('30px')
                , AdminColumn::link('name', __('admin.contents.name'))
            )->disablePagination();

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
        return AdminForm::panel()->addBody([
            AdminFormElement::text('name', __('admin.contents.name'))->required()
            , AdminFormElement::ckeditor('content', __('admin.contents.content'))
            , AdminFormElement::ckeditor('content_en', __('admin.contents.content_en'))
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
