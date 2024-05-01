<?php

namespace StaffCollab\HebrewDate\Commands;

use Illuminate\Console\Command;

class HebrewDateCommand extends Command
{
    public $signature = 'hebrew-date';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
