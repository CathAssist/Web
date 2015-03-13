<?php
$contents = file_get_contents("bible.xml");
$meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
$doc = new DOMDocument();
$doc->loadHTML($meta.$contents);
$body = $doc->documentElement->getElementsByTagName('body')->item(0);

$doc1 = new DOMDocument('1.0', 'utf-8');
$doc1->preserveWhiteSpace = false; 
$doc1->formatOutput = true; 
$root = $doc1->createElement('bible');
$doc1->appendChild($root);

$vol = null;
$chapter = null;
$volIndex = 0;
$cIndex = 0;

foreach ($body->childNodes AS $item)
{
	if($item->nodeName=='#text')
		continue;
	$class = $item->getAttribute('class');
	if($class=='t1')
	{
		$vol = $doc1->createElement("template");
		$volIndex++;
		$vol->setAttribute('value',$volIndex);
		$vol->setAttribute('title',$item->textContent);
		$vol->setAttribute('name',$item->getAttribute("name"));
		$vol->setAttribute('sname',$item->getAttribute("sname"));
		$root->appendChild($vol);
		$cIndex = 0;
	}
	else if($class=='t2')
	{
		$t2 = $doc1->createElement("t2");
		$t2->appendChild(new DOMText($item->textContent));
		$vol->appendChild($t2);
	}
	else if($class=='c')
	{
		$chapter = $doc1->createElement("chapter");
		$cIndex++;
		$chapter->setAttribute('value',$cIndex);
		$chapter->setAttribute('title',$item->textContent);
		$vol->appendChild($chapter);
	}
	else if($class=='t3')
	{
		$t3 = $doc1->createElement("t3");
		$t3->appendChild(new DOMText($item->textContent));
		$chapter->appendChild($t3);
	}
	else if($class=='s')
	{
		$section = $doc1->createElement("section");
		$section->setAttribute('value',$item->getAttribute('value'));
		$section->appendChild(new DOMText($item->textContent));
		$chapter->appendChild($section);
	}
}

$doc1->save('D:\\bible1.xml');
//$fn = fopen('D:\\bible1.xml',"w");
//fwrite($fn,$doc1->saveHTML($root));
//fclose($fn);
?>