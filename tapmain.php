<?php
session_start(); 

if(!isset($_SESSION['email']) or !isset($_SESSION['parol']))
  {echo'<meta http-equiv="refresh" content="0; URL=index.php">'; exit;}

$con=mysqli_connect('localhost','ulduz','777','tapsiriq');
$tarix=date('Y-m-d H:i:s');

$yaranma=date('Y-m-d H:i:s');


 


if(isset($_GET['t'])=='tapshiriq')
{     
    if(isset($_POST['edit_id']))
    {
        $edit_id=mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['edit_id']))));

        $edit=mysqli_query($con,"SELECT * FROM mytapsiriq WHERE id='".$edit_id."' ");
        $info=mysqli_fetch_array($edit);
    
        echo'
        <div class="alert alert-primary" role="alert">
        <form method="post" id="tapupdate">
        Tapsiriq:<br>
        <input type="text" name="tapsiriq" value="'.$info['tapsiriq'].'" class="form-control">
        Son vaxt:<br>
        <input type="date" name="sonvaxt" value="'.$info['sonvaxt'].'" class="form-control">
        Saat:<br>
        <input type="time" name="saat" value="'.$info['saat'].'" class="form-control"><br>
        <input type="hidden" name="update">
        <button type="button" class="btn btn-primary update">Yenile</button>
        </form>
      </div>';	
    }
    
    
    //----------------------------------------------------UPDATE---------------------------------//
    
    
    if(isset($_POST['update']))
    {
        $tapsiriq=mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['tapsiriq']))));
    
    
     if(!empty($tapsiriq) && !empty($_POST['sonvaxt']) && !empty($_POST['saat']))
      
      {
        $yenile=mysqli_query($con,"UPDATE mytapsiriq SET
                                        tapsiriq='".$tapsiriq."',
                                        sonvaxt='".$_POST['sonvaxt']."',
                                        saat='".$_POST['saat']."'

                                        WHERE id='".$_POST['id']."' AND  user_id='".$_SESSION['user_id']."' ");
    
        if($yenile==true)
        {echo'<div class="alert alert-success" role="alert">Melumat ugurla yenilendi!</div>';}
        else
        {echo'<div class="alert alert-danger" role="alert">Melumati yenilemek mumkun olmadi!<br>';}
    
     }
    
    else
    {echo'<div class="alert alert-warning" role="alert">Lutfen melumatlari tam doldurun!<br>';}
    
    }
    

//------------------------------------------DELETE----------------------------------------//


if(isset($_POST['delete_id']))
{
	$sil=mysqli_query($con,"DELETE FROM mytapsiriq WHERE id='".$_POST['delete_id']."' ");

	if($sil==true)
	{echo'<div class="alert alert-success" role="alert">Melumatlar ugurla silindi !</div>';}
    else
    {echo'<div class="alert alert-danger" role="alert">Melumatlar silinmedi !</div>';}
}

//----------------------------------------------------------------------------------//


if(!isset($_POST['edit_id']))
{
    echo'
    <div class="alert alert-primary" role="alert"> 
    <form method="post" id="tapinsert">
    Tapsiriq:<br>
    <input type="text" name="tapsiriq" class="form-control">
    Son vaxt:<br>
    <input type="date" name="sonvaxt" class="form-control">
    Saat:<br>
    <input type="time" name="saat" class="form-control"><br>
    <input type="hidden" name="insert">
    <button type="button" class="btn btn-dark insert">Gir</button>

    </form>
    </div>';	
}

    //-------------------------------------------------INSERT------------------------------------------------//

if(isset($_POST['insert']))
{
	$tapsiriq=mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['tapsiriq']))));


	if(!empty($tapsiriq))
    {
    	if($yaranma<$_POST['sonvaxt'] or $_POST['saat'])
    	{
				$daxil=mysqli_query($con,"INSERT INTO mytapsiriq(tapsiriq,sonvaxt,saat,yaranma) 
				VALUES('".$tapsiriq."','".$_POST['sonvaxt']."','".$_POST['saat']."','".$yaranma."')");

				if($daxil==true)
				{echo'<div class="alert alert-success" role="alert">Melumatlarin cavabi hazirdir.</div>';}
				else
				{echo'<div class="alert alert-danger" role="alert">Melumatlar hesablana bilmedi.</div>';}

		}
			else
			{echo'<div class="alert alert-danger" role="alert">Gelecek vaxt indikinden az ola bilmez!</div>';}
	}
else
	{echo'<div class="alert alert-warning">Bos xanalari doldurun !</div>';}
}




//-------------------------------------------SELECT-----------------------------------------//
$input=$_POST['input'];
$sec=mysqli_query($con,"SELECT * FROM mytapsiriq WHERE tapsiriq LIKE '{$input}%' ".$axtar." ORDER BY id DESC");
$say=mysqli_num_rows($sec);


echo'<form method="post" id="tsecsil">';

echo'
    <table class="table">
    <thead class="thead-dark">

    <th>#</th>
    <th>Tapsiriq</th>
    <th>Sonvaxt</th>
    <th>Saat</th>
    <th>Tarix</th>
    <th>Qalan Vaxt</th>
    <th><button type="button" name="secsil" class="btn btn-danger btn-sm secsil" id="'.$info['id'].'">Secimleri sil</button></th>

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
$t2=strtotime($info['sonvaxt'].''.$info['saat']);

 if($t1>$t2)
  {$bitmis++;}
 else
  {$aktiv++;}

$t=time();
$gun=strtotime($info['sonvaxt'].' '.$info['saat']);
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
  echo'<td>'.$info['sonvaxt'].'</td>';
  echo'<td>'.$info['saat'].'</td>';
  echo'<td>'.$info['yaranma'].'</td>';
  echo'<td>'.$qalanvaxt.'</td>';


echo'<td>
<form method="post">

<input type="hidden" name="id" value="'.$info['id'].'">
<button type="button" name="delete" class="btn btn-danger btn-sm delete" id="'.$info['id'].'">Sil</button>
<button type="button" name="edit" class="btn btn-primary btn-sm edit" id="'.$info['id'].'"">Edit</button>

</form>
</td>';

echo'</tr>';

}

echo'</tbody>
</table>';





}
























?>

