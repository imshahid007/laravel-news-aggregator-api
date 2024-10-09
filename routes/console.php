<?php

use Illuminate\Support\Facades\Schedule;


// Run Articles Aggregator Command Daily Without Overlapping
Schedule::command('app:fetch-articles')->daily()->runInBackground()->withoutOverlapping();
