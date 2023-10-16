<?php

namespace Domain\Order\Enums;

enum OrderStatusEnum: string
{
    case NEW = 'NEW';
    case PARTIALLY_FILLED = 'PARTIALLY_FILLED';
    case FILLED = 'FILLED';
    case CANCELED = 'CANCELED';
    case PENDING_CANCEL = 'PENDING_CANCEL';
    case REJECTED = 'REJECTED';
    case EXPIRED = 'EXPIRED';
    case EXPIRED_IN_MATCH = 'EXPIRED_IN_MATCH';
}
