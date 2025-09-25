<?php

namespace App\Enums;

enum CarePlanStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Archived = 'archived';
}
