<?php include('./config/db_connect.php'); ?>

<!-- Container for the calendar -->
<div class="container">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <!-- Calendar placeholder -->
          <div id="calendar"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for event details -->
  <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Placeholder for event details -->
          <div id="eventDetails"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Styling -->
<style>
  .card {
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
  }

  #calendar {
    background-color: #ffffff; 
    border-radius: 8px;

  }
</style>

<!-- JS for jQuery, Bootstrap, and full calendar -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<!-- Your custom JavaScript file -->
<script src="assets/js/calendar.js"></script>
