<?php
session_start();
error_reporting(0);
$error;
$eventID;
$evenName;
$Points;
$TotalPoints;
$path1;
$path2;
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {

    if (isset($_POST['create']) && isset($_FILES['image1'])) {
        $eventID = $_POST['event'];
        $abc = "SELECT * FROM  tblauthors Where id=:eventID";
        $que = $dbh->prepare($abc);
        $que -> bindParam(':eventID',$eventID, PDO::PARAM_STR);
        $que->execute();
        if($que)
        {
            try
            {
                $res = $que->fetchAll(PDO::FETCH_OBJ);
                if ($que->rowCount() > 0) 
                    {
                        foreach ($res as $re)
                        {
                            $eventName = $re->AuthorName;
                            $Points = $re->Points;
                        }
                    }
            }
            catch(exception $e)
            {
            echo $e;
            }
        }
        else
        {
            echo "<script>alert('Error')</script>";
        }
        $Count = $_POST['count'];
        $Type= $_SESSION['Type'];
        $BranchName = $_SESSION['Branch'];
        $District = $_SESSION['District'];
        $Date = $_POST['eventDate'];
        $Description = $_POST['description'];
        $DoneBy = $_POST['doneby'];
        $Crowd = $_POST['crowd'];
        
        $error = array();
        $Image_1 = $_FILES['image1']['name'];
        $Temp_Dir_1 = $_FILES['image1']['tmp_name'];
        $Image_Size_1 = $_FILES['image1']['size'];

        $Image_2 = $_FILES['image2']['name'];
        $Temp_Dir_2 = $_FILES['image2']['tmp_name'];
        $Image_Size_2 = $_FILES['file']['size'];
        
        $U_Dir = "uploads/";
        $Img_Ext_1 = explode(".",$Image_1);
        $Act_Ext_1 = strtolower(end($Img_Ext_1));

        $Img_Ext_2 = explode(".",$Image_2);
        $Act_Ext_2 = strtolower(end($Img_Ext_2));
        $Valid_Extensions = array('jpeg','jpg','png');

        $Pic_Profile_1 = rand(1000,1000000).".".$Img_Ext_1;
        $Pic_Profile_2 = rand(1000,1000000).".".$Img_Ext_2;
        $FD1 = 'admin/uploads/'.$Pic_Profile_1;
        $FD2 = 'admin/uploads/'.$Pic_Profile_2;
        move_uploaded_file($Temp_Dir_1,$FD1);
        move_uploaded_file($Temp_Dir_2,$FD2);
        $path1 = $U_Dir.$Pic_Profile_1;
        $path2 = $U_Dir.$Pic_Profile_2;
        $Status  = 0;
        $TotalPoints = $Points * $Count;
        try
        {
        try
        {
        $sql = "INSERT INTO tblstudents(EventID,Event_Name,Branch_Name,District,Done_By,Status,Count,Branch_Type,EventDate,Points,Crowd,image1,image2) VALUES(:ei,:en,:bn,:dn,:db,:st,:co,:bt,:ed,:po,:cr,:im1,:im2)";
        $query = $dbh->prepare($sql);
       // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query->bindParam(':ei', $eventID, PDO::PARAM_INT);
        $query->bindParam(':en', $eventName, PDO::PARAM_STR);
        $query->bindParam(':bn', $BranchName, PDO::PARAM_STR);
        $query->bindParam(':dn', $BranchName, PDO::PARAM_STR);
        $query->bindParam(':db', $DoneBy, PDO::PARAM_STR);
        $query->bindParam(':st', $Status , PDO::PARAM_INT);
        $query->bindParam(':co', $Count, PDO::PARAM_INT);
        $query->bindParam(':bt', $Type, PDO::PARAM_INT);
        $query->bindParam(':ed', $Date, PDO::PARAM_STR);
        $query->bindParam(':po', $TotalPoints, PDO::PARAM_STR);
        $query->bindParam(':cr', $Crowd, PDO::PARAM_STR);
        $query->bindParam(':im1', $path1, PDO::PARAM_STR);
        $query->bindParam(':im2', $path2, PDO::PARAM_STR);
        $query->execute(); 
        $_SESSION['msg'] = "Event Listed successfully";
        header('location:manage-events.php');    
        $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId)
            {              
                $_SESSION['msg'] = "Event Listed successfully";
                header('location:manage-events.php');
            }else
            {
                echo "<script>alert('Oops! Please try again')</script>";
            }      
        }
        catch(PDOException $db) 
        {
            $_SESSION['error'] = $db." Something went wrong. Please try again";
            header('location:manage-events.php');
        }
    }
    catch(PDOException $ex)
        {
            $error = $ex;
            throw $ex; 
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
    <title>SLTJ Ranking Management System | Add Event Details</title>
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
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Event Info
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" enctype="multipart/form-data">
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
                                        <?php } }?>
                                    </select>

                                    <label>Event Count<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" onkeypress='validate(event)' name="count"
                                        autocomplete="off" required />

                                    <label>Event Date<span style="color:red;">*</span></label>
                                    <input class="form-control" type="date" name="eventDate" autocomplete="off"
                                        required />

                                    <label>Description</label>
                                    <input class="form-control" type="text" name="description" placeholder="Optional"
                                        autocomplete="off" />

                                    <label>Done by<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="doneby" required="required"
                                        autocomplete="off" />

                                    <label>Estimated Crowd<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="crowd" onkeypress='validate(event)' autocomplete="off"
                                        required="required" />
                                
                                    <label ><span style="color:red;">Event Images 1</span></label>
                                    <input class="form-control" type="file" name='image1' required="required"/>
                                    
                                    <label ><span style="color:red;">Event Images 2</span></label>
                                    <input class="form-control" type="file" name='image2' required="required"/>
                                    <div class="form-group"></div>
                                    
                                    <button type="submit" name="create" class="btn btn-info">Add Event</button>
                            </form>
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
        <!-- CORE JQUERY  -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="assets/js/bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="assets/js/custom.js"></script>
</body>

</html>
<?php }?>