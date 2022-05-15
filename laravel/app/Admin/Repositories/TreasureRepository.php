<?php

namespace App\Admin\Repositories;

use Dcat\Admin\Repositories\EloquentRepository;
use App\Models\Treasure;

class TreasureRepository extends EloquentRepository
{
     protected $eloquentClass = Treasure::class;
}
