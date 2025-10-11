<?php
namespace App\Enums;

enum AssignmentStatus:string { case Assigned='assigned'; case Accepted='accepted'; case Swapped='swapped'; case Cancelled='cancelled'; }
