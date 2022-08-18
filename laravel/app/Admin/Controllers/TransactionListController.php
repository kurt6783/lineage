<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use App\Admin\Repositories\TreasureDistributionsRepository;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Support\Facades\DB;

class TransactionListController extends Controller
{
    protected $title = '交易列表';

    protected $description = [
        'index'  => '寶物分贓的物品交易紀錄',
        'edit'   => '編輯',
    ];


    // 返回页面标题
    protected function title()
    {
        return $this->title ?: admin_trans_label();
    }

    // 返回描述信息
    protected function description()
    {
        return $this->description;
    }

    // 列表页
    public function index(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['index'] ?? trans('admin.list'))
            ->body($this->grid());
    }

    public function edit($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['edit'])
            ->body($this->form($id)->edit($id));
    }

    public function update($id)
    {
        return $this->form()->update($id);
    }

    protected function grid()
    {
        return Grid::make(TreasureDistributionsRepository::with(['treasure']), function (Grid $grid) {
            $grid->setActionClass(Grid\Displayers\Actions::class);
            if (!Admin::user()->isAdministrator()) {
                $grid->model()->where('treasure_distributions.user_id', Admin::user()->id);
            }
            $grid->model()->orderBy('treasure.created_at');

            //column
            $grid->column('treasure.id', '寶物編號')->sortable();
            $grid->column('treasure.title', '項目');
            $grid->column('treasure.product', '寶物')->sortable();
            $grid->column('treasure.user_id', '負責人')->display(function ($value) {
                return DB::table('admin_users')->find($value)->name;
            });
            $grid->column('user_id', '分贓者')->display(function ($value) {
                return DB::table('admin_users')->find($value)->name;
            });
            $grid->column('player_name', '交易帳號');
            $grid->column('product', '交易物品');
            $grid->column('treasure.unit_price', '交易金額')->display(function ($value) {
                return is_null($value) ? $value : number_format($value);
            });
            $grid->column('status', '狀態')->using(['待上架', '分贓中', '完成']);

            //filter
            $grid->quickSearch()->placeholder('搜尋項目、寶物、交易物品...');

            //disable
            $grid->disableCreateButton();
            $grid->disableViewButton();
            // $grid->disableEditButton();
            $grid->disableDeleteButton();
            $grid->disableRowSelector();
            // $grid->showQuickEditButton();
        });
    }

    // 表单
    protected function form()
    {
        return Form::make(TreasureDistributionsRepository::with(['treasure']), function (Form $form) {
            $form->display('treasure.id', '寶物編號');
            $form->display('treasure.title', '項目');
            $form->display('treasure.product', '寶物');
            $form->text('player_name', '交易帳號');
            $form->text('product', '交易物品');
            $form->display('treasure.unit_price', '交易金額');

            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->disableViewButton();
            $form->disableResetButton();
            $form->disableDeleteButton();
        });
    }
}
