<?php
session_start();
//error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
        try{
        if (isset($_POST['update'])) {
        $BranchName = $_POST['BranchName'];
        $BranchCity = $_POST['BranchCity'];
        $BranchType = $_POST['BranchType'];
        $status = intval($_POST['status']);
        $catid = $_GET['catid'];
        $sql = "UPDATE tblcategory SET CategoryName=:branchName,BranchCity=:branchCity,Type=:branchType,Status=:status WHERE id=:catid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':branchName', $BranchName, PDO::PARAM_STR);
        $query->bindParam(':branchCity', $BranchCity, PDO::PARAM_STR);
        $query->bindParam(':branchType', $BranchType, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':catid', $catid, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['updatemsg'] = "Branch updated successfully";
        header('location:manage-branches.php');
            }
        }
        catch(PDOException $e)
        {
            $_SESSION['updatemsg'] = $e;
            header('location:manage-branches.php');
        }
    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
        <meta name="description" content=""/>
        <meta name="author" content=""/>
        <title>SLTJ Ranking Management System | Edit Branches</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet"/>
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet"/>
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet"/>
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'/>

    </head>
    <body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wra
    <div class=" content-wrapper
    ">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h5 class="header-line">Editing Branch Details</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"
            ">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Branch Info
                </div>

                <div class="panel-body">
                    <form role="form" method="post">
                        <?php
                        $catid = intval($_GET['catid']);
                        $sql = "SELECT * FROM tblcategory WHERE id=:catid";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':catid', $catid, PDO::PARAM_STR);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        if ($query->rowCount() > 0) {
                            foreach ($results as $result) {
                                ?>
                                <h5 class="header-line">Edit <?php echo htmlentities($result->CategoryName); ?> Branch Details</h5>
                                <div class="form-group">
                                    <label>Branch Name</label>
                                    <input class="form-control" type="text" name="BranchName"
                                           value="<?php echo htmlentities($result->CategoryName); ?>" required/>
                                </div>

                                <div class="form-group">
                                    <label>Branch City</label>
                                    <input class="form-control" type="text" name="BranchCity"
                                           value="<?php echo htmlentities($result->BranchCity); ?>" required/>
                                </div>
                                <div class="form-group">
                                    <label>Branch Type</label>
                                    <?php if ($result->Type == 3) { ?>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="BranchType" value="2"
                                                       title="Select this if it is District Head">District Head
                                            </label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="BranchType" value="3" checked="checked"
                                                           title="Select this if it is regular branch">Regular
                                                    Branch
                                                </label>
                                            </div>
                                        </div>
                                    <?php } else if ($result->Type == 2) { ?>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="BranchType" value="2" checked="checked"
                                                       title="Select this if it is District Head">District Head
                                            </label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="BranchType" value="2"
                                                           title="Select this if it is regular branch">Regular
                                                    Branch
                                                </label>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <?php if ($result->Status == 1) { ?>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" id="status" value="1"
                                                       checked="checked">Active
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" id="status" value="0">Inactive
                                            </label>
                                        </div>
                                    <?php } else { ?>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" id="status" value="0"
                                                       checked="checked">Inactive
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" id="status" value="1">Active
                                            </label>
                                        </div
                                    <?php } ?>
                                </div>
                            <?php }
                        } ?>
                        <button type="submit" name="update" class="btn btn-info">Update</button>

                    </form>
                </div>
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
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
    </body>
    </html>
<?php } ?>
