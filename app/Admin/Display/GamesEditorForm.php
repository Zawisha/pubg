<?php
/**
 * Created by PhpStorm.
 * User: don3d_000
 * Date: 10.09.2019
 * Time: 1:26
 */

namespace App\Admin\Display;


use App\Models\Game;

class GamesEditorForm extends \SleepingOwl\Admin\Display\Display
{
    public function __construct()
    {
        parent::__construct();
        $this->addScript('members-editor', asset('/js/admin/members-editor.js'), ['admin-default']);
        $this->addStyle('members-editor', asset('css/admin/members-editor.css'), ['admin-default']);
//        $this->addScript('vue-konva', 'https://cdn.jsdelivr.net/npm/vue-konva@2.0.7/umd/vue-konva.min.js', ['konva']);
//        $this->addScript('points-area-component', asset('/js/admin/points-area-component.js'), ['vue-konva']);
    }

    var $game;
    var $multiple = 1;
    var $index = 0;

    public function setGame(Game $game)
    {
        $this->game = $game;
    }

    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;
    }

    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'title' => $this->getTitle(),
            'extensions' => $this->getExtensions()->toArray(),
            'attributes' => $this->htmlAttributesToString(),
            'game' => $this->game,
            'index' => $this->index,
            'multiple' => $this->multiple
        ];
    }

    protected $viewPath = 'Display.games-editor-form';
}
