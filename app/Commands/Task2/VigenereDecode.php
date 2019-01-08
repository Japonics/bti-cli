<?php

namespace App\Commands\Task2;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class VigenereDecode extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'vigenere:decode';

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
        $key = readline("Enter key used to decode: ");
        $message = readline("Enter text to decode: ");
        print_r($message . "\n");

        $message = strtoupper($message);
        $message = preg_replace("/[^A-Z]/", "", $message);
        $key = preg_replace("/[^A-Z]/", "", $key);
        $length = strlen($message);

        $preparedKey = VigenereEncode::prepareKey($key, $message);
        $result = '';

        for($i = 0; $i < $length; ++$i)
        {
            $row = (ord($preparedKey[$i]) - 65) * 26;

            for($j = 0; $j < 26; ++$j)
            {
                $pos = $row + $j;

                if(VigenereEncode::$table[$pos] == $message[$i])
                {
                    $result .= chr($j + 65);
                    break;
                }
            }
        }

        echo "Decoded message: \r\n";
        echo "{$result} \r\n";

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
