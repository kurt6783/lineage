<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use App\Admin\Repositories\TreasureRepository;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Support\Facades\DB;

class TreasureController extends Controller
{
    /**
     * Set Title
     */
    protected $title = '寶物管理';

    /**
     * Set description for following 4 action pages.
     *
     * @var array
     */
    protected $description = [
        'index'  => 'Index',
        'show'   => 'Show',
        'edit'   => 'Edit',
        'create' => 'Create',
    ];

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->title($this->title)
            ->description($this->description()['index'])
            ->body($this->grid());
    }
    
    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['create'])
            ->body($this->form());
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['edit'])
            ->body($this->form($id)->edit($id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->form()->store();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        return $this->form()->update($id);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->form()->destroy($id);
    }

    /**
     * Get Title
     *
     * @return array
     */
    protected function title()
    {
        return $this->title;
    }

    /**
     * Get description for following 4 action pages.
     *
     * @return array
     */
    protected function description()
    {
        return $this->description;
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(
            new TreasureRepository(), function (Grid $grid) {
                $grid->setActionClass(Grid\Displayers\Actions::class);
                $grid->model()->orderBy('kill_at');

                $grid->column('id')->sortable();
                $grid->column('kill_at', '擊殺時間');
                $grid->column('boss_name', '怪物名稱');
                $grid->column('product', '寶物名稱');
                $grid->column('owner', '持有者');
                $grid->column('deadline', '最後補登時間');
                $grid->column('description', '備註')
                    ->display('公告內容')
                    ->modal(
                        function ($modal) {
                            $modal->title('請將內容公告至Line記事本');
                            $modal->icon('fa-copy');
                            return $this->description;
                        }
                    );

                // disable tools
                $grid->disableFilterButton();
                $grid->disableRefreshButton();
                $grid->disableBatchActions();
                $grid->disableRowSelector();
                $grid->disableViewButton();
            }
        );
    }


    /**
     * Create & Edit Form
     */
    protected function form()
    {
        return Form::make(
            new TreasureRepository(), function (Form $form) {
                $options = DB::table('admin_users')->pluck('name', 'id');
                $form->display('id');
                $form->datetime('kill_at', '擊殺時間')
                    ->format('YYYY-MM-DD HH:mm')->required();
                $form->text('boss_name', '怪物名稱')->required();
                $form->text('product', '寶物名稱')->required();
                $form->select('owner', '持有者')
                    ->options($options)
                    ->required();

                // disable tools
                $form->disableViewButton();
                $form->disableViewCheck();
                $form->disableEditingCheck();
                $form->disableCreatingCheck();
                $form->disableResetButton();
                $form->disableDeleteButton();
            }
        );
    }

}
