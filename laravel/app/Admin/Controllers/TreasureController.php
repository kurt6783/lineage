<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use App\Admin\Repositories\TreasureRepository;
use App\Admin\Renderable\PlayerTable;
use App\Models\Player;

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
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['show'])
            ->body($this->detail($id));
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
            new TreasureRepository(['ownerInfo']), function (Grid $grid) {
                $grid->setActionClass(Grid\Displayers\Actions::class);
                $grid->model()->orderBy('id', 'desc');

                $grid->column('id', '#')->sortable();
                $grid->column('kill_at', '擊殺時間')->display(function(){
                        return date_format($this->kill_at, 'Y-m-d H:i');
                    })->sortable();
                $grid->column('boss_name', '怪物名稱');
                $grid->column('product', '寶物名稱');
                $grid->column('ownerInfo.name', '持有者');
                $grid->column('players', '參與人數')->display(function(){
                        return !is_null($this->players) ? count($this->players) : 0;
                    })->modal(
                        function ($modal) {
                            $modal->title('參與人員清單');
                            $modal->icon('fa-users');
                            if (!is_null($this->players)) {
                                $players = Player::whereIn('id', $this->players)->get('name')->pluck('name')->toArray();
                                return implode('<br>', $players);
                            }
                        }
                    );
                $grid->column('deadline', '最後補登時間')->sortable();
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
            }
        );
    }

    protected function detail($id)
    {
        return Show::make($id, new TreasureRepository(['ownerInfo']), function (Show $show) {
            $show->id('#');
            $show->kill_at('擊殺時間')->as(function () {
                    return date_format($this->kill_at, 'Y-m-d H:i');
                });
            $show->boss_name('怪物名稱');
            $show->product('寶物名稱');
            $show->field('owner_info.name', '持有者');

            $show->deadline('最後補登時間');
            $show->players('參與人員')->as(function () {
                    $players = Player::whereIn('id', $this->players)->get('name')->pluck('name')->toArray();
                    return $players;
                })->label();
            
            $show->divider();
            $show->updater_id('異動人')->as(function () {
                $userModel = config('admin.database.users_model');
                return $userModel::find($this->updater_id)->name;
            });
            $show->updated_at('異動時間');
            $show->creator_id('新增人')->as(function () {
                $userModel = config('admin.database.users_model');
                return $userModel::find($this->creator_id)->name;
            });
            $show->created_at('新增時間');

            //tools
            $show->disableDeleteButton();
        });
    }

    /**
     * Create & Edit Form
     */
    protected function form()
    {
        return Form::make(
            new TreasureRepository(), function (Form $form) {

                $form->display('id', '#');
                $form->datetime('kill_at', '擊殺時間')
                    ->format('YYYY-MM-DD HH:mm')->required();
                $form->text('boss_name', '怪物名稱')->required();
                $form->text('product', '寶物名稱')->required();

                $form->selectTable('owner', '持有者')
                    ->title('持有者')
                    ->dialogWidth('50%')
                    ->from(PlayerTable::make())
                    ->model(Player::class, 'id', 'name')
                    ->required();

                $form->multipleSelectTable('players', '參與人員')
                    ->title('參與人員')
                    ->dialogWidth('50%')
                    ->from(PlayerTable::make())
                    ->model(Player::class, 'id', 'name');
                
                $form->hidden('deadline');
                $form->hidden('description');
                $form->hidden('updater_id')->default(Admin::user()->id);

                if ($form->isCreating()) {
                    $form->hidden('creator_id')->default(Admin::user()->id);
                    $form->saving(function (Form $form) {
                        $form->creator_id = Admin::user()->id;
                    });
                }
                $form->saving(function (Form $form) {
                    $form->updater_id = Admin::user()->id;
                    $form->deadline = date('Y-m-d H:i:s', strtotime($form->kill_at . '+4 days midnight -1 sec'));
                    $owner = Player::where('id', $form->owner)->get('name')->first()->toArray();
                    $players = [];
                    if (!is_null($form->players)) {
                        $players = Player::whereIn('id', explode(',', $form->players))->get('name')->pluck('name')->toArray();
                    }
                    
                    $description = $form->kill_at . '<br>';
                    $description .= $form->boss_name . '  ' . $form->product . '<br>';
                    $description .= '持有者： ' . $owner['name'] . '<br>';
                    $description .= '最後補登時間： ' . $form->deadline . '<br>';
                    $description .= '<br>參與人員：<br>' . implode('<br>', $players) . '<br>';
                    $form->description = $description;
                });

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
