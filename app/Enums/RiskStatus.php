<?php

namespace App\Enums;

enum RiskStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Archived = 'archived';
}
