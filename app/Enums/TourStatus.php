<?php

namespace App\Enums;

enum TourStatus: int
{
    case DRAFT = 0;
    case PUBLISHED = 1;
    case WAITING = 2;
}
