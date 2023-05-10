<?php

namespace App\Enums;

enum GoalStatus: string {

    case OnTrack = 'on_track';
    case OffTrack = 'off_track';
    case Completed = 'completed';

}
