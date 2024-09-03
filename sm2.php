<?php 

namespace App\Services;

class Sm2
{
    // Constructor to initialize the class with necessary data
    public function __construct(private $lastReviewDate, private $easyFactor, private $interval, private $quality)
    {
        // Parameters:
        // $lastReviewDate - Date of the last review
        // $easyFactor - The ease factor that influences the next review interval
        // $interval - The current interval (in days) between reviews
        // $quality - The quality of the last review (user rating from 0 to 5)
    }

    // Method to calculate the next review date based on SM2 algorithm
    public function nextReviewDate()
    {
        // Update the ease factor based on the quality of the last review
        $this->easyFactor = $this->updateEasyFactor();

        // Determine the next interval based on the quality of the last review
        if ($this->quality >= 3) {
            if ($this->interval == 1) {
                // If this is the first review, set the interval to 6 days
                $this->interval = 6;
            } else {
                // Otherwise, multiply the current interval by the ease factor
                $this->interval = round($this->interval * $this->easyFactor);
            }
        } else {
            // If the quality is below 3, reset the interval to 1 day
            $this->interval = 1;
        }

        // Return the next review date by adding the interval to the last review date
        return $this->lastReviewDate->addDays($this->interval);
    }

    // Private method to update the ease factor based on the quality of the last review
    private function updateEasyFactor()
    {
        // Calculate the new ease factor using the SM2 formula
        $newEasyFactor = $this->easyFactor + (0.1 - (5 - $this->quality) * (0.08 + (5 - $this->quality) * 0.02));
        
        // Ensure the ease factor does not drop below 1.3
        return max(1.3, $newEasyFactor);
    }
}
?>
