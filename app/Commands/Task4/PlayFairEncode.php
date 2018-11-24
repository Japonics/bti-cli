<?php

namespace App\Commands\Task4;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class PlayFairEncode extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'playfair:encode';

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
        $key = readline("Enter KEY: \n");
        $message = readline("Enter text to decode: \n");

        $key = trim(preg_replace("/[^A-Z]/", "", $key));
        $message = preg_replace("/[^A-Z]/", "", strtoupper($message));

        $matrix = static::prepareMatrix($key);

        $helperMatrix = [];
        for ($index = 0; $index < $helperMatrix; $index++) {
            $helperMatrix[$matrix[$index]] = $index;
        }

        $messageLength = strlen($message);
        if ($messageLength % 2 !== 0) {
            $message .= "X";
        }
        $limit =  strlen($message);

        for ($index = 0; $index < $limit; $index = $index + 2) {
            $firstLetter = $message[$index];
            $secondLetter = $message[$index + 1];
            $firstIndex = $helperMatrix[$firstLetter];
            $secondIndex = $helperMatrix[$secondLetter];

            if ($firstIndex > $secondIndex) {
                $min = $secondIndex + 1;
                $max = $firstIndex + 1;
            } else {
                $min = $firstIndex + 1;
                $max = $secondLetter + 1;
            }

            $encodedFirstIndex = 1;
            $encodedSecondIndex = 1;

            if ($max % $min === 0) {

                $encodedFirstIndex = $firstIndex + 5;
                $encodedSecondIndex = $secondIndex + 5;
                continue;
            }
        }

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

    /**
     * Prepare array for key
     *
     * @param $key
     *
     * @return array
     */
    public static function prepareMatrix(string $key): array
    {
        $result = [];
        $letters = [];

        // Delete repeated letters
        for ($index = 0; $index < strlen($key); $index++)
        {
            if (!in_array($key[$index], $letters, true)) {
                $letters[] = $key[$index];
                $result[] = $key[$index];
            }
        }

        $startLetter = ord('A');

        do {
            if ($startLetter === 73){
                $startLetter++;
            }

            $letter = chr($startLetter);

            if (!in_array($letter, $letters)) {
                $result[] = $letter;
            }

            $startLetter++;

        } while (count($result) !== 25);

        for ($index = 0; $index < count($result); $index++)
        {
            if ($index % 5 === 0)
            {
                print_r("\n");
            }

            print_r("\t {$result[$index]}");
        }

        return $result;
    }
}
