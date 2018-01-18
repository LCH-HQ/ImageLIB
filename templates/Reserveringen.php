<h1>Reserveringen</h1>

<div id="calendar"></div>

<?php

    use Inc\Api\Callbacks;

    echo "<script>
    	$(document).ready(function() {

        // page is now ready, initialize the calendar...

        $('#calendar').fullCalendar({
            firstDay: 1,
            weekends: false,
            timezone: 'UTC',
            defaultView: 'listWeek',
            businessHours: true,
            locale: 'nl',
            nowIndicator: true,
            aspectRatio: 3
        });
        ";

        $this->loopDoorReserveringen();

        echo '
    });
    </script>';
?>