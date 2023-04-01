<?php

namespace App\Enums;

enum EnvEnum: string
{
    case LOCAL = 'local';
    case DEBUG = 'debug';
    case STAGING = 'staging';
    case PRODUCTION = 'production';
}
