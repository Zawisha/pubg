<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnEditable;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use AdminSection;
use App\Models\AdminAuthHistory;
use Illuminate\Support\Facades\Auth;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;

/**
 * Class Prices
 *
 * @property \onthetop\Models\Payment\Price $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Admins extends Section
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
        return 'fa fas fa-user-cog';
    }

    public function getTitle()
    {
        return __('admin.admins.title');
    }

    public function getEditTitle()
    {
        return __('admin.admins.title_edit');
    }

    public function getCreateTitle()
    {
        return __('admin.admins.title_create');
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display = AdminDisplay::datatables();

        $display->setApply(function ($q) {
            if (!Auth::user()->isSuperAdmin()) {
                $q->where('role', '<>', 'super_admin');
            }
        });

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns(
                AdminColumn::link('id', '#')->setWidth('30px'),
                AdminColumn::link('name', __('admin.admins.name')),
                AdminColumn::link('email', __('admin.admins.email'))
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
        $panel = AdminForm::panel()->addBody([
            AdminFormElement::text('name', __('admin.admins.name'))->required(),
            AdminFormElement::text('email', __('admin.admins.email'))->required(),
            AdminFormElement::text('telegram_id', __('admin.admins.telegram_id')),
            AdminFormElement::select('role', __('admin.admins.role'),
                Auth::user()->isSuperAdmin() ?
                    [
                        'admin' => 'admin',
                        'manager' => 'manager',
                        'super_admin' => 'super admin'
                    ] :
                    [
                        'admin' => 'admin',
                        'manager' => 'manager',
                    ]),
            AdminFormElement::password('password', __('admin.admins.password'))
//                ->required()
                ->allowEmptyValue()
                ->hashWithBcrypt()
//            ->setHelpText('При сохранении обязательно - ввести пароль. Иначе вместо пароля будет хрень и войти не получится. Такой-вот глюк sOwl.'),
        ]);

        if (Auth::user()->isSuperAdmin()) {
            $adminHistory = AdminSection::getModel(AdminAuthHistory::class)->fireDisplay();
            $adminHistory->getScopes()->push(['byAdmin', $id]);
            $panel->addBody([
                AdminFormElement::columns()->addColumn([
                    $adminHistory
                ])
            ]);
        }

        return $panel;
    }

    /**
     * @return void
     */
    public function onDelete($id)
    {
        // remove if unused
    }

    public function onSave($id)
    {
        debug('save');
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }

}
