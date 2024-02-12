<?php include('./config/db_connect.php') ?>
<?php
$person = array("name" => "John", "age" => 25, "city" => "New York");
$tasks = [
    ["task_id" => 1, "task_name" => "okic"],
    ["task_id" => 2, "task_name" => "okic"],
    ["task_id" => 3, "task_name" => "pc"],
    ["task_id" => 4, "task_name" => "pantry"],
    ["task_id" => 5, "task_name" => "backup"],
    ["task_id" => 6, "task_name" => "grill"],
    ["task_id" => 7, "task_name" => "fry soda"],
    ["task_id" => 8, "task_name" => "sman"],
    ["task_id" => 9, "task_name" => "pos 1"],
    ["task_id" => 10, "task_name" => "pos 2"],
    ["task_id" => 11, "task_name" => "assemm 1"],
    ["task_id" => 12, "task_name" => "assemm 2"],
    ["task_id" => 13, "task_name" => "(agree, Fp, Gf)"],
    ["task_id" => 14, "task_name" => "OAC"],
    ["task_id" => 15, "task_name" => "opening Dining"],
    ["task_id" => 16, "task_name" => "mid dining"],
    ["task_id" => 17, "task_name" => "closing dinning"],
];

?>

<div class="row gap-3 w-full mx-auto  ">
    <section class="card h-full p-2  col-8">
        <div class="card-header">
            <span><b>Scheduling for Aug 3 2023</b></span>
            <!-- <button class="btn btn-primary btn-sm btn-block col-md-3 float-right" type="button" id="new_attendance_btn"><span class="fa fa-plus"></span> Add Attendance</button> -->
        </div>
        <div style="height: 75vh;" class="card-body overflow-auto">
            <table id="table" class="  table table-bordered table-striped">
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
                    foreach ($tasks as $task) {
                    ?>
                        <tr>
                            <td id="<?php echo $task['task_id'] ?>" class="fs-6 fw-semibold"><?php echo $task["task_name"]; ?></td>
                            <td style="max-height: 5rem; min-height: 2.5rem;" class="canvas d-flex flex-wrap gap-2 ">
                                <!-- <div style="width: max-content; font-size: .8rem; " class="bg-light fw-medium py-1 px-3   rounded ">
                                    John Doe
                                </div> -->

                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="col bg-light  p-2 rounded">
        <header class="bg-info py-2 px-2 d-flex gap-2 align-items-center">
            <i class="fa fa-regular fa-user fs-4"></i>
            <p class="m-0 fs-4 fw-medium">Employee</p>
        </header>

        <div style="background-color: #F1EFEF; max-height: 70vh;" class=" overflow-y-auto d-flex flex-column gap-1 overflow-y-auto">
            <?php
            for ($i = 0; $i < 20; $i++) {
            ?>
                <div style="background-color: #C7C8CC; cursor:grab; " class="employee noselect p-2 rounded  d-flex gap-2 align-items-center">
                    <i class="fa fa-regular fa-user"></i>
                    <p class="m-0 fw-medium">John Doe <?php echo $i; ?></p>
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
</style>

<!-- jqeury -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        let isDragInPlace = false; // drag variable for checking drag is in right place

        // drag element function
        $(".employee").draggable({
            helper: function() {
                const helperElement = $(this).clone().appendTo("body");
                helperElement.css({
                    "zIndex": 5,
                    "background-color": "#B4D4FF"
                });

                // Show the helper element
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
            drop: function(event, ui) {
                // Create a new element with the desired content
                const newElement = $("<div>").addClass("bg-lights fw-medium py-1 px-3 rounded shadow border").css({
                    "width": "max-content",
                    "font-size": ".8rem ",
                    "background-color": "#EEF5FF"
                }).text(ui.helper.find("p").text());

                // Append the new element to the dropped area
                $(this).append(newElement);

                // Remove the cloned helper elements after dropping
                $(".ui-draggable-helper").remove();
            }
        }).sortable({
            placeholder: "sort-placer",
            cursor: "move",
            connectWith: ".canvas"

        });
    });
</script>