<?php
session_start();
//error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    if (isset($_POST['create'])) {
        $id = $_POST['BranchID'];
        $branchName = $_POST['BranchName'];
        $branchCity = $_POST['BranchCity'];
        $branchDistrict = $_POST['District'];
        $userName = $_POST['Username'];
        $Password = md5($_POST['Password']);
        $branchType = intval($_POST['BranchType']);
        $status = intval($_POST['Status']);

        $sql = "INSERT INTO tblcategory(id,CategoryName,BranchCity,BranchDistrict,Username,Password,Type,Status) VALUES(:Id,:BranchName,:BranchCity,:BranchDistrict,:Username,:Password,:Type,:Status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':Id', $id, PDO::PARAM_STR);
        $query->bindParam(':BranchName', $branchName, PDO::PARAM_STR);
        $query->bindParam(':BranchCity', $branchCity, PDO::PARAM_STR);
        $query->bindParam(':BranchDistrict', $branchDistrict, PDO::PARAM_STR);
        $query->bindParam(':Username', $userName, PDO::PARAM_STR);
        $query->bindParam(':Password', $Password, PDO::PARAM_STR);
        $query->bindParam(':Type', $branchType, PDO::PARAM_INT);
        $query->bindParam(':Status', $status, PDO::PARAM_INT);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if (isset($lastInsertId)) {
            $_SESSION['msg'] = "Branch Listed successfully";
            header('location:manage-branches.php');
        } else {
            $_SESSION['error'] = $lastInsertId."Something went wrong. Please try again";
            header('location:manage-branches.php');
        }
    }
    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
        <meta name="description" content=""/>
        <meta name="author" content=""/>
        <title>SLTJ Ranking Management System | Add Branch Details</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet"/>
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet"/>
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet"/>
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>

    <script>
            var password = document.getElementById("password")
  , confirm_password = document.getElementById("confirm_password");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
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
                    Branch Info
                </div>
                <div class="panel-body">
                    <form role="form" method="post">
                        <div class="form-group">
                            <label>Branch ID</label>
                            <input class="form-control" type="text" name="BranchID" autocomplete="off" title="Branch Unique ID" Required/>

                            <label>Branch Name</label>
                            <input class="form-control" type="text" name="BranchName" autocomplete="off" title="Branch Name" Required/>

                            <label>Branch City</label>
                            <input class="form-control" type="text" name="BranchCity" title="Branch City or Village Situated at" Required/>

                            <p>
                                <label>Select District</label>
                                <select class="form-control" name="District" title="Please Select a District">
                                    <option value="Ampara">Ampara</option>
                                    <option value="Anuradhapura">Anuradhapura</option>
                                    <option value="Badulla">Badulla</option>
                                    <option value="Batticaloa">Batticaloa</option>
                                    <option value="Colombo">Colombo</option>
                                    <option value="Galle">Galle</option>
                                    <option value="Gampaha">Gampaha</option>
                                    <option value="Hambantota">Hambantota</option>
                                    <option value="Jaffna">Jaffna</option>
                                    <option value="Kalutara">Kalutara</option>
                                    <option value="Kandy">Kandy</option>
                                    <option value="Kegalle">Kegalle</option>
                                    <option value="Kilinochchi">Kilinochchi</option>
                                    <option value="Kurunegala">Kurunegala</option>
                                    <option value="Mannar">Mannar</option>
                                    <option value="Matale">Matale</option>
                                    <option value="Matara">Matara</option>
                                    <option value="Moneragala">Moneragala</option>
                                    <option value="Mullaitivu">Mullaitivu</option>
                                    <option value="Nuwareliya">Nuwareliya</option>
                                    <option value="Polonnaruwa">Polonnaruwa</option>
                                    <option value="Puttalam">Puttalam</option>
                                    <option value="Ratnapura">Ratnapura</option>
                                    <option value="Trincomalee">Trincomalee</option>
                                    <option value="Vavuniya">Vavuniya</option>
                                </select>
                            </p>

                            <label>Username</label>
                            <input class="form-control" type="text" name="Username" title="Username to login by branch" Required/>

                            <label>Password</label>
                            <input class="form-control" type="password" name="Password" id="password" title="Password to login by branch" onkeyup='validatePassword();' Required/>

                            <label>Confirm Password</label>
                            <input class="form-control" type="password" name="Confirm-Password" title="Confirm password" id="confirm_password" onkeyup='validatePassword();' Required/>

                            <label>Branch Type</label>
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="BranchType" value="2" title="Select this if it is District Head">District Head
                                    </label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="BranchType" value="3" checked="checked" title="Select this if it is regular branch">Regular
                                            Branch
                                        </label>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="Status" value="1" checked="checked">Active
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="Status" value="0">Inactive
                                    </label>
                                </div>

                            </div>
                            <button type="submit" name="create" class="btn btn-info">Create</button>

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
<?php } ?>
