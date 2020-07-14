<?php
/**
 * Created by PhpStorm.
 * User: don3d_000
 * Date: 10.09.2019
 * Time: 1:26
 */

namespace App\Admin\Display;


use App\Models\Game;

class KingGamesEditorForm extends \SleepingOwl\Admin\Display\Display
{
    public function __construct()
    {
        parent::__construct();
        $this->addScript('king-members-editor', asset('/js/admin/king-members-editor.js'), ['admin-default']);
//        $this->addScript('vue-konva', 'https://cdn.jsdelivr.net/npm/vue-konva@2.0.7/umd/vue-konva.min.js', ['konva']);
//        $this->addScript('points-area-component', asset('/js/admin/points-area-component.js'), ['vue-konva']);
    }

    var $game;

    public function setGame(Game $game)
    {
        $this->game = $game;
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
            'game' => $this->game
        ];
    }

    protected $viewPath = 'Display.king-games-editor-form';
}