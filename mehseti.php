<?php
include"header.php";

echo'<div class="container">';

$tarix=date('Y-m-d H:i:s');

//---------------------------------------------------FORM-------------------------------------------------//

if(!isset($_POST['edit']))
{
  echo'
  <div class="alert alert-danger" role="alert"> 
  <form method="post">
  Tapsiriq:<br>
  <input type="text" name="tapsiriq" class="form-control">
  Gelecek:<br>
  <input type="date" name="gelecek" class="form-control">
  Saat:<br>
  <input type="time" name="saat" class="form-control"><br>
  <button type="submit" name="insert" class="btn btn-dark">Gir</button>

    </form>
    </div>';  
}

if(isset($_POST['edit']))
{
  $edit=mysqli_query($con,"SELECT * FROM mytapsiriq WHERE id='".$_POST['id']."' ");
  $info=mysqli_fetch_array($edit);

  echo'<div class="alert alert-danger" role="alert">
   
    <form method="post">
  Tapsiriq:<br>
  <input type="text" name="tapsiriq" value="'.$info['tapsiriq'].'" class="form-control">
  Gelecek:<br>
  <input type="date" name="gelecek" value="'.$info['gelecek'].'" class="form-control">
  Saat:<br>
  <input type="time" name="saat" value="'.$info['saat'].'" class="form-control"><br>
  <button type="submit" name="insert" class="btn btn-dark">Gir</button>

    </form>
    </div>';  
}

//------------------------------------------DELETE----------------------------------------//

if(isset($_POST['delete']))
{
  echo' Silmeye eminsiniz ?

  <form method="post">

  <button type="submit" name="he" class="btn btn-success">He</button>
  <button type="submit" name="yox" class="btn btn-danger">Yox</button>
  <input type="hidden" name="id" value="'.$_POST['id'].'">

  </form>
  ';
}

if(isset($_POST['he']))
{
  $sil=mysqli_query($con,"DELETE FROM mytapsiriq WHERE id='".$_POST['id']."' ");

  if($sil==true)
  {echo'<div class="alert alert-success" role="alert">Melumatlar ugurla silindi !</div>';}
    else
    {echo'<div class="alert alert-danger" role="alert">Melumatlar silinmedi !</div>';}
}

//-------------------------------------------------DAXIL------------------------------------------------//

if(isset($_POST['insert']))
{
  $tapsiriq=mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['tapsiriq']))));

  if(!empty($tapsiriq))
    {
      if($tarix<$_POST['gelecek'] or $_POST['saat'])
      {
  $daxil=mysqli_query($con,"INSERT INTO mytapsiriq(tapsiriq,gelecek,saat,tarix) 
                      VALUES('".$tapsiriq."','".$_POST['gelecek']."','".$_POST['saat']."','".$tarix."')");

    if($daxil==true)
      {echo'<div class="alert alert-success">Melumatlarin cavabi hazirdir.</div>';}
    else
      {echo'<div class="alert alert-danger">Melumatlar hesablana bilmedi.</div>';}

}
else
  {echo'<div class="alert alert-danger" role="alert">Gelecek vaxt indiden geri olammaz.</div>';}
}
else
  {echo'<div class="alert alert-warning">Bos xanalari doldurun !</div>';}
}

//-----------------------------------------------SECIM DELETE-------------------------------------------//

if(isset($_POST['secsil']))
{
  if(!empty($_POST['secim'][0]))
  {
    for($i=0;$i<count($_POST['secim']); $i++)
      $sil=mysqli_query($con,"DELETE FROM mytapsiriq WHERE id='".$_POST['secim'][$i]."'");
  }
  if($sil==true)
  {echo'<div class="alert alert-success" role="alert">Secimler ugurla silindi.</div>';}
}

//-------------------------------------------SELECT-----------------------------------------//

if(isset($_POST['axtar']) && !empty($_POST['sorgu']))
{
  $axtar = " WHERE (tapsiriq LIKE '%".$_POST['sorgu']."%') ";
}

$sec=mysqli_query($con,"SELECT * FROM mytapsiriq ".$axtar." ORDER BY id DESC");
$say=mysqli_num_rows($sec);

echo'<form method="post">';

echo'
    <table class="table">
    <thead class="thead-dark">

        <th>#</th>
        <th>Tapsiriq</th>
        <th>Gelecek</th>
        <th>Saat</th>
        <th>Tarix</th>
        <th>Qalan Vaxt</th>
        <th><button type="submit" name="secsil" class="btn btn-danger btn-sm">Secimleri sil</button></th>
        

        </thead>

        <tbody>';


$aktiv= 0;
$bitmis= 0;

echo'
<div class="alert alert-secondary" role="alert">
<b>Toplam tapsiriq: '.$say.'</b>|
<b>Aktiv tapsiriq: '.$aktiv.'</b> |
<b>Bitmis tapsiriq: '.$bitmis.'</b>
</div>';
$i=0;

while($info=mysqli_fetch_array($sec)) 
{

$t1=time();
$t2=strtotime($info['gelecek'].''.$info['saat']);

 if($t1>$t2)
  {$bitmis++;}
 else
  {$aktiv++;}

$t=time();
$gun=strtotime($info['gelecek'].' '.$info['saat']);
$t3=strtotime(date('Y-m-d H:i:s'));

$ferq=$gun-$t3;
$deq=round($ferq/60);
$saat=round($deq/60);
$gun=round($saat/24);

if($deq>0 && $deq<60 && $saat<1)
  {$qalanvaxt = $deq.'deq';}
else 
    {$qalanvaxt='<span><i class="fa fa-check-square"></i></span>';}

if($deq>59 && $saat<25)
  {$qalanvaxt = $saat.'saat';}

if($saat>24)
  {$qalanvaxt = $gun.'gun';}

$i++;

if($qalanvaxt==0)
  {$class='class="alert alert-success" ';}
else
  {$class='class="alert alert-warning" ';}

if($qalanvaxt==0)
{$qalanvaxt='<button class="btn btn-success btn-sm"><i class="bi bi-check"></i></button>';}



  echo'<tr '.$class.'>';
  echo'<td>'.$i.' <input type="checkbox" name="secim[]" value="'.$info['id'].'"></td>';
  echo'<td>'.$info['tapsiriq'].'</td>';
  echo'<td>'.$info['gelecek'].'</td>';
  echo'<td>'.$info['saat'].'</td>';
  echo'<td>'.$info['tarix'].'</td>';
  echo'<td>'.$qalanvaxt.'</td>';


echo'<td>
<form method="post">

<input type="hidden" name="id" value="'.$info['id'].'">
<button type="submit" name="delete" class="btn btn-danger btn-sm">Sil</button>
<button type="submit" name="edit" class="btn btn-primary btn-sm">Duzelt</button>

</form>
</td>';

echo'</tr>';

}

echo'</tbody>
</table>

</div>';

?>