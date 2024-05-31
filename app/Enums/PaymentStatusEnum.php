<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case CREATED = 'CREATED';
    case FAILED = 'FAILED';
    case CONFIRM = 'CONFIRM';
    case WAITING = 'WAITING';
    case CANCELED = 'CANCELED';

    public static function getValues(): array
    {
        return [self::CONFIRM, self::FAILED, self::CREATED,self::WAITING];
    }
}
