<?php
    global $cssLinkList, $pageTitleList, $cssStyleList;
    require_once __DIR__.'/../config/app.php';
    $cssLink=$cssLinkList['admin'];
    $pageTitle=$pageTitleList['Séances Planifiées'];
    $cssStyle=$cssStyleList['doctorsScheduleAppointmentPatient'];
    include_once '../includes/patientHeader.php';

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
    $userfetch=$result->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];

    //echo $userid;
    //echo $username;
    
    date_default_timezone_set('Asia/Kolkata');
    $today = date('Y-m-d');
    //echo $userid;
 ?>
 <div class="container">
     <?php include_once '../includes/patientSidebar.php'?>
     <?php
        $sqlmain= "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduledate>='$today'  order by schedule.scheduledate asc";
        $sqlpt1="";
        $insertkey="";
        $q='';
        $searchtype="Toutes les";
        if($_POST){
            //print_r($_POST);
            if(!empty($_POST["search"])){
                /*TODO: make and understand */
                $keyword=$_POST["search"];
                $sqlmain= "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduledate>='$today' and (doctor.docname='$keyword' or doctor.docname like '$keyword%' or doctor.docname like '%$keyword' or doctor.docname like '%$keyword%' or schedule.title='$keyword' or schedule.title like '$keyword%' or schedule.title like '%$keyword' or schedule.title like '%$keyword%' or schedule.scheduledate like '$keyword%' or schedule.scheduledate like '%$keyword' or schedule.scheduledate like '%$keyword%' or schedule.scheduledate='$keyword' )  order by schedule.scheduledate asc";
                //echo $sqlmain;
                $insertkey=$keyword;
                $searchtype="Search Result : ";
                $q='"';
            }
        }
        $result= $database->query($sqlmain)
     ?>
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr>
                    <td width="13%" >
                    <a href="schedule.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Retour</font></button></a>
                    </td>
                    <td >
                        <form action="" method="post" class="header-search">
                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Rechercher le Nom du Médecin, l'E-mail ou la Date (AAAA-MM-JJ)" list="doctors" value="<?php  echo $insertkey ?>">&nbsp;&nbsp;
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
                                $today1 = date('d-m-Y');
                                echo $today1;
                            ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../assets/img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)"><?php echo $searchtype." Séances"."(".$result->num_rows.")"; ?> </p>
                        <p class="heading-main12" style="margin-left: 45px;font-size:22px;color:rgb(49, 49, 49)"><?php echo $q.$insertkey.$q ; ?> </p>
                    </td>
                </tr>
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
                        <tbody>
                            <?php
                                if($result->num_rows==0){
                                    echo '<tr>
                                    <td colspan="4">
                                    <br><br><br><br>
                                    <center>
                                    <img src="../img/notfound.svg" width="25%">
                                    
                                    <br>
                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Nous avons rien trouvé en rapport avec vos mots clés !</p>
                                    <a class="non-style-link" href="schedule.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Afficher toutes les séances &nbsp;</font></button>
                                    </a>
                                    </center>
                                    <br><br><br><br>
                                    </td>
                                    </tr>';
                                }
                                else{
                                    //echo $result->num_rows;
                                    for ( $x=0; $x<($result->num_rows);$x++){
                                        echo "<tr>";
                                        for($q=0;$q<3;$q++){
                                            $row=$result->fetch_assoc();
                                            if (!isset($row)){
                                                break;
                                            };
                                            $scheduleid=$row["scheduleid"];
                                            $title=$row["title"];
                                            $docname=$row["docname"];
                                            $scheduledate=$row["scheduledate"];
                                            $scheduletime=$row["scheduletime"];

                                            if($scheduleid==""){
                                                break;
                                            }
                                            echo '
                                            <td style="width: 25%;">
                                                    <div  class="dashboard-items search-items"  >
                                                        <div style="width:100%">
                                                                <div class="h1-search">
                                                                    '.substr($title,0,21).'
                                                                </div><br>
                                                                <div class="h3-search">
                                                                    '.substr($docname,0,30).'
                                                                </div>
                                                                <div class="h4-search">
                                                                    '.$scheduledate.'<br>Commence: <b>@'.substr($scheduletime,0,5).'</b> (24h)
                                                                </div>
                                                                <br>
                                                                <a href="booking.php?id='.$scheduleid.'" ><button  class="login-btn btn-primary-soft btn "  style="padding-top:11px;padding-bottom:11px;width:100%"><font class="tn-in-text">Reserver Maintenant</font></button></a>
                                                        </div>      
                                                    </div>
                                            </td>';
                                        }
                                        echo "</tr>";
                                        // echo '<tr>
                                        //     <td> &nbsp;'.
                                        //     substr($title,0,30)
                                        //     .'</td>

                                        //     <td style="text-align:center;">
                                        //         '.substr($scheduledate,0,10).' '.substr($scheduletime,0,5).'
                                        //     </td>
                                        //     <td style="text-align:center;">
                                        //         '.$nop.'
                                        //     </td>

                                        //     <td>
                                        //     <div style="display:flex;justify-content: center;">

                                        //     <a href="?action=view&id='.$scheduleid.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-view"  style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">View</font></button></a>
                                        //    &nbsp;&nbsp;&nbsp;
                                        //    <a href="?action=drop&id='.$scheduleid.'&name='.$title.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-delete"  style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Cancel Session</font></button></a>
                                        //     </div>
                                        //     </td>
                                        // </tr>';
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
<!--    </div>-->
<?php include_once '../includes/footer.php'?>