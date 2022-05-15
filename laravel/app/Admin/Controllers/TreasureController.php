<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use App\Admin\Metrics\Examples;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\Dashboard;
use App\Admin\Repositories\TreasureRepository;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Support\Facades\DB;
use App\Admin\Renderable\UserTable;
use Dcat\Admin\Models\Administrator;

class TreasureController extends Controller
{
    // 页面标题
    protected $title = '寶物';

    // 页面描述信息
    protected $description = [
        'index'  => 'Index',
        'show'   => 'Show',
        'edit'   => 'Edit',
        'create' => 'Create',
    ];

    // 指定语言包名称，默认与当前控制器名称相对应
    protected $translation;

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

    // 数据表格
    protected function grid()
    {
        return Grid::make(new TreasureRepository(), function (Grid $grid) {
            $grid->setActionClass(Grid\Displayers\Actions::class);
            $status = request()->status ?? 0;
            if ($status == '0') {
                $grid->model()->where('status', '還沒售出');
            } elseif ($status == '1') {
                $grid->model()->where('status', '分贓中');
            } elseif ($status == '2') {
                $grid->model()->where('status', '結束');
            }
            $grid->header(function () use ($status) {
                $tab = Tab::make();
                $tab->addLink('還沒售出', '?status=0', $status == '0' ? true : false);
                $tab->addLink('分贓中', '?status=1', $status == '1' ? true : false);
                $tab->addLink('結束', '?status=2', $status == '2' ? true : false);
                return $tab;
            });
            $grid->column('id')->sortable();
            $grid->column('user_id', '持有者')->display(function ($userId) {
                return DB::table('admin_users')->find($userId)->name;
            });
            $grid->column('product');
            $grid->column('selling_price');
            $grid->column('unit_price');
            $grid->column('status');
        });
    }

    // 数据详情
    protected function detail($id)
    {
        return Show::make($id, new User(), function (Show $show) {
            // ...
        });
    }

    // 表单
    protected function form()
    {
        return Form::make(new TreasureRepository(), function (Form $form) {
            $options = DB::table('admin_users')->pluck('name', 'id');
            $form->display('id');
            $form->select('user_id', '持有者')
                ->options($options)
                ->required();
            $form->text('product', '寶物名稱');
            $form->number('selling_price', '金額（稅後）');
            $form->number('unit_price')->default('100');
            if ($form->isCreating()) {
                $form->select('status')->options([
                    '還沒售出' => '還沒售出'
                ])->default('還沒售出');
            } else {
                $form->select('status')->options([
                    '還沒售出' => '還沒售出',
                    '分贓中' => '分贓中', 
                    '結束' =>'結束'
                ]);
            }

            // $form->selectTable('user_id', '分贓者')
            //     ->title('分贓者')
            //     ->from(UserTable::make(['id' => $form->getKey()]))
            //     ->model(Administrator::class, 'id', 'name');

            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->disableResetButton();
            $form->disableDeleteButton();
        });
    }

    // 列表页
    public function index(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['index'] ?? trans('admin.list'))
            ->body($this->grid());
    }

    public function create(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['create'])
            ->body($this->form());
    }

    public function edit($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['edit'])
            ->body($this->form($id)->edit($id));
    }

    public function store()
    {
        return $this->form()->store();
    }

    public function update($id)
    {
        return $this->form()->update($id);
    }

    public function destroy($id)
    {
        return $this->form()->destroy($id);
    }
}
