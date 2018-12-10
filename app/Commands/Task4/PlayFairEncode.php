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
        print_r("PlayFair - encode:\n");
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

            if ($firstLetter === "I") {
                $firstLetter = "J";
            }

            if ($secondLetter === "I") {
                $secondLetter = "J";
            }

            $isFound = false;

            // Check if letters are in the same row
            for ($row = 0; $row < 5; $row++) {

                $record = $matrix[$row];

                if (in_array($firstLetter, $record) && in_array($secondLetter, $record)) {

                    $firstLetterIndex = intval(array_search($firstLetter, $record));
                    $secondLetterIndex = intval(array_search($secondLetter, $record));

                    $tempFirstIndex = intval(($firstLetterIndex + 1) % 5);
                    $tempSecondIndex = intval(($secondLetterIndex + 1) % 5);

                    $result .= $record[$tempFirstIndex];
                    $result .= $record[$tempSecondIndex];

                    $isFound = true;

                    break;
                }
            }

            if ($isFound) {
                continue;
            }

            $isFound = false;

            // Check if letters are in the same column
            for ($col = 0; $col < 5; $col++) {

                $record = [];

                for ($row = 0; $row < 5; $row++) {
                    $record[] = $matrix[$row][$col];
                }

                if (in_array($firstLetter, $record) && in_array($secondLetter, $record)) {

                    $firstLetterIndex = array_search($firstLetter, $record);
                    $secondLetterIndex = array_search($secondLetter, $record);

                    $result .= $record[($firstLetterIndex + 1) % 5];
                    $result .= $record[($secondLetterIndex + 1) % 5];

                    $isFound = true;

                    break;
                }
            }

            if ($isFound) {
                continue;
            }

            $firstLetterRow = 0;
            $firstLetterCol = 0;
            $secondLetterCol = 0;
            $secondLetterRow = 0;

            // Letters are in different rows and columns
            for ($row = 0; $row < 5; $row++) {
                for ($col = 0; $col < 5; $col++) {

                    if ($matrix[$row][$col] === $firstLetter) {
                        $firstLetterRow = $row;
                        $secondLetterCol = $col;
                    }

                    if ($matrix[$row][$col] === $secondLetter) {
                        $secondLetterRow = $row;
                        $firstLetterCol = $col;
                    }
                }
            }

            $result .= $matrix[$firstLetterRow][$firstLetterCol];
            $result .= $matrix[$secondLetterRow][$secondLetterCol];
        }

        print "\n";
        print "Result: {$result}";

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

        for ($col = 0; $col < 5; $col++) {
            for ($row = 0; $row < 5; $row++)
            {
                if ($keyLettersLength !== 0) {

                    $index = ($col + 1) * $row;

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
