<?php
    $pageTitle="Tableau de bord";
    include_once '../includes/patientHeader.php';
?>
<?php
    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }
    }else{
        header("location: ../login.php");
    }

    //import database
    include("../includes/connection.php");

    $sqlmain= "select * from patient where pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s",$useremail);
    $stmt->execute();
    $userrow = $stmt->get_result();
    $userfetch=$userrow->fetch_assoc();

    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];

    //echo $userid;
    //echo $username;
?>
    <div class="container">
        <?php include_once '../includes/patientSidebar.php'?>
        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
                <tr>
                    <td colspan="1" class="nav-bar" >
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;">Accueil</p>
                    </td>
                    <td width="25%">
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">Date d'Aujourd'hui</p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php
                                date_default_timezone_set('Asia/Kolkata');
        
                                $today = date('d-m-Y');
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
                    <table class="filter-container doctor-header patient-header" style="border: none;width:95%" border="0" >
                    <tr>
                        <td>
                            <h3>Bienvenu!</h3>
                            <h1><?php echo $username  ?>.</h1>
                            <p>Vous n'avez aucune idée des médecins ? pas de problème, passons à
                                <a href="doctors.php" class="non-style-link"><b>"Tous les médecins"</b></a> section ou
                                <a href="schedule.php" class="non-style-link"><b>"Séances"</b></a>.<br>
                                Suivez l'historique de vos rendez-vous passés et futurs.<br>Renseignez-vous également sur l'heure d'arrivée prévue de votre médecin ou médecin-conseil.<br><br>
                            </p>
                            <h3>Canalisez un Médecin Ici</h3>
                            <form action="schedule.php" method="post" style="display: flex">
                                <input type="search" name="search" class="input-text " placeholder="Recherchez un Médecin et Nous Trouverons la Séance Disponible" list="doctors" style="width:50%;">&nbsp;&nbsp;
                                <?php
                                    echo '<datalist id="doctors">';
                                    $list11 = $database->query("select  docname,docemail from  doctor;");
    
                                    for ($y=0;$y<$list11->num_rows;$y++){
                                        $row00=$list11->fetch_assoc();
                                        $d=$row00["docname"];
                                        
                                        echo "<option value='$d'><br/>";
                                    };
    
                                    echo '</datalist>';
                                ?>
                                <input type="Submit" value="Recherche" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                            <br>
                            <br>
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
                                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex">
                                                        <div>
                                                                <div class="h1-dashboard">
                                                                    <?php    echo $doctorrow->num_rows  ?>
                                                                </div><br>
                                                                <div class="h3-dashboard">
                                                                    Tous les Médecins &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                </div>
                                                        </div>
                                                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../assets/img/icons/doctors-hover.svg');"></div>
                                                    </div>
                                                </td>
                                                <td style="width: 25%;">
                                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex;">
                                                        <div>
                                                                <div class="h1-dashboard">
                                                                    <?php    echo $patientrow->num_rows  ?>
                                                                </div><br>
                                                                <div class="h3-dashboard">
                                                                    Tous les patients &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                </div>
                                                        </div>
                                                        <div class="btn-icon-back dashboard-icons" style="background-image: url('../assets/img/icons/patients-hover.svg');"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%;">
                                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex; ">
                                                        <div>
                                                                <div class="h1-dashboard" >
                                                                    <?php    echo $appointmentrow ->num_rows  ?>
                                                                </div><br>
                                                                <div class="h3-dashboard" >
                                                                    Nouvelle Réservation &nbsp;&nbsp;
                                                                </div>
                                                        </div>
                                                                <div class="btn-icon-back dashboard-icons" style="margin-left: 0px;background-image: url('../assets/img/icons/book-hover.svg');"></div>
                                                    </div>
                                                </td>
                                                <td style="width: 25%;">
                                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex;padding-top:21px;padding-bottom:21px;">
                                                        <div>
                                                            <div class="h1-dashboard">
                                                                    <?php    echo $schedulerow ->num_rows  ?>
                                                            </div><br>
                                                            <div class="h3-dashboard">
                                                                    Séances D'aujourd'hui
                                                            </div>
                                                        </div>
                                                        <div class="btn-icon-back dashboard-icons" style="background-image: url('../assets/img/icons/session-iceblue.svg');"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </center>
                                </td>
                                <td>
                                    <p style="font-size: 20px;font-weight:600;padding-left: 40px;" class="anime">Votre Prochaine Réservation</p>
                                    <center>
                                        <div class="abc scroll" style="height: 250px;padding: 0;margin: 0;">
                                        <table width="85%" class="sub-table scrolldown" border="0" >
                                        <thead>
                                        <tr>
                                        <th class="table-headin">
                                                    #.Réservat
                                                    </th>
                                                <th class="table-headin">
                                                    Titre de la Séance
                                                </th>
                                                <th class="table-headin">
                                                    Médecin
                                                </th>
                                                <th class="table-headin">
                                                    Date et Heure Programmées
                                                </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $nextweek=date("d-m-Y",strtotime("+1 week"));
                                                $sqlmain= "select * from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid  where  patient.pid=$userid  and schedule.scheduledate>='$today' order by schedule.scheduledate asc";
                                                //echo $sqlmain;
                                                $result= $database->query($sqlmain);
                
                                                if($result->num_rows==0){
                                                    echo '<tr>
                                                    <td colspan="4">
                                                    <br><br><br><br>
                                                    <center>
                                                    <img src="../assets/img/notfound.svg" width="25%">
                                                    
                                                    <br>
                                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Rien à montrer ici !</p>
                                                    <a class="non-style-link" href="schedule.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Canaliser un médecin &nbsp;</font></button>
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
                                                    $apponum=$row["apponum"];
                                                    $docname=$row["docname"];
                                                    $scheduledate=$row["scheduledate"];
                                                    $scheduletime=$row["scheduletime"];
                                                   
                                                    echo '<tr>
                                                        <td style="padding:30px;font-size:25px;font-weight:700;"> &nbsp;'.
                                                        $apponum
                                                        .'</td>
                                                        <td style="padding:20px;"> &nbsp;'.
                                                        substr($title,0,30)
                                                        .'</td>
                                                        <td>
                                                        '.substr($docname,0,20).'
                                                        </td>
                                                        <td style="text-align:center;">
                                                            '.substr($scheduledate,0,10).' '.substr($scheduletime,0,5).'
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
<?php include_once '../includes/patientFooter.php'?>