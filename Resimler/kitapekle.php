<html>
<head>
<meta charset="utf-8" />
</head>
<body>
<?php
if(empty($_POST)):
?>
<form action="kitapekle.php" method="post"><br />
Kitap Adı : <input type="text" name="kitapadi" /><br />
Yazarı : <input type="text" name="yazari" /><br />
Kategorisi : <input type="text" name="kategorisi" /><br />
Fiyatı : <input type="text" name="fiyati" /><br />
ISBN : <input type="text" name="isbn" /><br />
<input type="submit" value="EKLE" />
<?php
else:
	try {
		$baglanti = new PDO("mysql:host=localhost:3308;dbname=ekitap","root","");
		
	}
	catch(PDOException $e) {
		echo $e->getMessage();
		
	}
	$kitapadi=$_POST["kitapadi"];
	$yazar=$_POST["yazari"];
	$kategori=$_POST["kategorisi"];
	$fiyat=$_POST["fiyati"];
	$isbn=$_POST["isbn"];
	$sorgu = "INSERT INTO kitaplar (isbn, kitapadi, yazar, kategori, fiyat)
	VALUES('$isbn', '$kitapadi', '$yazar', '$kategori', $fiyat)";
	$etkilenen=$baglanti->exec($sorgu);
	if($etkilenen==1) {
		echo "Kitap Eklendi";
	}
	else {
		echo "Hata var";
	}
endif
?>
</form>
</body>
</html>