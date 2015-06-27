<?php

$data=array();
$datafile;
$outputfile;

$mydb=new mysqli('localhost', 'root','test','gsi');


function getregnum() {
	global $mydb;

	$res = $mydb->query("select val from information where id = 'AC11'");
	if ($res !== FALSE ) {
		$boom = $res->fetch_row();
		return $boom[0];
	} else {
		return 'NA';
	};
	$res->free();
}

function saveregnum($regnum) {
	global $mydb;
	
	$mydb->query("update information set val = '$regnum' where id = 'AC11'");
}

function setSellingPrice($counter, $code, $sellprice, $name)
{
	global $mydb;
	
	$sql1 = <<<EOS
	insert into sellingprices (`id`, `regnum`,`idatetime`, `iditem`, `normalprice`, `minprice`, `approvalby`, `userlog`, `datetimelog`)
	values (?,?,?,?,?,?,?,?,?)
EOS;
	$stmtprice = $mydb->prepare($sql1);
	if (!$stmtprice) {
		echo $mydb->error."\n";
		die;
	}
	$id = date('YmdHis');
	$id = $id.str_pad($counter, 5, '0', STR_PAD_LEFT).'A';
	$idatetime = date('Y/m/d H:i:s');
	$regnum = getregnum();
	saveregnum($regnum+1);
	$userlog = '13926111914000000';
	$datetimelog = $idatetime;
	echo $code."-".$name."-";
	$res = $mydb->query("select id from items where code = '$code'");
	if ($res !== FALSE ) {
		$row = $res->fetch_assoc();
		if ($row !== FALSE) {
			$approvalby = 'Bp Welly T';
			$stmtprice->bind_param('sssssssss', $id, $regnum, $idatetime, $row['id'], $sellprice,
					$sellprice, $approvalby, $userlog, $datetimelog);
			$stmtprice->execute();
			echo $name."\n";
		};
		$res->close();
	} else {
		echo "fail to find code \n";
	}
	$stmtprice->close();
}

function processData()
{
	global $data;
	global $mydb;	
	global $outputfile;
	
	$data = $mydb->query("select b.id, b.remark from detailpurchasesstockentries b where not isnull(b.remark)");
	$stmt1 = $mydb->prepare("update purchasesstockentries set remark = ? where id = ?");
	
	if ($data != FALSE) {
		while( $row = $data->fetch_assoc() ) {
			$stmt1->bind_param('ss', $row['remark'], $row['id']);
			$stmt1->execute();
		}
	}  
}

processData();


?>
