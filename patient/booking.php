<?php
    global $cssLinkList, $pageTitleList, $cssStyleList;
    require_once __DIR__.'/../config/app.php';
    $cssLink=$cssLinkList['admin'];
    $pageTitle=$pageTitleList['Réservation'];
    $cssStyle=$cssStyleList['bookingPatient'];
    include_once '../includes/patientHeader.php';

    global$userrow; global$database; global$userrow; global$userrow;
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
    $result = $stmt->get_result();
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];

    //echo $userid;
    //echo $username;

    date_default_timezone_set('Africa/Casablanca');

    $today = date('Y-m-d');
    //echo $userid;
 ?>
 <div class="container">
     <?php include_once '../includes/patientSidebar.php'?>
     <div class="dash-body">
        <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
            <tr>
                <td width="13%" >
                    <a href="index.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Retour</font></button></a>
                </td>
                <td>
                    <form action="schedule.php" method="post" class="header-search">
                        <input type="search" name="search" class="input-text header-searchbar" placeholder="Rechercher le nom du médecin, l'e-mail ou la date (AAAA-MM-JJ)" list="doctors" >&nbsp;&nbsp;
                            <?php
                                echo '<datalist id="doctors">';
                                $list11 = $database->query("select DISTINCT * from  doctor;");
                                $list12 = $database->query("select DISTINCT * from  schedule GROUP BY title;");

                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    $d=$row00["docname"];

                                    echo "<option value='$d'><br/>";
                                };

                                for ($y=0;$y<$list12->num_rows;$y++){
                                    $row00=$list12->fetch_assoc();
                                    $d=$row00["title"];

                                    echo "<option value='$d'><br/>";
                                };
                            echo ' </datalist>';
                            ?>
                        <input type="Submit" value="Recherche" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                    </form>
                </td>
                <td width="15%">
                    <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">Date d'Aujourd'hui</p>
                    <p class="heading-sub12" style="padding: 0;margin: 0;">
                        <?php
                            echo $today;
                        ?>
                    </p>
                </td>
                <td width="10%">
                    <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../assets/img/calendar.svg" width="100%"></button>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top:10px;width: 100%;" >
                    <!-- <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49);font-weight:400;">Scheduled Sessions / Booking / <b>Review Booking</b></p> -->
                </td>
            </tr>
            <tr>
               <td colspan="4">
                   <center>
                    <div class="abc scroll">
                        <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
                        <tbody>
                            <?php
                            if(($_GET)){
                                if(isset($_GET["id"])){
                                    $id=$_GET["id"];

                                    $sqlmain= "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduleid=? order by schedule.scheduledate desc";
                                    $stmt = $database->prepare($sqlmain);
                                    $stmt->bind_param("i", $id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    //echo $sqlmain;
                                    $row=$result->fetch_assoc();
                                    $scheduleid=$row["scheduleid"];
                                    $title=$row["title"];
                                    $docname=$row["docname"];
                                    $docemail=$row["docemail"];
                                    $scheduledate=$row["scheduledate"];
                                    $scheduletime=$row["scheduletime"];
                                    $sql2="select * from appointment where scheduleid=$id";
                                    //echo $sql2;
                                     $result12= $database->query($sql2);
                                     $apponum=($result12->num_rows)+1;

                                    echo '
                                        <form action="booking-complete.php" method="post">
                                            <input type="hidden" name="scheduleid" value="'.$scheduleid.'" >
                                            <input type="hidden" name="apponum" value="'.$apponum.'" >
                                            <input type="hidden" name="date" value="'.$today.'" >
                                    ';

                                    echo '
                                    <td style="width: 50%;" rowspan="2">
                                        <div  class="dashboard-items search-items"  >
                                            <div style="width:100%">
                                                <div class="h1-search" style="font-size:25px;">Détails de la Séance</div><br><br>
                                                <div class="h3-search" style="font-size:18px;line-height:30px">
                                                    Nom du Médecin:  &nbsp;&nbsp;<b>'.$docname.'</b><br>
                                                    E-mail du Médecin:  &nbsp;&nbsp;<b>'.$docemail.'</b> 
                                                </div>
                                                <div class="h3-search" style="font-size:18px;"></div><br>
                                                <div class="h3-search" style="font-size:18px;">
                                                    Titre de la Séance: '.$title.'<br>
                                                    Date Prévue de la Séance: '.$scheduledate.'<br>
                                                    Débuts de Session : '.$scheduletime.'<br>
                                                    Frais de Canalisation : <b>LKR.2 000.00</b>
                                                </div>
                                                <br>  
                                            </div>   
                                        </div>
                                    </td>
                                    <td style="width: 25%;">
                                        <div  class="dashboard-items search-items">
                                            <div style="width:100%;padding-top: 15px;padding-bottom: 15px;">
                                                <div class="h1-search" style="font-size:20px;line-height: 35px;margin-left:8px;text-align:center;">Votre Numéro de Rendez-vous</div>
                                                <center>
                                                <div class=" dashboard-icons" style="margin-left: 0px;width:90%;font-size:70px;font-weight:800;text-align:center;color:var(--btnnictext);background-color: var(--btnice)">'.$apponum.'</div>
                                                </center>
                                                </div><br>
                                                <br>
                                                <br>
                                            </div>       
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="Submit" class="login-btn btn-primary btn btn-book" style="margin-left:10px;padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;width:95%;text-align: center;" value="Réserver Maintenant" name="booknow"></button>
                                    </form>
                                    </td>
                                </tr>
                                        ';
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
        </div>
    </div>
</div>
<?php include_once '../includes/footer.php'?>