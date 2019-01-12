<?php

namespace App\Commands\Task5;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Exception;

class MasterMind extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'mastermind:play';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    public static $availableLetters = [
      'A', 'B', 'C', 'D', 'E', 'F'
    ];

    public static $goodPlace = 'X';
    public static $goodLetter = 'O';
    public static $wrongGuess = null;
    public static $codesLength = 4;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            $tries = readline("Enter tries count: \n");
            $tries= intval(preg_replace("/[^0-9]/", "", $tries));

            echo "Available letters: ";
            foreach (static::$availableLetters as $letter)
            {
                echo $letter . " ";
            }

            $codesLength = static::$codesLength;
            $generatedCode = $this->generateCoderCode();

            echo "\n Enter you codes {$tries}. Must have {$codesLength} chars: \n";

            for ($index = 0; $index < $tries; $index++)
            {
                $number = $index + 1;
                echo "TRY {$number} \n";
                $enteredMessage = readline("Enter code {$number}:");
                $enteredMessage = preg_replace("/[^A-Z]/", "", strtoupper($enteredMessage));
                $enteredCode = str_split($enteredMessage);
                $checkedCode = [];
                $goodAnswer = true;

                for ($position = 0; $position < $codesLength; $position++)
                {
                    $letter = $enteredCode[$position];

                    if ($letter === $generatedCode[$position]) {
                        $checkedCodes[$index][$position] = static::$goodPlace;
                        continue;
                    }

                    if (in_array($letter, $generatedCode)) {
                        $checkedCodes[$index][$position] = static::$goodLetter;
                        $goodAnswer = false;
                        continue;
                    }

                    $checkedCodes[$index][$position] = static::$wrongGuess;
                    $goodAnswer = false;
                }

                if ($goodAnswer === true) {
                    echo "\n\n YOU WON! \n\n";
                    echo "Coder code was: [" . implode("", $generatedCode) . "] \n";
                }

                foreach ($checkedCode as $status) {
                    if (!is_null($status)) {
                        echo $status;
                    }
                }
            }

            echo "\n\n YOU LOST! \n\n";
            echo "Coder code was: [" . implode("", $generatedCode) . "] \n";

            return;

        } catch (Exception $e) {
            echo $e->getMessage();
            return;
        }
    }

    public function generateCoderCode(): array
    {
        return ['B', 'F', 'A', 'C'];
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
