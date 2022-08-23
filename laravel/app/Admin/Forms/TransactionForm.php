<?php

namespace App\Admin\Forms;

use Dcat\Admin\Widgets\Form;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Contracts\LazyRenderable;
use App\Models\Player;
use App\Admin\Renderable\PlayerTable;
use Dcat\Admin\Models\Administrator;
use App\Admin\Renderable\UserTable;
use App\Models\Treasure;

class TransactionForm extends Form implements LazyRenderable
{
    use LazyWidget;
    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return mixed
     */

    public function handle(array $input)
    {
        $treasure = Treasure::findOrFail($this->payload['id']);
        $player = Player::findOrFail($input['buyer_id']);
        $accountant = Administrator::findOrFail($input['accountant_id']);

        $treasure->update([
            'owner' => $player->id,
            'selling_price' => $input['selling_price'],
            'updater_id' => $accountant->id,
            'accountant_id' => $accountant->id,
            'sell_at' => $input['sell_at'],
            'status' => Treasure::status['SOLD'],
        ]);

        return $this->response()
            ->success('Transaction successfully.')
            ->refresh();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->selectTable('buyer_id', '購買者')
            ->title('購買者')
            ->dialogWidth('50%')
            ->from(PlayerTable::make())
            ->model(Player::class, 'id', 'name')
            ->required();
        $this->number('selling_price', '售價');
        $this->selectTable('accountant_id', '收鑽者')
            ->title('收鑽者')
            ->dialogWidth('50%')
            ->from(UserTable::make())
            ->model(Administrator::class, 'id', 'name')
            ->required();
        $this->datetime('sell_at', '販售時間')
            ->format('YYYY-MM-DD HH:mm')
            ->required();
    }

    /**
     * The data of the form.
     *
     * @return array
     */
    public function default()
    {
        $treasure = Treasure::findOrFail($this->payload['id']);

        $result = [];

        if ($treasure->status == Treasure::status['SOLD']) {
            $result = [
                'buyer_id' => $treasure->owner,
                'selling_price' => $treasure->selling_price,
                'accountant_id' => $treasure->accountant_id,
                'sell_at' => $treasure->sell_at,
            ];
        }

        return $result;
    }
}
