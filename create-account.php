<?php
    global $cssLinkList, $pageTitleList;
    require_once __DIR__.'/config/app.php';
    $cssLink=$cssLinkList['signup'];
    $pageTitle=$pageTitleList['Inscrire'];
    include_once 'includes/header.php';

    session_start();

    $_SESSION["user"]="";
    $_SESSION["usertype"]="";

    // Set the new timezone
    date_default_timezone_set('Africa/Casablanca');
    $date = date('Y-m-d');

    $_SESSION["date"]=$date;

    //import database
    include("includes/connection.php");

    if($_POST){
        $result= $database->query("select * from webuser");

        $fname=$_SESSION['personal']['fname'];
        $lname=$_SESSION['personal']['lname'];
        $name=$fname." ".$lname;
        $address=$_SESSION['personal']['address'];
        $nic=$_SESSION['personal']['nic'];
        $dob=$_SESSION['personal']['dob'];
        $email=$_POST['newemail'];
        $tele=$_POST['tele'];
        $newpassword=$_POST['newpassword'];
        $cpassword=$_POST['cpassword'];

        if ($newpassword==$cpassword){
            $sqlmain= "select * from webuser where email=?;";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("s",$email);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows==1){
                $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
            }else{
                $database->query("insert into patient(pemail,pname,ppassword, paddress, pnic,pdob,ptel) values('$email','$name','$newpassword','$address','$nic','$dob','$tele');");
                $database->query("insert into webuser values('$email','p')");

                //print_r("insert into patient values($pid,'$email','$fname','$lname','$newpassword','$address','$nic','$dob','$tele');");
                $_SESSION["user"]=$email;
                $_SESSION["usertype"]="p";
                $_SESSION["username"]=$fname;

                header('Location: patient/index.php');
                $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>';
            }
        }else{
            $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Conformation Error! Reconform Password</label>';
        }
    }else{
        //header('location: signup.php');
        $error='<label for="promter" class="form-label"></label>';
    }
?>

<center>
    <div class="container">
        <table border="0" style="width: 69%;">
            <tr>
                <td colspan="2">
                    <p class="header-text">Commençons</p>
                    <p class="sub-text">C'est bon, maintenant créer pas de compte utilisateur.</p>
                </td>
            </tr>
            <tr>
            <form action="" method="POST" >
                <td class="label-td" colspan="2">
                    <label for="newemail" class="form-label">Email: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="email" name="newemail" class="input-text" placeholder="Adresse Email" required>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="tele" class="form-label">Numéro de Téléphone: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="tel" name="tele" class="input-text"  placeholder="ex: 0712345678" pattern="[0]{1}[0-9]{9}" >
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="newpassword" class="form-label">Créer un Nouveau Mot de Passe: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="password" name="newpassword" class="input-text" placeholder="Nouveau Mot de Passe" required>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="cpassword" class="form-label">Confirmez le Mot de Passe: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="password" name="cpassword" class="input-text" placeholder="Confirmez le Mot de Passe" required>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php echo $error ?>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="reset" value="Réinitialiser" class="login-btn btn-primary-soft btn" >
                </td>
                <td>
                    <input type="submit" value="S'inscrire" class="login-btn btn-primary btn">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br>
                        <label for="" class="sub-text" style="font-weight: 280;">Vous avez déjà un compte&#63; </label>
                        <a href="login.php" class="hover-link1 non-style-link">Se connecter</a>
                    <br><br><br>
                </td>
            </tr>
            </form>
            </tr>
        </table>
    </div>
</center>
<?php include_once 'includes/footer.php'?>