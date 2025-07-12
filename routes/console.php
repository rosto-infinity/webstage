<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:dbbackup')->daily();
