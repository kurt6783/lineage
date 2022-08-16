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
use App\Admin\Repositories\PlayerRepository;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Support\Facades\DB;
use App\Admin\Renderable\UserTable;
use Dcat\Admin\Models\Administrator;

class PlayerController extends Controller
{

    protected $title = '玩家角色';

    protected $description = [
        'index'  => 'Index',
        'show'   => 'Show',
        'edit'   => 'Edit',
        'create' => 'Create',
    ];

    protected $translation;

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

    public function store()
    {
        return $this->form()->store();
    }

    protected function title()
    {
        return $this->title ?: admin_trans_label();
    }

    protected function description()
    {
        return $this->description;
    }

    protected function grid()
    {
        return Grid::make(new PlayerRepository(), function (Grid $grid) {
            $grid->setActionClass(Grid\Displayers\Actions::class);
            $grid->column('name');
            $grid->column('profession');
            $grid->column('blood_alliance');
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new PlayerRepository(), function (Show $show) {
            
        });
    }

    protected function form()
    {
        return Form::make(new PlayerRepository(), function (Form $form) {
            $form->text('name', 'ID');
            $form->text('profession', '職業');
            $form->text('blood_alliance', '血盟');

            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->disableResetButton();
            $form->disableDeleteButton();
        });
    }
}