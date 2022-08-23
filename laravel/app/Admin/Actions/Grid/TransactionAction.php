<?php

namespace App\Admin\Actions\Grid;

use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Traits\HasPermissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Widgets\Modal;
use Illuminate\Http\Request;
use App\Admin\Forms\TransactionForm;

class TransactionAction extends RowAction
{
    /**
     * @return string
     */
	protected $title = '<i class="fa fa-eye"></i> 交易';

    /**
     * Handle the action request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request)
    {
        return $this->response();
    }

    public function render()
    {
        $form = TransactionForm::make()
            ->payload(['id' => $this->getKey()]);

        return Modal::make()
            ->lg()
            ->title('寶物交易')
            ->body($form)
            ->button($this->title);
    }
}
