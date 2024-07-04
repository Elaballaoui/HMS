 <?php
    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
            header("location: ../login.php");
        }
    }else{
        header("location: ../login.php");
    }

    //import database
    include("../includes/connection.php");

    if($_POST){
        //print_r($_POST);
        $result= $database->query("select * from webuser");
        $name=$_POST['name'];
        $nic=$_POST['nic'];
        $spec=$_POST['spec'];
        $email=$_POST['email'];
        $tele=$_POST['Tele'];
        $password=$_POST['password'];
        $cpassword=$_POST['cpassword'];
        
        if ($password==$cpassword){
            $error='3';
            $result= $database->query("select * from webuser where email='$email';");
            if($result->num_rows==1){
                $error='1';
            }else{
                $sql1="insert into doctor(docemail,docname,docpassword,docnic,doctel,specialties) values('$email','$name','$password','$nic','$tele',$spec);";
                $sql2="insert into webuser values('$email','d')";
                $database->query($sql1);
                $database->query($sql2);
                //echo $sql1;
                //echo $sql2;
                $error= '4';
            }
        }else{
            $error='2';
        }
    }else{
        //header('location: signup.php');
        $error='3';
    }

    header("location: doctors.php?action=add&error=".$error);
    ?>