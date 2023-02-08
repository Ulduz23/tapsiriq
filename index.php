<?php
session_start(); 

if(isset($_SESSION['email']) && isset($_SESSION['parol']))
  {echo'<meta http-equiv="refresh" content="0; URL=tapsiriq.php">'; exit;}

$con=mysqli_connect('localhost','ulduz','777','tapsiriq');



?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<br><br>
<style>
  a{text-decoration:none;}
</style>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Anbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Haqqımızda</a>
        </li>

        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Əlaqə</a>
        </li>

      </ul>
      <form class="d-flex" method="post" action="#cedvel">
        <input class="form-control me-2" type="email" name="email" placeholder="email" aria-label="Search">
        <input class="form-control me-2" type="password" name="parol" placeholder="parol" aria-label="Search">
        <button class="btn btn-outline-success" style="width:200px;" name="daxilol" type="submit">Daxil ol</button>
      </form>
    </div>
  </div>
</nav>
<br><br>

<div class="container">
  <div class="alert alert-warning" role="alert">
  Anbar proqramında işləmək üçün ya qeydiyyatdan keçin, ya da <b>email</b> və <b>parolunuzu</b> daxil edərək sistemə giriş edin
</div>

<?php 

//-----------------------------------------------DAXIL------------------------------------------//

$tarix=date('Y-m-d H:i:s');

if(isset($_POST['daxilol']))
{
  $email=mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['email']))));
  $parol=mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['parol']))));


  $yoxla=mysqli_query($con,"SELECT * FROM users WHERE email='".$email."' AND parol='".$parol."' ");

  if(mysqli_num_rows($yoxla)>0)
  {
    $info=mysqli_fetch_array($yoxla);

    $_SESSION['user_id'] = $info['id'];
    $_SESSION['ad'] = $info['ad'];
    $_SESSION['soyad'] = $info['soyad'];
    $_SESSION['telefon'] = $info['telefon'];
    $_SESSION['email'] = $info['email'];
    $_SESSION['parol'] = $info['parol'];

    echo'<meta http-equiv="refresh" content="0; URL=tapsiriq.php">';
    
  }
}

if(isset($_POST['insert']))
{
    $ad=mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['ad']))));
    $soyad=mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['soyad']))));
    $email=mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['email']))));
    $parol=mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['parol']))));
    $telefon=mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['telefon']))));
    

  if(!empty($ad) && !empty($soyad) && !empty($telefon) && !empty($email) && !empty($parol))
  {
    $yoxla=mysqli_query($con,"SELECT * FROM users WHERE telefon='".$telefon."' OR email='".$email."'");
    $say=mysqli_num_rows($yoxla);
    if($say==0)
    {
      if($parol==$_POST['tparol'])
      {
        $daxil=mysqli_query($con,"INSERT INTO users(ad,soyad,telefon,email,parol,tarix) VALUES('".$ad."','".$soyad."',
          '".$telefon."','".$email."','".$parol."','".$tarix."')");

        if($daxil==true)
          {echo '<div class="alert alert-success role="alert">Qeydiyyata alindiniz.</div>';}
        else
          {echo'<div class="alert alert-danger role="alert">Qeydiyyata alinmadiniz.</div>';}
      }

  else
    {echo'<div class="alert alert-warning role="alert">Parol tekrar parolla eyni olmalidir.</div>';}
    }
else
{echo'<div class="alert alert-danger role="alert">Eyni email veya telefon daxil ola bilmez.</div>';}
  }
else
  echo'<div class="alert alert-warning role="alert">Zehmet olmasa butun xanalari doldurun!</div>';
}

?>

<div class="alert alert-success" role="alert">
    <form method="post" >
      Ad:<br>
      <input type="text" name="ad" class="form-control" autocomplete="on">
      Soyad:<br>
      <input type="text" name="soyad" class="form-control" autocomplete="on">
      Telefon:<br>
      <input type="text" name="telefon" class="form-control" autocomplete="on">
      Email:<br>
      <input type="email" name="email" class="form-control" autocomplete="on">
      Parol:<br>
      <input type="password" name="parol" class="form-control" autocomplete="on">
      Təkrar parol:<br>
      <input type="password" name="tparol" class="form-control" autocomplete="on"><br>
      <button type="submit" class="btn btn-success" name="insert">Qeydiyyatdan keç</button>

    </form>
</div>
</div>