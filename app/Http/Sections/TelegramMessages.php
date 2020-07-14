<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Models\TelegramHistory;
use Illuminate\Database\Eloquent\Model;
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
class TelegramMessages extends Section implements Initializable
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
        return false;
    }

    public function isEditable(Model $model)
    {
        return false;
    }

    public function initialize()
    {
    }

    public function getIcon()
    {
        return 'fa fa-telegram';
    }

    public function getTitle()
    {
        return __('admin.telegram.title');
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay($user_id)
    {
        $display = AdminDisplay::datatablesAsync();
        $display->setOrder([[5, 'desc']]);

        $display->getScopes()->push(['byUser', $user_id]);

//        $display->setDisplaySearch(true);

        $display->setColumns([
            AdminColumn::text('id', __('admin.telegram.rows.id'))->setWidth(80),
            AdminColumn::text('telegram_id', __('admin.telegram.rows.telegram_id'))->setWidth(100),
            AdminColumn::text('message_id', __('Message ID'))->setWidth(100),
            AdminColumn::custom('Статус', function (TelegramHistory $model) {
                return TelegramHistory::getStatusNames()[$model->status];
            }),
            AdminColumn::text('message', __('admin.telegram.rows.message')),
            AdminColumn::datetime('created_at', __('admin.telegram.rows.created_at'))->setWidth(120),
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
}