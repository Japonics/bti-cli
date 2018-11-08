<?php

namespace App\Commands\Task1;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class CaesarEncode extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'caesar:encode';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        print_r("Caesar - encode:\n");
        $moveBy = intval(readline("Move by: "));
        $message = readline("Enter text to encode: ");
        print_r($message . "\n");
        $chars = str_split($message);
        $encodedMessage = "";

        foreach ($chars as $char)
        {
            $decChar = ord($char);

            if ($decChar === 32) continue;
            $decChar += $moveBy;
            if ($decChar >= 91) $decChar -= 26;

            $encodedMessage .= chr($decChar);
        }

        print_r( "Encoded message: {$encodedMessage}\n");

        return;
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
