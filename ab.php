
<?php
if(isset($_POST['axtar']) && !empty($_POST['sorgu']))
{
	$sorgu = mysqli_real_escape_string($con,htmlspecialchars(strip_tags(trim($_POST['sorgu']))));
	$axtar = " AND (ad LIKE '%".$sorgu."%' OR tarix1 LIKE '%".$sorgu."%' OR vaxt LIKE '%".$sorgu."%') ";
}
else
{$axtar = "";}

//AXTAR END---------------------------------------------------------------------------

$sec = mysqli_query($con,"SELECT * FROM qeyd WHERE userid='".$_SESSION['userid']."' ".$axtar.$order);
$say = mysqli_num_rows($sec);

$aktiv = 0;
$passiv = 0;
  
if($say>0)
{
 	$st = mysqli_query($con,"SELECT * FROM qeyd WHERE userid='".$_SESSION['userid']."'");


	while($stinfo = mysqli_fetch_array($st))
	{   
	$from = time();
	$to = $stinfo['tarix1'].' '.$stinfo['vaxt'].':00';
    $to = strtotime($to);

    $san = $to - $from;
	$deq = round($san/60);
	$saat = round($deq/60);
	$gun = round($saat/24);

	if($san>0)
	{$aktiv++;}
	else
	{$passiv++;}
	}	  

	echo'<div class="alert alert-success" role="alert">
	<b>Tapşırıq: '.$say.' </b>  | 
	<b>Aktiv: '.$aktiv.'</b>  | 
	<b>Bitmiş: '.$passiv.'</b>  | 
	
	</div>'; 
	
	$i = 0;
    
	echo'<table class="table table-bordered" id="cedvel">';

	echo'<thead>
			<th>#</th>
			<th>Tapşırıq '.$f1.'</th>
			<th>Tarix '.$f2.'</th>
			<th>Vaxt '.$f3.'</th>
      <th>Qalan vaxt</th>
			<th>Tarix '.$f4.'</th>
		  </thead>';

	echo'<tbody>';


	while($info = mysqli_fetch_array($sec))
	{   
		 
 
	  $from = time();
	  $to = $info['tarix1'].' '.$info['vaxt'].':00';
    $to = strtotime($to);

    $san = $to - $from;
	  $deq = round($san/60);
	  $saat = round($deq/60);
	  $gun = round($saat/24);

	  if($deq<59 && $deq>0)
	  {$qaliq = $deq.' dəqiqə';}
	  elseif($deq>59 && $saat<24)
	  {$qaliq = $saat.' saat';}
	  elseif($saat>24)
	  {$qaliq = $gun.' gün';}
		else
		{$qaliq = '<button type="submit" name="tesdiq" class="btn btn-success btn-sm" title="Təsdiq et"><i class="bi bi-check"></i></button>';}
	  

        
		$i++;
		echo'<tr>';
		echo'<td>'.$i.'</td>';
		echo'<td>'.$info['ad'].' </td>';
		echo'<td>'.$info['tarix1'].'</td>';
		echo'<td>'.$info['vaxt'].'</td>';
    echo'<td>'.$qaliq.'</td>';
    echo'<td>'.$info['tarix'].'</td>';
		echo'
		<form method="post">
			<input type="hidden" name="id" value="'.$info['id'].'">
			<input type="hidden" name="pid" value="'.$info['tarix1'].'">
			<input type="hidden" name="stok" value="'.$info['vaxt'].'">
		</form>
		</td>
		</tr>';

		
	}

	echo'</tbody>';
	echo'</table>';
}

echo'</div>';