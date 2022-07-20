@extends('layout', [
    'namePage' => 'Calendar Menu',
    'activePage' => 'dashboard',
    'nameDesc' => ''
])

@section('content')
<div class="content">
	<div class="content-header p-0">
      <div class="container">
         <div class="row pt-1">
            <div class="col-md-12 p-0 m-0">
               <div class="card card-lightblue">
                  <div class="card-header text-center">
                     <span id="branch-name" class="font-weight-bold d-block font-responsive">{{ $branch }}</span>
                  </div>
						<div class="card-body p-2">
							@if ($due_alert)
							<div class="callout callout-warning font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2"><i class="fas fa-exclamation-circle"></i> Sales report submission is due tomorrow</div>
							@endif
							<div class="callout callout-info font-responsive text-center pr-1 pl-1 pb-3 pt-3 m-2"><i class="fas fa-info-circle"></i> Select a date for report entry</div>
							<div class="d-block font-responsive text-center	mt-2" id="report-deadline-display"></div>
							<div id="calendar"></div>
							<div class="d-flex flex-row mt-3 justify-content-start">
								<div class="p-1 col-4">
									<i class="fas fa-square text-success" style="font-size: 13pt;"></i> 
									<span style="font-size: 9pt;">Submitted</span>
								</div>
								<div class="p-1 col-4">
									<i class="fas fa-square text-danger" style="font-size: 13pt;"></i>
									<span style="font-size: 9pt;">Late</span>
								</div>
							</div>
						</div>
               		</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-sm">
	<div class="modal-dialog modal-sm modal-dialog-cen1tered">
	  	<div class="modal-content bg-danger">
			<div class="modal-body text-center">
				<p id="modal-sm-message"></p>
				<a href="/beginning_inventory" class="btn btn-primary btn-sm">Create Beginning Inventory</a>
			</div>
	 	</div>
	</div>
</div>
<style>
	.fc .fc-daygrid-day.fc-day-future {
		background-color: rgba(23, 32, 42, 0.15);
		background-color: var(--fc-today-bg-color, rgba(23, 32, 42, 0.15));
		cursor: disabled;
	}
	.fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
		font-weight: bold;
		font-size: 15pt;
	}
	.fc .fc-bg-event {
		opacity: 0.8;
	}
	.fc-event-time {
		display: none !important;
	}
	.fc-event-title {
		font-size: 7pt;
	}
	.fc-daygrid-event-dot {
		display: none;
	}
	.fc-header-toolbar {
		padding: 5px !important;
		margin-bottom: 8px !important;
	}
	.fc-prev-button {
		padding: 3px 12px 3px 10px !important;
	}
	.fc-next-button {
		padding: 3px 10px 3px 12px !important;
	}
	.fc-dayGridMonth-button {
		padding: 3px 8px !important;
		font-size: 10pt;
	}
	.fc-timeGridWeek-button {
		font-size: 10pt;
		padding: 3px 8px !important;
	}
</style>
@endsection

@section('script')
<!-- fullCalendar 2.2.5 -->
<script src="{{ asset('/updated/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/updated/plugins/fullcalendar/main.js') }}"></script>
<!-- jQuery UI -->
<script src="{{ asset('/updated/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Page specific script -->
<script>
	$(function () {  
		/* initialize the calendar -----------------------------------------------------------------*/
		var Calendar = FullCalendar.Calendar;
		var calendarEl = document.getElementById('calendar');

		var calendar = new Calendar(calendarEl, {
			timeZone: 'local',
			height: 650,
			headerToolbar: {
				left  : '',
				center: 'title',
				right : 'prev dayGridMonth,timeGridWeek next'
			},
			themeSystem: 'bootstrap',
			//Random default events
			eventSources: [
				{
					url: '/calendar_data/' + $('#branch-name').text(),
				}
			],   
			dateClick: function(info) {
				if (new Date(info.dateStr) > moment()) {
					showNotification("info", 'Cannot select this date.', "fa fa-info");
					return false;
				} 

				$.ajax({
					type: "GET",
					url: "/validate_beginning_inventory",
					data: {branch_warehouse: $('#branch-name').text(), date: (info.dateStr)},
					success: function (response) {
						if (!response.status) {
							$('#modal-sm-message').html(response.message);
							$('#modal-sm').modal('show');
							return false;
						} else {
							window.location.href='/view_product_sold_form/' + $('#branch-name').text() + '/' + info.dateStr;
						}
					}
				});
			},
		});
	
		calendar.render();

		$(document).on('click', '.fc-prev-button', function(e){
			var prevDate = calendar.getDate();
			var prevMonth = prevDate.getMonth() + 1;
			var prevYear = prevDate.getFullYear();
			displayDeadline(prevMonth, prevYear);
		});

		$(document).on('click', '.fc-next-button', function(e){
			var nextDate = calendar.getDate();
			var nextMonth = nextDate.getMonth() + 1;
			var nextYear = nextDate.getFullYear();
			displayDeadline(nextMonth, nextYear);
		});

		var currentCalendarDate = calendar.getDate();
		var currentCalendarMonth = currentCalendarDate.getMonth() + 1;
		var currentCalendarYear = currentCalendarDate.getFullYear();
		displayDeadline(currentCalendarMonth, currentCalendarYear);
			
		function displayDeadline(month, year) {
			$.ajax({
				type: "GET",
				url: "/sales_report_deadline",
				data: {month, year},
				success: function (response) {
					$('#report-deadline-display').text(response);
				}
			});
		}

		function showNotification(color, message, icon){
			$.notify({
				icon: icon,
				message: message
			},{
				type: color,
				timer: 500,
				z_index: 1060,
				placement: {
					from: 'top',
					align: 'center'
				}
			});
		}
	});
</script>
@endsection
