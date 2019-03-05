<?php
session_start();
//error_reporting(0);
include('includes/config.php');
$eventID;
$evenName;
$Points;
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} 
else 
{
    if (isset($_POST['add'])) { 
                                $eventID = $_POST['event'];
                                $abc = "SELECT * FROM  tblauthors Where id=:eventID";
                                $que = $dbh->prepare($abc);
                                $que -> bindParam(':eventID',$eventID, PDO::PARAM_STR);
                                $que->execute();
                                $res = $que->fetchAll(PDO::FETCH_OBJ);
                                if ($que->rowCount() > 0) {
                                    foreach ($res as $re)
                                    {
                                        $eventName = $re->AuhtorName;
                                        $Points = $re->Points;
                                    }
                                }
        $Count = $_POST['count'];
        $Status = 0;
        $BranchName = $_SESSION['Branch'];
        $District = $_SESSION['District'];
        $Date = $_POST['eventDate'];
        $Description = $_POST['description'];
        $DoneBy = $_POST['doneby'];
        $Crowd = $_POST['crowd'];
        $sql = "INSERT INTO `tblstudents`(`EventID`,`Event_Name`,`Branch_Name`,`Done_By`,`Status`,`Count`,`EventDate`,`Points`,`Crowd`) VALUES(:ei,:en,:bn,:db,:st,:co,:ed,:po,:cr)";
        $query = $dbh->prepare($sql);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query->bindParam(':ei', $eventID, PDO::PARAM_INT);
        $query->bindParam(':en', $eventName, PDO::PARAM_STR);
        $query->bindParam(':bn', $BranchName, PDO::PARAM_STR);
        $query->bindParam(':db', $DoneBy, PDO::PARAM_STR);
        $query->bindParam(':st', $Status , PDO::PARAM_INT);
        $query->bindParam(':co', $Count, PDO::PARAM_INT);
        $query->bindParam(':ed', $Date, PDO::PARAM_STR);
        $query->bindParam(':po', $TotalPoints, PDO::PARAM_STR);
        $query->bindParam(':cr', $Crowd, PDO::PARAM_STR);           
        $query->execute();     
        $lastInsertId = $dbh->lastInsertId();
        if (isset($lastInsertId)) {
            $_SESSION['msg'] = "Event Listed for Approval Successfully";
            header('location:branch-events.php');
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again";
            header('location:branch-events.php');
        }
    }
    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SLTJ Ranking Management System | Add Events</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <script>
    function validate(evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }
    </script>
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wra
    <div class=" content-wrapper ">
    <div class=" container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Add Event</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3" ">
            <div class=" panel panel-info">
                <div class="panel-heading">
                    Event Info
                </div>
                <div class="panel-body">
                    <form role="form" method="post">

                        <div class="form-group">
                            <label>Event Name<span style="color:red;">*</span></label>
                            <select class="form-control" name="event" required="required">
                                <option value=""> Select Event</option>
                                <?php
                                $sql = "SELECT * FROM  tblauthors";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) { ?>
                                <option value="<?php echo htmlentities($result->id); ?>">
                                    <?php echo htmlentities($result->AuthorName); ?></option>
                                <?php }} ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Event Count<span style="color:red;">*</span></label>
                            <input class="form-control" type="text" onkeypress='validate(event)' name="count"
                                autocomplete="off" required />
                        </div>

                        <div class="form-group">
                            <label>Event Date<span style="color:red;">*</span></label>
                            <input class="form-control" type="date" name="eventDate" autocomplete="off" required />
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <input class="form-control" type="text" name="description" placeholder="Optional"
                                autocomplete="off" />
                        </div>

                        <div class="form-group">
                            <label>Done by<span style="color:red;">*</span></label>
                            <input class="form-control" type="text" name="doneby" required="required"
                                autocomplete="off" />
                        </div>

                        <div class="form-group">
                            <label>Estimated Crowd<span style="color:red;">*</span></label>
                            <input class="form-control" type="text" name="crowd" autocomplete="off"
                                required="required" />
                        </div>
                        
                        <button type="submit" name="add" class="btn btn-info">Add</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Added Events</h4>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <!-- Advanced Tables -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                            Event List
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover"
                                        id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Event ID</th>
                                                <th>Event Name</th>
                                                <th>Date</th>
                                                <th>Count</th>
                                                <th>Done by</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        //$sid = $_SESSION['stdid'];
                                        $Branch = $_SESSION['Branch'];
                                        $sql = "SELECT * FROM tblstudents WHERE MobileNumber =:branch AND Status =1";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':branch', $Branch, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        if ($query->rowCount() > 0)
                                        {
                                        foreach ($results as $result)
                                        { ?>
                                            <tr class="odd gradeX">
                                                <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                <td class="center"><?php echo htmlentities($result->StudentId); ?></td>
                                                <td class="center"><?php echo htmlentities($result->EmailId); ?></td>
                                                <td class="center"><?php echo htmlentities($result->EventDate); ?></td>
                                                <td class="center"><?php echo htmlentities($result->Count); ?></td>
                                                <td class="center"><?php echo htmlentities($result->Done_By); ?></td>
                                                <?php $cnt = $cnt + 1;
                                            }
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!--End Advanced Tables -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- DATATABLE SCRIPTS  -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>

</html>
<?php } ?>