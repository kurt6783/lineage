<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use App\Admin\Repositories\RateRepository;

class RateController extends Controller
{

    protected $title = '分鑽比例';

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

    public function show($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['show'] ?? trans('admin.show'))
            ->body($this->detail($id));
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

    public function destroy($id)
    {
        return $this->form()->destroy($id);
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
        return Grid::make(new RateRepository(), function (Grid $grid) {
            $grid->setActionClass(Grid\Displayers\Actions::class);
            $grid->column('name', '名目');
            $grid->column('proportion', '比例（％）');
            $grid->column('updater_id', '異動人')
                ->display(function () {
                    $userModel = config('admin.database.users_model');
                    return $userModel::find($this->updater_id)->name;
                });
            $grid->column('updated_at', '異動時間');
        });
    }

    protected function form()
    {
        return Form::make(new RateRepository(), function (Form $form) {
            $form->text('name', '名目');
            $form->number('proportion', '比例（％）');

            $form->hidden('updater_id')->default(Admin::user()->id);

            if ($form->isCreating()) {
                $form->hidden('creator_id')->default(Admin::user()->id);
                $form->saving(function (Form $form) {
                    $form->creator_id = Admin::user()->id;
                });
            }

            $form->saving(function (Form $form) {
                $form->updater_id = Admin::user()->id;
            });

            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->disableResetButton();
            $form->disableDeleteButton();
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new RateRepository(), function (Show $show) {
            $show->name('名目');
            $show->proportion('比例（％）');

            $show->disableDeleteButton();
        });
    }
}