<?php

namespace App\Admin\Repositories;

use Dcat\Admin\Repositories\EloquentRepository;
use App\Models\TreasureDistributions;

class TreasureDistributionsRepository extends EloquentRepository
{
     protected $eloquentClass = TreasureDistributions::class;
}
