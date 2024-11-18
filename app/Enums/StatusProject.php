<?php

namespace App\Enums;

use Mokhosh\FilamentKanban\Concerns\IsKanbanStatus;

enum StatusProject: string
{
    use IsKanbanStatus;

    case Todo = 'To Do';
    case Doing = 'In Progress';
    case Done = 'Done';
}
