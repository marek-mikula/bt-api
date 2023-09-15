<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;

class BaseBatchJob extends BaseJob
{
    use Batchable;
}
