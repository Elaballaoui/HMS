 <?php
     global $cssLinkList, $pageTitleList, $cssStyleList;
     require_once __DIR__.'/../config/app.php';
     $cssLink=$cssLinkList['admin'];
     $pageTitle=$pageTitleList['Tableau de bord'];
     $cssStyle=$cssStyleList['indexDoctor'];
     include_once '../includes/doctorHeader.php';

     session_start();

     if(isset($_SESSION["user"])){
         if(($_SESSION["user"])=="" or $_SESSION['usertype']!='d'){
             header("location: ../login.php");
         }else{
             $useremail=$_SESSION["user"];
         }
     }else{
         header("location: ../login.php");
     }

    //import database
    include("../includes/connection.php");
    $userrow = $database->query("select * from doctor where docemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["docid"];
    $username=$userfetch["docname"];
    //echo $userid;
    //echo $username;
    ?>
    <div class="container">
        <?php include_once '../includes/doctorSidebar.php'?>
        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
                <tr>
                    <td colspan="1" class="nav-bar" >
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;">Tableau de bord</p>
                    </td>
                    <td width="25%">
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">Date d'Aujourd'hui</p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php
                                date_default_timezone_set('Asia/Kolkata');
                                $today = date('Y-m-d');
                                echo $today;

                                $patientrow = $database->query("select  * from  patient;");
                                $doctorrow = $database->query("select  * from  doctor;");
                                $appointmentrow = $database->query("select  * from  appointment where appodate>='$today';");
                                $schedulerow = $database->query("select  * from  schedule where scheduledate='$today';");
                                ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../assets/img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" >
                    <center>
                    <table class="filter-container doctor-header" style="border: none;width:95%" border="0" >
                    <tr>
                        <td>
                            <h3>Bienvenue !</h3>
                            <h1><?php echo $username  ?>.</h1>
                            <p>Merci de vous joindre à nous. Nous essayons toujours de vous offrir un service complet<br>
                                Vous pouvez consulter votre emploi du temps quotidien, atteindre le rendez-vous du patient à la maison!<br><br>
                            </p>
                            <a href="appointment.php" class="non-style-link"><button class="btn-primary btn" style="width:30%">Afficher mes Rendez-vous</button></a>
                            <br><br>
                        </td>
                    </tr>
                    </table>
                    </center>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table border="0" width="100%"">
                            <tr>
                                <td width="50%">
                                    <center>
                                        <table class="filter-container" style="border: none;" border="0">
                                            <tr>
                                                <td colspan="4">
                                                    <p style="font-size: 20px;font-weight:600;padding-left: 12px;">Statut</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%;">
                                                    <div class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex">
                                                        <div>
                                                            <div class="h1-dashboard">
                                                                <?php echo $doctorrow->num_rows ?>
                                                            </div><br>
                                                            <div class="h3-dashboard">Tous les Médecins &nbsp;&nbsp;&nbsp;&nbsp</div>
                                                        </div>
                                                        <div class="btn-icon-back dashboard-icons" style="background-image: url('../assets/img/icons/doctors-hover.svg');"></div>
                                                    </div>
                                                </td>
                                                <td style="width: 25%;">
                                                    <div class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex;">
                                                        <div>
                                                            <div class="h1-dashboard">
                                                                <?php echo $patientrow->num_rows ?>
                                                            </div><br>
                                                            <div class="h3-dashboard">Tous les Patients &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                                        </div>
                                                        <div class="btn-icon-back dashboard-icons" style="background-image: url('../assets/img/icons/patients-hover.svg');"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%;">
                                                    <div class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex; ">
                                                        <div>
                                                            <div class="h1-dashboard" >
                                                                    <?php echo $appointmentrow ->num_rows ?>
                                                            </div><br>
                                                            <div class="h3-dashboard" >Nouvelle Réservation&nbsp;&nbsp;</div>
                                                        </div>
                                                        <div class="btn-icon-back dashboard-icons" style="margin-left: 0px;background-image: url('../assets/img/icons/book-hover.svg');"></div>
                                                    </div>
                                                </td>
                                                <td style="width: 25%;">
                                                    <div class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex;padding-top:21px;padding-bottom:21px;">
                                                        <div>
                                                            <div class="h1-dashboard">
                                                                <?php echo $schedulerow ->num_rows ?>
                                                            </div><br>
                                                            <div class="h3-dashboard" style="font-size: 15px">Séances D'aujourd'hui</div>
                                                        </div>
                                                        <div class="btn-icon-back dashboard-icons" style="background-image: url('../assets/img/icons/session-iceblue.svg');"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </center>
                                </td>
                                <td>
                                    <p id="anim" style="font-size: 20px;font-weight:600;padding-left: 40px;">Vos prochaines séances jusqu'à la semaine prochaine</p>
                                    <center>
                                        <div class="abc scroll" style="height: 250px;padding: 0;margin: 0;">
                                        <table width="85%" class="sub-table scrolldown" border="0" >
                                        <thead>
                                        <tr>
                                            <th class="table-headin">Titre de la Séance</th>
                                            <th class="table-headin">Date Planifiée</th>
                                            <th class="table-headin">Temps</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $nextweek=date("Y-m-d",strtotime("+1 week"));
                                                $sqlmain= "select schedule.scheduleid,schedule.title,doctor.docname,schedule.scheduledate,schedule.scheduletime,schedule.nop from schedule inner join doctor on schedule.docid=doctor.docid  where schedule.scheduledate>='$today' and schedule.scheduledate<='$nextweek' order by schedule.scheduledate desc";
                                                $result= $database->query($sqlmain);
                
                                                if($result->num_rows==0){
                                                    echo '<tr>
                                                        <td colspan="4">
                                                        <br><br><br><br>
                                                        <center>
                                                        <img src="../assets/img/notfound.svg" width="25%">
                                                        <br>
                                                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Nous avons rien trouvé en rapport avec vos mots clés!</p>
                                                        <a class="non-style-link" href="schedule.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp;Afficher Toutes les Séances &nbsp</font></button>
                                                        </a>
                                                        </center>
                                                        <br><br><br><br>
                                                        </td>
                                                    </tr>';
                                                }
                                                else{
                                                for ( $x=0; $x<$result->num_rows;$x++){
                                                    $row=$result->fetch_assoc();
                                                    $scheduleid=$row["scheduleid"];
                                                    $title=$row["title"];
                                                    $docname=$row["docname"];
                                                    $scheduledate=$row["scheduledate"];
                                                    $scheduletime=$row["scheduletime"];
                                                    $nop=$row["nop"];
                                                    echo '<tr>
                                                        <td style="padding:20px;"> &nbsp;'.
                                                        substr($title,0,30)
                                                        .'</td>
                                                        <td style="padding:20px;font-size:13px;">
                                                        '.substr($scheduledate,0,10).'
                                                        </td>
                                                        <td style="text-align:center;">
                                                            '.substr($scheduletime,0,5).'
                                                        </td>
                                                    </tr>';
                                                }
                                            }
                                            ?>
                                        </tbody>
                                        </table>
                                        </div>
                                    </center>
                                </td>
                            </tr>
                        </table>
                    </td>
            </table>
        </div>
    </div>
<?php include_once '../includes/footer.php' ?>