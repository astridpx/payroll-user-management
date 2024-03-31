$(document).ready(async function () {
  let dates = null;

  await $.ajax({
    url: "./services/ajax.php?action=get_employee_sched",
    method: "GET",
    error: (err) => {
      console.log(err);
      Swal.fire({
        title: "Something went wrong.",
        // text: "John Patrick Lubuguin",
        icon: "error",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "OK",
        customClass: {
          container: "custom-sweetalert-container",
          popup: "custom-sweetalert-popup",
          title: "custom-sweetalert-title",
          text: "custom-sweetalert-text",
          confirmButton: "custom-sweetalert-confirm-button",
        },
      });
    },
    success: (resp) => {
      if (resp) {
        // console.log(resp);
        dates = resp;
      }
    },
  });

  $("#calendar").fullCalendar({
    defaultView: "month",
    // events: [
    //   {
    //     title: "Divyesh Birthday",
    //     start: "2024-02-21",
    //   },
    //   {
    //     title: "Roshni Birthday",
    //     start: "2024-02-21",
    //   },
    //   {
    //     title: "Shinerweb website renewal",
    //     start: "2024-03-31",
    //   },
    // ],
    dayRender: function (date, cell) {
      dates?.map((d) => {
        // Check if the date matches the target date
        if (date.format("YYYY-MM-DD") === d.date) {
          // Apply custom styling to the cell
          cell.css("background-color", "rgb(220 252 231)");
        }
      });
    },
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
