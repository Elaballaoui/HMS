<?php
global $cssLinkList, $pageTitleList;
require_once __DIR__.'/config/app.php';
$cssLink=$cssLinkList['login'];
$pageTitle=$pageTitleList['Connecter'];
include_once 'includes/header.php';

session_start();

$_SESSION["user"]="";
$_SESSION["usertype"]="";

// Set the new timezone
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');

$_SESSION["date"]=$date;

//import database
include("includes/connection.php");

if($_POST){

    $email=$_POST['useremail'];
    $password=$_POST['userpassword'];

    $error='<label for="promter" class="form-label"></label>';

    $result= $database->query("select * from webuser where email='$email'");
    if($result->num_rows==1){
        $utype=$result->fetch_assoc()['usertype'];
        if ($utype=='p'){
            //TODO
            $checker = $database->query("select * from patient where pemail='$email' and ppassword='$password'");
            if ($checker->num_rows==1){
                //   Patient dashbord
                $_SESSION['user']=$email;
                $_SESSION['usertype']='p';

                header('location: patient/index.php');
            }else{
                $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Informations identification erronées : e-mail ou mot de passe invalide</label>';
            }
        }elseif($utype=='a'){
            //TODO
            $checker = $database->query("select * from admin where aemail='$email' and apassword='$password'");
            if ($checker->num_rows==1){
                //   Admin dashbord
                $_SESSION['user']=$email;
                $_SESSION['usertype']='a';

                header('location: admin/index.php');
            }else{
                $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Informations identification erronées : e-mail ou mot de passe invalide</label>';
            }
        }elseif($utype=='d'){
            //TODO
            $checker = $database->query("select * from doctor where docemail='$email' and docpassword='$password'");
            if ($checker->num_rows==1){
                //   doctor dashbord
                $_SESSION['user']=$email;
                $_SESSION['usertype']='d';
                
                header('location: doctor/index.php');
            }else{
                $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
            }
        }
    }else{
        $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">We cant found any acount for this email.</label>';
    }
}else{
    $error='<label for="promter" class="form-label">&nbsp;</label>';
}
?>

<center>
    <div class="container">
        <table border="0" style="margin: 0;padding: 0;width: 60%;">
            <tr>
                <td>
                    <p class="header-text">Bienvenue sur eHospital!</p>
                </td>
            </tr>
            <div class="form-body">
                <tr>
                    <td>
                        <p class="sub-text">Connectez-vous avec vos coordonnées pour continuer</p>
                    </td>
                </tr>
                <tr>
                    <form action="" method="POST" >
                        <td class="label-td">
                            <label for="useremail" class="form-label">Email: </label>
                        </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <input type="email" name="useremail" class="input-text" placeholder="Adresse Email" required>
                    </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <label for="userpassword" class="form-label">Mot de passe: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <input type="Password" name="userpassword" class="input-text" placeholder="Mot de passe" required>
                    </td>
                </tr>
                <tr>
                    <td><br>
                        <?php echo $error ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Se connecter" class="login-btn btn-primary btn">
                    </td>
                </tr>
            </div>
            <tr>
                <td>
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">Ne pas avoir de compte&#63; </label>
                    <a href="signup.php" class="hover-link1 non-style-link">S'inscrire</a>
                    <br><br><br>
                </td>
            </tr>
                    </form>
        </table>
    </div>
</center>
<?php include_once 'includes/footer.php'?>