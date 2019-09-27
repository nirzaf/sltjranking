<?php
session_start();
//error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    if (isset($_POST['update'])) {
        $athrid = intval($_GET['athrid']);
        $event = $_POST['event'];
        $description = $_POST['description'];
        $points = intval($_POST['points']);
        $sql = "UPDATE tblauthors SET AuthorName=:event, Description=:description, Points=:points WHERE id=:athrid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':event', $event, PDO::PARAM_STR);
        $query->bindParam(':athrid', $athrid, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':points', $points, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['updatemsg'] = "Event info updated successfully";
        header('location:manage-authors.php');
    }
    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
        <meta name="description" content=""/>
        <meta name="author" content=""/>
        <title>SLTJ Ranking Management System | Edit Event</title>
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
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"
            ">
            <div class="panel panel-info">
                <div class="panel-heading">
                   Edit Event Info
                </div>
                <div class="panel-body">
                    <form role="form" method="post">
                        <div class="form-group">
                            <label>Event Name</label>
                            <?php
                            $athrid = intval($_GET['athrid']);
                            $sql = "SELECT * FROM  tblauthors WHERE id=:athrid";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':athrid', $athrid, PDO::PARAM_STR);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            $cnt = 1;
                            if ($query->rowCount() > 0) {
                                foreach ($results as $result) { ?>
                                    <input class="form-control" type="text" name="event"
                                           value="<?php echo htmlentities($result->AuthorName); ?>" required/>
                                <?php }
                            } ?>
                        </div>

                        <div class="form-group">
                            <label>Event Description</label>
                            <?php
                            $athrid = intval($_GET['athrid']);
                            $sql = "SELECT * FROM  tblauthors WHERE id=:athrid";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':athrid', $athrid, PDO::PARAM_STR);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            $cnt = 1;
                            if ($query->rowCount() > 0) {
                                foreach ($results as $result) { ?>
                                    <input class="form-control" type="text" name="description"
                                           value="<?php echo htmlentities($result->Description); ?>"/>
                                <?php }
                            } ?>
                        </div>

                        <div class="form-group">
                            <label>Points</label>
                            <?php
                            $athrid = intval($_GET['athrid']);
                            $sql = "SELECT * FROM  tblauthors WHERE id=:athrid";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':athrid', $athrid, PDO::PARAM_STR);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            $cnt = 1;
                            if ($query->rowCount() > 0) {
                                foreach ($results as $result) { ?>
                                    <input class="form-control" type="text" name="points"
                                           value="<?php echo htmlentities($result->Points); ?>" required/>
                                <?php }
                            } ?>
                        </div>
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