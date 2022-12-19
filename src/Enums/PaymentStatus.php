<?php

namespace Laraditz\Gkash\Enums;

class PaymentStatus extends Enums
{
    const Created       = 1;
    const Pending       = 2;
    const Success       = 3;
    const Failed        = 4;
    const Cancelled     = 5;
    const None          = 6;
}
