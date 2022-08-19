<?php

namespace App\Admin\Renderable;

use App\Models\Player;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class PlayerTable extends LazyRenderable
{
    public function grid(): Grid
    {
        // 获取外部传递的参数
        $id = $this->id;

        return Grid::make(new Player(), function (Grid $grid) {
            $grid->column('id');
            $grid->column('name', '遊戲ID');
            $grid->column('profession', '職業');
            $grid->column('blood_alliance', '血盟');

            $grid->quickSearch(['id', 'name', 'blood_alliance']);

            $grid->paginate(20);
            $grid->disableActions();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('name', '遊戲ID')->width(4);
                $filter->like('profession', '職業')->width(4);
                $filter->like('blood_alliance', '血盟')->width(4);
            });
        });
    }
}