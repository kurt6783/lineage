<?php

namespace App\Admin\Repositories;

use Dcat\Admin\Repositories\EloquentRepository;
use App\Models\Player;

class PlayerRepository extends EloquentRepository
{
     protected $eloquentClass = Player::class;
}
