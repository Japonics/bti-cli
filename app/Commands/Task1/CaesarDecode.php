<?php

namespace App\Commands\Task1;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class CaesarDecode extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'caesar:decode';

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
        print_r("Caesar - decode:\n");
        $movedBy = intval(readline("Moved by: "));
        $message = readline("Enter text to decode: ");
        print_r($message . "\n");
        $chars = str_split($message);
        $encodedMessage = "";

        foreach ($chars as $char)
        {
            $decChar = ord($char);

            $decChar -= $movedBy;
            if ($decChar <= 64) $decChar += 26;

            $encodedMessage .= chr($decChar);
        }

        print_r( "Decoded message: {$encodedMessage}\n");

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
