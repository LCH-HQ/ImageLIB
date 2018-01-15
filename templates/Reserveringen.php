<h1>Reserveringen</h1>

<div id="calendar"></div>

<script>
	$(document).ready(function() {

    // page is now ready, initialize the calendar...

    $('#calendar').fullCalendar({
        firstDay: 1,
        weekends: false,
        timezone: 'UTC',
        defaultView: 'agendaWeek',
        businessHours: true,
        locale: 'nl',
        nowIndicator: true,
        aspectRatio: 4
    });

    $('.agendaItemVerzenden').on('click', function(e){
	    // We don't want this to act as a link so cancel the link action
	    e.preventDefault();

	    // Find form and submit it
	    verzendAgendaItem();
	});

    function verzendAgendaItem() {
    $('#calendar').fullCalendar('renderEvent',
    {
        title: $('.agendaItemNaam').val(),
        start: new Date($('.agendaItemStart').val()),
        end: new Date($('.agendaItemEinde').val()),
        editable: true
    },
    true
    );
};
});
</script>

<form id="agendaItemInvoeren">
	<input type="text" class="agendaItemNaam">
	<input type="datetime-local" class="agendaItemStart">
	<input type="datetime-local" class="agendaItemEinde">
	<input type="submit" class="agendaItemVerzenden">
</form>

<div class="wrap"> 
<?php
    use Inc\Api\Callbacks;

    $this->haalReserveringDataOp();
?>
</div>