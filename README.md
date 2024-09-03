
# SM2 Spaced Repetition Algorithm Service

This repository contains a simple implementation of the SM2 spaced repetition algorithm in PHP. The SM2 algorithm is used to calculate optimal review intervals for retaining information over the long term. This implementation is designed to be used within a Laravel application but can be adapted for other PHP frameworks or standalone projects.

## Features

- Calculates the next review date based on the user's performance.
- Updates the ease factor dynamically, ensuring optimal intervals for review.
- Simple to integrate into any PHP-based project.

## Installation

To use this class in your Laravel project:

1. Clone the repository or download the `Sm2.php` file.
2. Place the `Sm2.php` file in the `app/Services` directory of your Laravel project.

## Usage

Here’s an example of how to use the `Sm2` class in a Laravel controller or service:

```php
use App\Services\Sm2;
use Carbon\Carbon;

$lastReviewDate = Carbon::now()->subDays(5); // Example: last review was 5 days ago
$easyFactor = 2.5; // Initial ease factor
$interval = 1; // Initial interval (in days)
$quality = 4; // Example user rating (scale of 0 to 5)

$sm2 = new Sm2($lastReviewDate, $easyFactor, $interval, $quality);

$nextReviewDate = $sm2->nextReviewDate();

echo "Next review date: " . $nextReviewDate->toDateString();
