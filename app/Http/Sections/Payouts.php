<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use AdminColumnEditable;
use AdminColumnFilter;
use App\Models\Payment\Payment;
use App\Models\Payment\Payout;
use App\Models\Transaction;
use App\Models\WithdrawTransaction;
use onthetop\Models\Payment\PaypalPayout;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Section;
use SleepingOwl\Admin\Contracts\Initializable;

/**
 * Class Payments
 *
 * @property WithdrawTransaction $model
 * @method WithdrawTransaction getModelValue()
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Payouts extends Section
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
        return true;
    }

    public function getIcon()
    {
        return 'fa fa-credit-card';
    }

    public function getTitle()
    {
        return __('Withdraw Requests');
    }

    public function getEditTitle()
    {
        return 'Edit Withdraw Request';
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $tabs = AdminDisplay::tabbed();


//        $tabs->setParameter('userId', $userId);

        $display = AdminDisplay::datatablesAsync()->setName('only_new');
        $display->getScopes()->push('IsNew');
        $display->getScopes()->push('IsWithdraw');

//        $display = AdminDisplay::datatables();

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns([
                    AdminColumn::text('id', '#')->setWidth('30px'),
                    AdminColumn::text('user.name', __('User')),
                    AdminColumn::custom(__('Email'), function ($model) {
                        return $model->comment['account'];
                    }),
                    AdminColumn::text('amount', __('Amount, $;')),
                    AdminColumn::custom('Status', function ($model) {
                        return __(WithdrawTransaction::WITHDRAW_STATUS_NAMES[$model->status]);
                    })
                        ->setSearchable(false),
                    AdminColumn::text('updated_at', __('Changed')),
                ]
            )->paginate(20);

        $display->with('user');

        $tabs->appendTab($display, 'New')->setBadge(
            WithdrawTransaction::isWithdraw()->isNew()->count());

        $display = AdminDisplay::datatablesAsync()->setName('canceled');
        $display->getScopes()->push('IsWithdraw');
        $display->getScopes()->push('isCanceled');
//        $display->getScopes()->push('IsNew');

//        $display = AdminDisplay::datatables();

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns([
                AdminColumn::text('id', '#')->setWidth('30px'),
                AdminColumn::text('user.name', __('User')),
                AdminColumn::custom(__('Email'), function ($model) {
                    return $model->comment['account'];
                }),
                AdminColumn::text('amount', __('Amount, $;')),
                AdminColumn::custom('Status', function ($model) {
                    return __(WithdrawTransaction::WITHDRAW_STATUS_NAMES[$model->status]);
                })
                    ->setSearchable(false),
                AdminColumn::text('updated_at', __('Changed')),
            ])->paginate(20);

        $display->with('user');

        $tabs->appendTab($display, 'Canceled')->setBadge(
            WithdrawTransaction::isWithdraw()->isCanceled()->count());


        $display = AdminDisplay::datatablesAsync()->setName('all');
        $display->getScopes()->push('IsWithdraw');
//        $display->getScopes()->push('IsNew');

//        $display = AdminDisplay::datatables();

        $display->setHtmlAttribute('class', 'table-primary')
            ->setColumns([
                AdminColumn::text('id', '#')->setWidth('30px'),
                AdminColumn::text('user.name', __('User')),
                AdminColumn::custom(__('Email'), function ($model) {
                    return $model->comment['account'];
                }),
                AdminColumn::text('amount', __('Amount, $;')),
                AdminColumn::custom('Status', function ($model) {
                    return __(WithdrawTransaction::WITHDRAW_STATUS_NAMES[$model->status]);
                })
                    ->setSearchable(false),
                AdminColumn::text('updated_at', __('Created')),
                AdminColumn::text('created_at', __('Lat Changes')),
            ])->paginate(20);

        $display->with('user');

        $tabs->appendTab($display, 'All')->setBadge(
            WithdrawTransaction::isWithdraw()->count());

        return $tabs;
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        $panel = AdminForm::panel()->addBody([
            '<label>' . __('Request date') . ':</label>',
            AdminColumn::text('created_at'),
            '<label>' . __('Last Changes') . ':</label>',
            AdminColumn::text('updated_at'),
            '<label>' . __('User') . ':</label>',
            AdminColumn::relatedLink('user.name', 'User'),
            '<label>' . __('E-Mail') . ':</label>',
            AdminColumn::custom(__('Email'), function ($model) {
                return $model->comment['account'];
            }),
            AdminFormElement::text('amount', 'Amount $')->setReadOnly(true),
            AdminFormElement::select('status', __('Status'),
                [
                    Transaction::STATUS_NORMAL => __('admin.transactions.statuses.normal'),
                    Transaction::STATUS_CANCELED => __('admin.transactions.statuses.canceled'),
                    Transaction::STATUS_CONFIRMED => __('admin.transactions.statuses.confirmed'),
                ]
                )->required(),
//            AdminFormElement::select('comment', __('Причина отклонения'), PaypalPayout::REASON_NAMES),
        ]);

        $panel->getButtons()->setButtons([
            'delete' => null, // Убираем кнопку Delete
            'save' => (new SaveAndClose())->setText('Save'),
            'cancel' => (new Cancel())->setText('Cancel'),
        ]);

        return $panel;
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit(null);
    }

    /**
     * @return void
     */
    public function onDelete($id)
    {
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }

}
