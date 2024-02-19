<?php
include('./config/db_connect.php');

// FETCH DEPARTMENT
$dept = $conn->query("SELECT * FROM department");
$deptResult = $dept->fetch_all(MYSQLI_ASSOC);
$date = $_GET['date']; // params date


// FETCH EMPLOYEE THAT HASN'T IN SCHED DATE
$emp = $conn->query("SELECT e.*
FROM employee e
LEFT JOIN schedule s ON e.id = s.employee_ID AND s.date = '$date'
WHERE s.employee_ID IS NULL OR s.date IS NULL");
$empResult = $emp->fetch_all(MYSQLI_ASSOC);


// FETCH SCHED WITH DEPARTMENT AND EMPLOYEE NAME
$sched = $conn->query("SELECT  s.*, d.name AS department, d.id AS departmentID, s.ID AS sched_ID, CONCAT(e.firstname,' ',e.lastname) AS fullname FROM department d lEFT JOIN schedule s ON d.id = s.department_id AND s.date = '$date' LEFT JOIN employee e ON s.employee_id = e.id  ORDER BY departmentID ASC;");
$deptArray = $sched->fetch_all(MYSQLI_ASSOC);


// CREATE NEW STRUCTURE OF ARRAY AND SORT THE DATA      
$depSched = [];

foreach ($deptArray  as $newEntry) {
    $found = false;

    foreach ($depSched as &$data) {
        if ($data["dep_id"] === $newEntry["departmentID"]) {
            // if (!isset($data["employee"]) || !is_array($data["employee"])) {
            //     $data["employee"] = [];
            // }

            // id dep_id found insert this on the employee
            $data["employee"][] = [
                "fullname" => $newEntry["fullname"],
                "emp_id" => $newEntry['employee_ID']
            ];
            $data["employee"] = array_values($data["employee"]); // Reindex the array

            $found = true;
            break;
        }
    }

    if (!$found) {
        // If dep_id not found, add a new entry
        $depSched[] = [
            "dep_id" => $newEntry["departmentID"],
            "dep_name" => $newEntry["department"],
            "employee" => [
                [
                    "fullname" => $newEntry["fullname"],
                    "emp_id" => $newEntry['employee_ID']
                ]
            ]
        ];
    }
}


// echo  json_encode($depSched, JSON_PRETTY_PRINT);
// echo  json_encode($deptArray, JSON_PRETTY_PRINT);


?>

<div class="row gap-3 w-full mx-auto  ">
    <section class="card h-full p-2  col-8">
        <div class="card-header">
            <span><b>Scheduling for <?php echo isset($_GET['date']) ? (new DateTime(htmlspecialchars($_GET['date'])))->format("F j, Y") : "" ?></b></span>

            <div class="d-flex align-items-center float-right ">
                <button style="background-color: #315994;" class="fw-medium btn btn-sm text-light d-flex mr-1 " type="button" id="normalBtn">
                    <i class="bi bi-cursor mr-1"></i> Cursor</button>
                <button style="background-color: #D61A42;" class="fw-medium btn btn-sm text-light d-flex  " type="button" id="disposeBtn">
                    <i class="bi bi-trash3 mr-1"></i> Dispose</button>
            </div>
        </div>
        <div style="height: 75vh;" class="card-body overflow-auto">
            <table id="table" class="table table-bordered ">
                <colgroup>
                    <col width="20%">
                    <col width="70%">
                </colgroup>
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Assigned</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($depSched  as $sched) {
                    ?>
                        <tr>
                            <td class="fs-6 fw-semibold "><?php echo $sched["dep_name"]; ?>
                            </td>
                            <td id="<?php echo $sched["dep_id"] ?>" style="max-height: 5rem; min-height: 4rem;" class="canvas d-flex flex-wrap gap-2 ">
                                <?php
                                foreach ($sched["employee"]  as $emp) {
                                    if ($emp["emp_id"] !== null) {
                                ?>
                                        <div id="<?php echo $emp["emp_id"] ?>" style="height: max-content; width: max-content; font-size: .8rem;background-color: #EEF5FF" class="employee_sched bg-lights fw-medium py-1 px-3 rounded shadow border">
                                            <?php echo $emp['fullname'] ?>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <section style="background-color: rgb(241 245 249); " class="col  p-2 rounded">
        <header style="background-color: #FEBB0C; color: #2B2A0B;" class=" rounded mb-1 py-2 px-2 d-flex gap-2 align-items-center">
            <i class="fa fa-regular fa-user fs-4"></i>
            <p class="m-0 fs-4 fw-bold">Employee Lists</p>
        </header>

        <div style="max-height: 70vh;" class=" overflow-y-auto d-flex flex-column gap-1 overflow-y-auto">
            <?php
            foreach ($empResult as $emp) {
            ?>
                <div id="<?php echo $emp["id"] ?>" style="background-color: #DEDEDE;color: #334256; cursor:grab; font-family: 'Poppins', sans-serif; border: 1px solid #315994;" class="employee noselect p-2 rounded  d-flex gap-2 align-items-center">
                    <!-- <i class="fa fa-regular fa-user"></i> -->
                    <img src="./assets/img/profile.jpg" alt="profile" height="35" width="35" style="aspect-ratio: 1/1;" class="rounded-circle">
                    <p class="m-0 fs-6 text-capitalize fw-medium"><?php echo "{$emp['firstname']} {$emp['lastname']}" ?></p>
                </div>
            <?php
            }
            ?>
        </div>
    </section>
</div>

<style>
    .noselect {
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
    }

    .spatula-cursor {
        cursor: url('./assets/img/trash.svg'), auto !important;
        /* cursor: not-allowed !important; */
    }
</style>


<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script> -->

<script type="text/javascript">
    let isDragInPlace = false; // drag variable for checking drag is in right place
    let isAllowRemoveEmployee = false;

    // GET URL PARAMS DATE
    const urlParams = new URLSearchParams(window.location.search);
    const dateParam = urlParams.get('date');


    // BTN FUNCTION
    $("#disposeBtn").on("click", () => {
        isAllowRemoveEmployee = true;

        $(".canvas").addClass("spatula-cursor")
    })
    $("#normalBtn").on("click", () => {
        isAllowRemoveEmployee = false;

        $(".canvas").removeClass("spatula-cursor")
    })



    const InitialDropReq = async (data) => {
        await $.ajax({
            url: './services/ajax.php?action=drag_employee',
            method: 'POST',
            data: data,
            error: err => {
                console.log(err)
            },
            success: (resp) => {
                if (resp) {
                    console.log(resp)
                }
            }
        })
    }

    const MoveDropReq = async (data) => {
        await $.ajax({
            url: './services/ajax.php?action=move_employee',
            method: 'POST',
            data: data,
            error: err => {
                console.log(err)
            },
            success: (resp) => {
                if (resp) {
                    console.log(resp)
                }
            }
        })
    }

    const RemoveEmployeeReq = async (data) => {
        await $.ajax({
            url: './services/ajax.php?action=remove_employee',
            method: 'POST',
            data: data,
            error: err => {
                console.log(err)
            },
            success: (resp) => {
                if (resp) {
                    // alert(resp)
                    location.reload();
                }
            }
        })
    }

    // REMOVE EMPLOYEE IN SCHED FUNCTION
    $("td.canvas").on("click", ".employee_sched", async function() {
        const tdId = $(this).parent().attr('id');
        const empId = $(this).attr('id');

        let employee_to_remove = {
            department_id: tdId,
            employee_id: empId,
            date: new Date(dateParam).toISOString(),
        };

        if (!isAllowRemoveEmployee) {
            return false;
        }

        return await RemoveEmployeeReq(employee_to_remove)
    })

    // DRAG AND DROP FUNCTION
    $(".employee").draggable({
        helper: function() {
            const helperElement = $(this).clone().appendTo("body");
            helperElement.css({
                "zIndex": 5,
                // "background-color": "rgb(254 249 195)",
                "background-color": "rgb(226 232 240)",
                "color": "#333",
                "cursor": "grabbing",
                "box-shadow": "0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)"
            });

            return helperElement.show();
        },
        cursor: "move",
        containment: "document",
        revert: function(valid) {
            // if drag in the wrong place the drag cancel
            if (!valid) {
                isDragInPlace = false;
                return true
            }
            // if drag in the right place
            isDragInPlace = true;
            return false

        },
        start: function(event, ui) {
            $(this).hide();
        },
        stop: function(event, ui) {
            // Remove the original element when dragging is in the right place
            if (isDragInPlace) {
                return $(this).remove();
            }
            // Show the original element when dragging is in the wrong place
            return $(this).show();
        }
    })


    // Make .canvas droppable
    $(".canvas, .canvas *").droppable({
        accept: ".employee",
        drop: async function(event, ui) {
            const newElement = $("<div>").addClass("employee_sched bg-lights fw-medium py-1 px-3 rounded shadow border").css({
                "width": "max-content",
                "height": "max-content",
                "font-size": ".8rem ",
                "background-color": "#EEF5FF"
            }).text(ui.helper.find("p").text());

            // Set the ID of the new element to the ID of the dropped element
            newElement.attr("id", ui.draggable.attr("id"));

            // Append the new element to the dropped area
            $(this).append(newElement);

            // Remove the cloned helper elements after dropping
            $(".ui-draggable-helper").remove();

            let schedule = {
                employee_id: ui.draggable.attr("id"),
                department_id: $(this).attr("id"),
                date: new Date(dateParam).toISOString(),
            };

            // AJAX FOR INITIAL DROP
            await InitialDropReq(schedule)
        }
    }).sortable({
        placeholder: "sort-placer",
        cursor: "move",
        connectWith: ".canvas",
        update: async function(event, ui) {

            // Get the ID of the sorted element
            const sortedElementID = ui.item.attr("id");

            // Get the ID of the container where the element was sorted
            const sortedContainerID = $(this).attr("id");

            let schedule = {
                employee_id: sortedElementID,
                department_id: sortedContainerID,
                date: new Date(dateParam).toISOString(),
            };

            // AJAX FOR INITIAL DROP
            await MoveDropReq(schedule)

        }



    })
</script>