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

            $codesLength = static::$codesLength;
            $generatedCode = $this->generateCoderCode();

            echo "\n\n";
            
            echo "**************************\n";
            echo "*                        *\n";
            echo "*     MASTER MIND        *\n";
            echo "*                        *\n";
            echo "**************************\n";

            echo "\n\n";
            $tries = readline("Enter tries count: \n");
            $tries= intval(preg_replace("/[^0-9]/", "", $tries));

            echo "\n\n";
            echo "=== RULES === \n";
            echo "1. You can enter only specified letters. Available letters: ";
            foreach (static::$availableLetters as $letter)
            {
                echo $letter . " ";
            }
            echo "\n";

            echo "2. Code must have {$codesLength} chars.\n";
            echo "3. If code is not correct you lose you try. \n";
            echo "\n\n";

            echo "You have {$tries} tries. Good luck!!!\n";
            echo "START!";
            echo "\n";

            for ($index = 0; $index < $tries; $index++)
            {
                $number = $index + 1;
                echo "\n\n";
                echo "TRY {$number} \n";
                $enteredMessage = readline("Enter code {$number}:");
                $enteredMessage = preg_replace("/[^A-Z]/", "", strtoupper($enteredMessage));

                if (!$this->validateEnteredText($enteredMessage)) {
                    continue;
                }

                $enteredCode = str_split($enteredMessage);
                $checkedCode = [];
                $goodAnswer = true;

                for ($position = 0; $position < $codesLength; $position++)
                {
                    $letter = $enteredCode[$position];

                    if ($letter === $generatedCode[$position]) {
                        $checkedCode[$position] = static::$goodPlace;
                        continue;
                    }

                    if (in_array($letter, $generatedCode)) {
                        $checkedCode[$position] = static::$goodLetter;
                        $goodAnswer = false;
                        continue;
                    }

                    $checkedCode[$position] = static::$wrongGuess;
                    $goodAnswer = false;
                }

                if ($goodAnswer === true) {
                    echo "\n\n YOU WON! \n\n";
                    echo "Coder code was: [" . implode("", $generatedCode) . "] \n";
                    return;
                }

                foreach ($checkedCode as $status) {
                    if (!is_null($status)) {
                        echo $status;
                    } else {
                        echo " ";
                    }
                }
            }
            echo "\n\n";

            echo "YOU LOST! \n\n";
            echo "Coder code was: [" . implode("", $generatedCode) . "] \n";

            return;

        } catch (Exception $e) {
            echo $e->getMessage();
            return;
        }
    }

    public function generateCoderCode(): array
    {
        $letters = static::$availableLetters;
        $result = [];

        for ($i = 0; $i < static::$codesLength; $i++)
        {
            $randomLetterIndex = rand(0, count($letters) - 1);
            $result[$i] = $letters[$randomLetterIndex];
            unset($letters[$randomLetterIndex]);
            $newLetters = [];

            foreach ($letters as $letter) {
                $newLetters[] = $letter;
            }

            $letters = $newLetters;
        }

        return $result;
    }

    public function validateEnteredText($text): bool
    {
        $letters = str_split($text);
        $enteredTextLength = count($letters);
        $expectedLength = static::$codesLength;

        if ($enteredTextLength !== $expectedLength) {
            echo "Code length is incorrect. You length is {$enteredTextLength}, it should be {$expectedLength} \n";
            return false;
        }

        $usedLetters = [];

        foreach ($letters as $letter)
        {
            if (in_array($letter, $usedLetters)) {
                echo "Letter {$letter} is duplicated!\n";
                return false;
            }

            $usedLetters[] = $letter;

            if (!in_array($letter, static::$availableLetters)) {
                echo "Letter {$letter} cannot be used!\n";
                return false;
            }
        }

        return true;
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
