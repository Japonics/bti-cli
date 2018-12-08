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

        $result = "";
        $messageLength = strlen($message);

        if ($messageLength % 2 !== 0) {
            $message .= "X";
        }

        $limit =  strlen($message);

        for ($index = 0; $index < $limit; $index = $index + 2) {

            $firstLetter = $message[$index];
            $secondLetter = $message[$index + 1];

            // Check if letters are in the same column
            for ($col = 0; $col < 5; $col++) {
                $column = $matrix[$col];
                print_r($column);

                if (in_array($firstLetter, $column) && in_array($secondLetter, $column)) {

                    $firstLetterIndex = intval(array_search($firstLetter, $column));
                    $secondLetterIndex = intval(array_search($secondLetter, $column));

                    $tempFirstIndex = intval(($firstLetterIndex + 1) % 5);
                    $tempSecondIndex = intval(($secondLetterIndex + 1) % 5);

                    $result += $column[$tempFirstIndex];
                    $result += $column[$tempSecondIndex];

                    continue;
                }
            }

            // Check if letters are in the same row
            for ($row = 0; $row < 5; $row++) {
                $allRow = [];
                for ($col = 0; $col < 5; $col++) {
                    $allRow[] = $matrix[$col][$row];
                }

                if (in_array($firstLetter, $allRow) && in_array($secondLetter, $allRow)) {

                    $firstLetterIndex = array_search($firstLetter, $allRow);
                    $secondLetterIndex = array_search($secondLetter, $allRow);

                    $result += $allRow[($firstLetterIndex + 1) % 5];
                    $result += $allRow[($secondLetterIndex + 1) % 5];

                    continue;
                }
            }

            // Letters are in different rows and columns


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

        $startLetter = ord('A');
        $keyLettersLength = strlen($key);

        for ($row = 0; $row < 5; $row++) {
            for ($col = 0; $col < 5; $col++)
            {
                if ($keyLettersLength !== 0) {

                    $index = ($row + 1) * $col;

                    if (!in_array($key[$index], $letters, true)) {

                        if (!isset($result[$col])) {
                            $result[$col] = [];
                        }

                        $letters[] = $key[$index];
                        $result[$col][$row] = $key[$index];
                        print_r("\t {$key[$index]}");
                        $keyLettersLength--;
                    }

                } else {
                    if ($startLetter === 73){
                        $startLetter++;
                    }

                    $letter = chr($startLetter);

                    if (in_array($letter, $letters)) {
                        do {
                            $startLetter++;
                            $letter = chr($startLetter);
                        } while (in_array($letter, $letters));
                    }

                    if (!in_array($letter, $letters)) {

                        if (!isset($result[$col])) {
                            $result[$col] = [];
                        }

                        $result[$col][$row] = $letter;
                        print_r("\t {$letter}");
                    }

                    $startLetter++;
                }
            }
            print_r("\n");
        }

        return $result;
    }
}
