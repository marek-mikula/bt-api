<?php

namespace Domain\Cryptocurrency\Enums;

enum OrderStatusEnum
{
    case NEW;
    case PARTIALLY_FILLED;
    case FILLED;
    case CANCELED;
    case PENDING_CANCEL;
    case REJECTED;
    case EXPIRED;
    case EXPIRED_IN_MATCH;
}
