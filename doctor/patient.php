 <?php
    global $cssLinkList, $pageTitleList, $cssStyleList;
    require_once __DIR__.'/../config/app.php';
    $cssLink=$cssLinkList['admin'];
    $pageTitle=$pageTitleList['Mes Patients'];
    $cssStyle=$cssStyleList['appointmentSchedulePatientDoctor'];
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
    <?php
    include_once '../includes/doctorSidebar.php';
        $selecttype="Mes";
        $current="Mes patients uniquement";
        if($_POST){
            if(isset($_POST["search"])){
                $keyword=$_POST["search12"];
                $sqlmain= "select * from patient where pemail='$keyword' or pname='$keyword' or pname like '$keyword%' or pname like '%$keyword' or pname like '%$keyword%' ";
                $selecttype="Mes";
            }
            if(isset($_POST["filter"])){
                if($_POST["showonly"]=='all'){
                    $sqlmain= "select * from patient";
                    $selecttype="Tous";
                    $current="Tous les patients";
                }else{
                    $sqlmain= "select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=$userid;";
                    $selecttype="Mes";
                    $current="Mes patients uniquement";
                }
            }
        }else{
            $sqlmain= "select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=$userid;";
            $selecttype="Mes";
        }
        ?>
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr>
                    <td width="13%">
                        <a href="index.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Retour</font></button></a>
                    </td>
                    <td>
                        <form action="" method="post" class="header-search">
                            <input type="search" name="search12" class="input-text header-searchbar" placeholder="Rechercher le nom ou l'adresse e-mail du patient" list="patient">&nbsp;&nbsp;
                            <?php
                                echo '<datalist id="patient">';
                                $list11 = $database->query($sqlmain);
                                //$list12= $database->query("select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=1;");
                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    $d=$row00["pname"];
                                    $c=$row00["pemail"];
                                    echo "<option value='$d'><br/>";
                                    echo "<option value='$c'><br/>";
                                };

                                echo ' </datalist>';
                            ?>
                            <input type="Submit" value="Recherche" name="search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                        </form>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">Date d'Aujourd'hui</p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php
                                date_default_timezone_set('Africa/Casablanca');
                                $date = date('d-m-Y');
                                echo $date;
                            ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../assets/img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)"><?php echo $selecttype." Patients (".$list11->num_rows.")"; ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:0px;width: 100%;" >
                        <center>
                        <table class="filter-container" border="0" >
                            <form action="" method="post">
                            <td  style="text-align: right;">
                                Afficher les détails : &nbsp;
                            </td>
                            <td width="30%">
                                <select name="showonly" id="" class="box filter-container-items" style="width:90% ;height: 37px;margin: 0;" >
                                    <option value="" disabled selected hidden><?php echo $current   ?></option><br/>
                                    <option value="my">Mes patients uniquement</option><br/>
                                    <option value="all">Tous les patients</option><br/>
                                </select>
                            </td>
                                <td width="12%">
                                <input type="submit"  name="filter" value="Filtre" class=" btn-primary-soft btn button-icon btn-filter"  style="padding: 15px; margin :0;width:100%">
                            </form>
                    </td>
                </tr>
                        </table>
                        </center>
                    </td>
                </tr>
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="93%" class="sub-table scrolldown"  style="border-spacing:0;">
                        <thead>
                            <tr>
                                <th class="table-headin">Nom</th>
                                <th class="table-headin">CIN</th>
                                <th class="table-headin">Téléphone</th>
                                <th class="table-headin">Email</th>
                                <th class="table-headin">Date de Naissance</th>
                                <th class="table-headin">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $result= $database->query($sqlmain);
                                //echo $sqlmain;
                                if($result->num_rows==0){
                                    echo '<tr>
                                        <td colspan="4">
                                            <br><br><br><br>
                                            <center>
                                            <img src="../assets/img/notfound.svg" width="25%">
                                            <br>
                                            <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Nous avons rien trouvé en rapport avec vos mots clés !</p>
                                            <a class="non-style-link" href="patient.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Afficher Tous les Patients &nbsp;</font></button></a>
                                            </center>
                                            <br><br><br><br>
                                        </td>
                                    </tr>';
                                }
                                else{
                                    for ( $x=0; $x<$result->num_rows;$x++){
                                        $row=$result->fetch_assoc();
                                        $pid=$row["pid"];
                                        $name=$row["pname"];
                                        $email=$row["pemail"];
                                        $nic=$row["pnic"];
                                        $dob=$row["pdob"];
                                        $tel=$row["ptel"];

                                        echo '<tr>
                                            <td> &nbsp;'.
                                                substr($name,0,35)
                                            .'</td>
                                            <td>
                                                '.substr($nic,0,12).'
                                            </td>
                                            <td>
                                                '.substr($tel,0,10).'
                                            </td>
                                            <td>
                                                '.substr($email,0,20).'
                                             </td>
                                            <td>
                                                '.substr($dob,0,10).'
                                            </td>
                                            <td>
                                                <div style="display:flex;justify-content: center;">
                                                    <a href="?action=view&id='.$pid.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-view"  style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Voir</font></button></a>
                                                </div>
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
        </div>
    </div>
    <?php 
        if($_GET){
            $id=$_GET["id"];
            $action=$_GET["action"];
                $sqlmain= "select * from patient where pid='$id'";
                $result= $database->query($sqlmain);
                $row=$result->fetch_assoc();
                $name=$row["pname"];
                $email=$row["pemail"];
                $nic=$row["pnic"];
                $dob=$row["pdob"];
                $tele=$row["ptel"];
                $address=$row["paddress"];
                echo '
                <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <a class="close" href="patient.php">&times;</a>
                        <div class="content"></div>
                        <div style="display: flex;justify-content: center;">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Voir les Détails.</p><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Patient ID: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    P-'.$id.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Nom: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$name.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Email" class="form-label">Email: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$email.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nic" class="form-label">CIN: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$nic.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Téléphone: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$tele.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label">Adresse: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$address.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Date de Naissance: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$dob.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="patient.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" ></a>
                                </td>
                            </tr>
                        </table>
                        </div>
                    </center>
                    <br><br>
                    </div>
                </div>
                ';
        }
?>
 <?php include_once '../includes/footer.php' ?>