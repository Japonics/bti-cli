<?php

namespace App\Commands\Task3;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class AffineEncode extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'affine:encode';

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
        print_r("Affine - encode:\n");
        $a = readline("Enter A parameter: \n");
        $b = readline("Enter B parameter: \n");
        $message = readline("Enter text to encode: ");

        $a = intval(preg_replace("/[^0-9]/", "", $a));
        $b = intval(preg_replace("/[^0-9]/", "", $b));
        $message = preg_replace("/[^A-Z]/", "", strtoupper($message));

        print_r("K = ({$a}, {$b}), so function is e(x) = {$a}x + {$b}\n");
        $encoded = "";

        for ($index = 0; $index < strlen($message); $index++) {

            $letter = $message[$index];
            $dec = ord($letter) - 65;
            $e = (($a * $dec) + $b) % 26;

            $encoded .= chr($e + 65);
        }

        echo "Encoded message is: {$encoded} \n";

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
