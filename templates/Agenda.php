<div class="wrap">
<h1>Agenda</h1><br>
<?php settings_errors();?>

<div id="calendar"></div>

</div>

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