<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static InProduction()
 * @method static static Shipping()
 * @method static static Finished()
 */
final class BatchStatus extends Enum
{

    const InProduction = 0; //生产中
    const Shipping = 1; //运输中
    const Finished = 2; //已完成
}
