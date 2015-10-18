<?php
	require_once("../../include/dbconn.php");
	header('Content-Type: text/html; charset=utf-8');

	$singerid = 237;
	$allsrc = "http://www.cathassist.org/radio/getradio.php";
	$alljs = json_decode(file_get_contents($allsrc));
	foreach ($alljs as $k => $v)
{
	$alumeid = -1;
	$ckey = $k;
	$csrc = "http://www.cathassist.org/radio/getradio.php?channel=".$ckey;

	$rcheck = mysql_query("select id from alume where name='".$v->title."' and singer=".$singerid.";");
	if(mysql_num_rows($rcheck)<1)
	{
		mysql_query("insert into alume(name,pic,src,singer,pubdate,slogen) values('".$v->title."','".$v->logo."','".$csrc."',".$singerid.",'".$v->date."','".$v->desc."')");

		if(mysql_affected_rows()>0)
		{
			$alumeid = mysql_insert_id();
		}
	}
	else
	{
		while ($row = mysql_fetch_array($rcheck))
		{
			$alumeid = $row['id'];
			break;
		}
	}

	echo("checking:".$v->title." id:".$alumeid."<br/>");

	if($alumeid < 0)
	{
		die("can not find alume's id...<br/>");
	}

	{
		$ctitle = $v->title;
		$cdate = $v->date;
		$clogo = $v->logo;
		$cdesc = $v->desc;

		foreach($v->items as $i)
		{
			$idate = DateTime::createFromFormat('Y-m-d',$cdate)->getTimestamp() + 3600*8;
			$strIDate = gmdate('Y年m月d日',$idate);
			$ititle = $i->title.'('.$strIDate.')';
			$imp3src = $i->src;

			$ritem = mysql_query('select id from song where src="'.$imp3src.'";');
			if(mysql_num_rows($ritem) > 0)
			{
//				echo "valid:".$ititle."<br/>";
				continue;
			}
			else
			{
				echo "not valid:".$ititle."<br/>";

				mysql_query('insert into song(name,src,alume,singer,z_src,pubdate) values("'.$ititle.'","'.$imp3src.'",'.$alumeid.','.$singerid.',"'.$imp3src.'"'.',"'.$cdate.'");');
				if(mysql_affected_rows()<1)
				{
					echo("insert into db:".$ititle.'<br/>');
				}
			}
		}
	}
}

	die();
	return;
?>