<?php
session_start();
//error_reporting(0);
include('includes/config.php');
$BranchName = $_SESSION['Branch'];
$District = $_SESSION['District'];
if(strlen($_SESSION['login'])==0)
  { 
header('location:index.php');
}
else{?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SLTJ Ranking Management System | <?PHP echo $BranchName ?> Dash Board</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line"><?php echo $BranchName; ?> BRANCH DASHBOARD</h4>
                
                            </div>

        </div>
             
             <div class="row">



            
                 <div class="col-md-3 col-sm-3 col-xs-6">
                      <div class="alert alert-info back-widget-set text-center">
                            <i class="fa fa-bars fa-5x"></i>
<?php 
$sql1 ="SELECT id from tblstudents where Branch_Name=:bn";
$query1 = $dbh -> prepare($sql1);
$query1->bindParam(':bn',$BranchName,PDO::PARAM_STR);
$query1->execute();
$results1=$query1->fetchAll(PDO::FETCH_OBJ);
$issuedbooks=$query1->rowCount();
?>

                            <h3><?php echo htmlentities($issuedbooks);?> </h3>
                            Registered Events
                        </div>
                    </div>
             
               <div class="col-md-3 col-sm-3 col-xs-6">
                      <div class="alert alert-warning back-widget-set text-center">
                            <i class="fa fa-recycle fa-5x"></i>
<?php
$sql2 ="SELECT COUNT(*) FROM tblstudents WHERE Branch_Name=:bn1 AND Status=0";
$query2 = $dbh -> prepare($sql2);
$query2->bindParam(':bn1',$BranchName,PDO::PARAM_STR);
$num = $query2->execute();
$events=$query2->fetchColumn();
?>

                            <h3><?php echo htmlentities($events);?></h3>
                          Events not approved yet
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-6">
                      <div class="alert alert-warning back-widget-set text-center">
                      <i class="glyphicon glyphicon-briefcase fa-5x"></i>
<?php
$sql3 ="SELECT SUM(Points) as TotalPoints from tblstudents where Branch_Name=:bn2 and Status=1";
$query3 = $dbh->prepare($sql3);
$query3->bindParam(':bn2',$BranchName,PDO::PARAM_STR);
$query3->execute();
$results3=$query3->fetch(PDO::FETCH_ASSOC);
$totalPoints=$results3['TotalPoints'];
?>
                            <h3><?php echo htmlentities($totalPoints);?></h3>
                          Total Points
                        </div>
                    </div>
           </div>
     <!-- CONTENT-WRAPPER SECTION END-->

     <div class="panel panel-default">
                        <div class="panel-heading">
                           Rank List
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#Ranking</th>
                                            <th>Branch Name</th>
                                            <th>Total Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php $sql = "SELECT Branch_Name,Sum(Points) As `Total_Points` from  tblstudents Group by Branch_Name Order by Points DESC";
$qu = $dbh -> prepare($sql);
$qu->execute();
$re=$qu->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($qu->rowCount() > 0)
{
foreach($re as $result)
{               ?>                                      
                                        <tr class="odd gradeX">
                                            <td class="center"><?php echo htmlentities($cnt);?></td>
                                            <td class="center"><?php echo htmlentities($result->Branch_Name);?></td>
                                            <td class="center"><?php echo htmlentities($result->Total_Points);?></td>
                                        </tr>
 <?php $cnt=$cnt+1;}} ?>                                      
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>

<?php include('includes/footer.php');?>
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
