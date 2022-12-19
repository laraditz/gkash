<?php

namespace Laraditz\Gkash\Enums;

class RefundStatus extends Enums
{
    const None          = 1;
    const Pending       = 2;
    const Success       = 3;
    const Failed        = 4;
    const Cancelled     = 5;
}
