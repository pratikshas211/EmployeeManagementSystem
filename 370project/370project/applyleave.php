<?php 
    $id = (isset($_GET['id']) ? $_GET['id'] : '');
    require_once ('process/dbh.php');
    $sql = "SELECT * FROM `employee` WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $employee = mysqli_fetch_array($result);
    $empName = ($employee['firstName']);
?>

<html>
<head>
    <title>Apply Leave | Employee Panel | TeamSync</title>
    <link rel="stylesheet" type="text/css" href="styleapply.css">
    


<script>
    function validateForm() {
        var reason = document.forms["leaveForm"]["reason"].value;
        var startDate = new Date(document.forms["leaveForm"]["start"].value);
        var endDate = new Date(document.forms["leaveForm"]["end"].value);
        var defaultDate = new Date();
        var defaultMonth = defaultDate.getMonth();
        var defaultYear = defaultDate.getFullYear();

        // Check if reason is empty
        if (reason === "") {
            alert("Please enter a reason for leave");
            return false;
        }

        // Check if start date is after the default date
        if (startDate <= defaultDate) {
            alert("Please select a start date after today's date");
            return false;
        }

        // Check if start date and end date are within the current month
        if (startDate.getMonth() !== defaultMonth || startDate.getFullYear() !== defaultYear ||
            endDate.getMonth() !== defaultMonth || endDate.getFullYear() !== defaultYear) {
            alert("Please select start and end dates within the current month.");
            return false;
        }

        // Check if end date is exactly one day after the start date
        var oneDayMillis = 24 * 60 * 60 * 1000;
        if ((endDate.getTime() - startDate.getTime()) !== oneDayMillis) {
            alert("Leave duration should be exactly two days.");
            return false;
        }

        return true; // Form validation passed
    }
</script>
</head>
<body bgcolor="#F0FFFF">
    
    <header>
        <nav>
            <h1>TeamSync</h1>
            <ul id="navli">
                <li><a class="homeblack" href="eloginwel.php?id=<?php echo $id?>"">HOME</a></li>
                <li><a class="homeblack" href="myprofile.php?id=<?php echo $id?>"">My Profile</a></li>
                <li><a class="homeblack" href="empproject.php?id=<?php echo $id?>"">My Projects</a></li>
                <li><a class="homered" href="applyleave.php?id=<?php echo $id?>"">Apply Leave</a></li>
                <li><a class="homeblack" href="elogin.html">Log Out</a></li>
            </ul>
        </nav>
    </header>
     
    <div class="divider"></div>
    <div class="page-wrapper bg-blue p-t-100 p-b-100 font-robo">
        <div class="wrapper wrapper--w680">
            <div class="card card-1">
                <div class="card-heading"></div>
                <div class="card-body">
                    <h2 class="title">Apply Leave Form</h2>
                    <form name="leaveForm" action="process/applyleaveprocess.php?id=<?php echo $id?>" method="POST" onsubmit="return validateForm()">
                        <div class="input-group">
                            <input class="input--style-1" type="text" placeholder="Reason" name="reason">
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <p>Start Date</p>
                                <div class="input-group">
                                    <input class="input--style-1" type="date" placeholder="start" name="start">
                                </div>
                            </div>
                            <div class="col-2">
                                <p>End Date</p>
                                <div class="input-group">
                                    <input class="input--style-1" type="date" placeholder="end" name="end">
                                </div>
                            </div>
                        </div>
                        <div class="p-t-20">
                            <button class="btn btn--radius btn--green" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table>
        <tr>
            <th align="center">Emp. ID</th>
            <th align="center">Name</th>
            <th align="center">Start Date</th>
            <th align="center">End Date</th>
            <th align="center">Total Days</th>
            <th align="center">Reason</th>
            <th align="center">Status</th>
        </tr>

        <?php
            $sql = "SELECT employee.id, employee.firstName, employee.lastName, employee_leave.start, employee_leave.end, employee_leave.reason, employee_leave.status FROM employee, employee_leave WHERE employee.id = $id AND employee_leave.id = $id ORDER BY employee_leave.token";
            $result = mysqli_query($conn, $sql);
            while ($employee = mysqli_fetch_assoc($result)) {
                $date1 = new DateTime($employee['start']);
                $date2 = new DateTime($employee['end']);
                $interval = $date1->diff($date2);
                $interval = $date1->diff($date2);

                echo "<tr>";
                echo "<td>".$employee['id']."</td>";
                echo "<td>".$employee['firstName']." ".$employee['lastName']."</td>";
                echo "<td>".$employee['start']."</td>";
                echo "<td>".$employee['end']."</td>";
                echo "<td>".$interval->days."</td>";
                echo "<td>".$employee['reason']."</td>";
                echo "<td>".$employee['status']."</td>";
            }
        ?>
    </table>
</body>
</html>