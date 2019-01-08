<?php

namespace App\Commands\Task3;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class AffineDecode extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'affine:decode';

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
        $message = readline("Enter text to decode: ");

        $a = intval(preg_replace("/[^0-9]/", "", $a));
        $b = intval(preg_replace("/[^0-9]/", "", $b));
        $message = preg_replace("/[^A-Z]/", "", strtoupper($message));

        $x = $this->extendedEuclides(26, $a);

        $encoded = "";

        for ($index = 0; $index < strlen($message); $index++) {

            $letter = $message[$index];
            $dec = ord($letter) - 65;
            $e = ($x * ($dec - $b + 26)) % 26;

            $encoded .= chr(abs($e) + 65);
        }

        echo "Decoded message is: {$encoded} \n";

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

    private function extendedEuclides($nwd_a, $nwd_b)
    {
    $r = null;
    $nwd = null;

    $a = null;
    $q = null;
    $b = null;

    $x = null;
    $x1 = null;
    $x2 = null;

    $y = null;
    $y1 = null;
    $y2 = null;

    // a must be greater than b
    if ($nwd_b > $nwd_a)
    {
        $nwd = $nwd_b;
        $nwd_b = $nwd_a;
        $nwd_a = $nwd;
    }

    //initialize a and b
    $a = $nwd_a;
    $b = $nwd_b;

    //initialize r and nwd
    $q = floor($a / $b);
    $r = $a - $q * $b;
    $nwd = $b;

    //initialize x and y
    $x2 = 1;
    $x1 = 0;
    $y2 = 0;
    $y1 = 1;
    $x = 1;
    $y = $y2 - ($q - 1)* $y1;

    while ($r != 0)
    {
        $a = $b;
        $b = $r;

        $x = $x2 - $q * $x1;
        $x2 = $x1;
        $x1 = $x;

        $y = $y2 - $q * $y1;
        $y2 = $y1;
        $y1 = $y;

        $nwd = $r;
        $q = floor($a / $b);
        $r = $a - $q * $b;
    }

        if ($y < 0) {
            return $y + $nwd_a;
        }

        return $y;
    }
}
