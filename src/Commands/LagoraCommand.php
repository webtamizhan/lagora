<?php

namespace Webtamizhan\Lagora\Commands;

use Illuminate\Console\Command;

class LagoraCommand extends Command
{
    public $signature = 'lagora';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
