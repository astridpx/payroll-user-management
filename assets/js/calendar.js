$(document).ready(function () {
  $("#calendar").fullCalendar({
    defaultView: "month",
    events: [
      {
        title: "Divyesh Birthday",
        start: "2024-02-21",
      },
      {
        title: "Roshni Birthday",
        start: "2024-02-21",
      },
      {
        title: "Shinerweb website renewal",
        start: "2024-02-21",
      },
    ],
    eventClick: function (calEvent, jsEvent, view) {
      // Show modal when clicking on an event
      $("#eventModal").modal("show");
    },
    dayClick: function (date, jsEvent, view) {
      // Redirect when clicking on a day without events
      var clickedDate = date.format("YYYY-MM-DD");
      var redirectURL =
        "http://localhost/Payroll/index.php?page=scheduling-employee&date=" +
        clickedDate;
      window.location.href = redirectURL;
    },
  });
});
