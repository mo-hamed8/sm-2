<?php 

namespace App\Services;

use Carbon\Carbon;

class SM2Algorithm {
    // Interval between reviews
    private $interval;
    
    // E-Factor (EF) for spacing interval
    private $ef;
    
    // Number of repetitions
    private $repetitions;
    
    // Last review date
    private $lastReviewDate;

    // Constructor: allows setting initial values or using defaults
    public function __construct($interval = 1, $ef = 2.5, $repetitions = 0, $lastReviewDate = null) {
        $this->interval = $interval;
        $this->ef = $ef;
        $this->repetitions = $repetitions;
        $this->lastReviewDate = $lastReviewDate ?? date('Y-m-d');
    }

    // Method that applies the SM2 algorithm based on the rating
    public function review($rating) {
        // If rating is less than 3, reset interval to 1 day
        if ($rating < 3) {
            $this->interval = 1;
            $this->repetitions = 0;
        } else {
            if ($this->repetitions === 0) {
                // First review
                $this->interval = 1;
            } elseif ($this->repetitions === 1) {
                // Second review
                $this->interval = 6;
            } else {
                // Subsequent reviews
                $this->interval = round($this->interval * $this->ef);
            }
            // Increment repetitions
            $this->repetitions++;
        }
        
        // Adjust EF based on rating
        $this->ef = $this->ef - 0.8 + (0.28 * $rating) - (0.02 * $rating * $rating);
        if ($this->ef < 1.3) {
            $this->ef = 1.3; // EF should not go below 1.3
        }

        return $this->getNextReviewDate();
    }

    // Method to calculate the next review date
    public function getNextReviewDate() {
        $nextReviewTimestamp = strtotime("+$this->interval days", strtotime($this->lastReviewDate));
        return date('Y-m-d', $nextReviewTimestamp);
    }

    // Method to reset the interval, EF, and repetitions
    public function reset() {
        $this->interval = 1;
        $this->ef = 2.5;
        $this->repetitions = 0;
    }

    // Methods to manually set values
    public function setInterval($interval) {
        $this->interval = $interval;
    }

    public function setEF($ef) {
        $this->ef = $ef;
    }

    public function setRepetitions($repetitions) {
        $this->repetitions = $repetitions;
    }

    public function setLastReviewDate($date) {
        $this->lastReviewDate = $date;
    }

    // Getter methods to retrieve the values
    public function getInterval() {
        return $this->interval;
    }

    public function getEF() {
        return $this->ef;
    }

    public function getRepetitions() {
        return $this->repetitions;
    }

    public function getLastReviewDate() {
        return $this->lastReviewDate;
    }
}

// // Example usage:
// $sm2 = new SM2Algorithm();

// // Manually setting values
// $sm2->setInterval(3); // Set interval to 3 days
// $sm2->setEF(2.2);     // Set EF to 2.2
// $sm2->setRepetitions(2); // Set repetitions to 2
// $sm2->setLastReviewDate('2024-09-21'); // Set last review date

// // Perform a new review
// $rating = 4; // User's rating
// $nextReviewDate = $sm2->review($rating);

// echo "Next review date: $nextReviewDate\n";

?>
