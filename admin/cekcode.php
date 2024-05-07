<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calendar</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
    </style>
</head>

<body>

    <?php
    // Function to get the name of the month
    function getMonthName($month)
    {
        $monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        return $monthNames[$month - 1];
    }

    // Function to generate the calendar
    function generateCalendar($year, $month)
    {
        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        $totalDays = date("t", $firstDay);
        $numDayOfWeek = date("w", $firstDay);

        echo "<h2>" . getMonthName($month) . " " . $year . "</h2>";
        echo "<table>";
        echo "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";

        echo "<tr>";
        // Display blank cells for days before the first day of the month
        for ($i = 0; $i < $numDayOfWeek; $i++) {
            echo "<td>kosong</td>";
        }
        // Display the days of the month
        for ($day = 1; $day <= $totalDays; $day++) {
            echo "<td>$day</td>";
            if (++$numDayOfWeek == 7) {
                echo "</tr><tr>";
                $numDayOfWeek = 0;
            }
        }
        // Fill in remaining blank cells in the last row
        while ($numDayOfWeek > 0 && $numDayOfWeek < 7) {
            echo "<td>kosong</td>";
            $numDayOfWeek++;
        }
        echo "</tr>";
        echo "</table>";
    }

    // Get the current year and month
    $currentYear = date("Y");
    $currentMonth = date("n");

    // Display the calendar for the current month
    generateCalendar($currentYear, $currentMonth);
    ?>

</body>

</html>