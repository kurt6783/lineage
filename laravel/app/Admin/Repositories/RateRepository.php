<?php

namespace App\Admin\Repositories;

use Dcat\Admin\Repositories\EloquentRepository;
use App\Models\Rate;

class RateRepository extends EloquentRepository
{
     protected $eloquentClass = Rate::class;
}
