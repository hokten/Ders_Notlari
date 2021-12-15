<html>
<head>
<meta charset="utf-8" />
</head>
<body>

<?php
try {
	$baglanti = new PDO("mysql:host=localhost:3308;dbname=ekitap", "root", "");
}
catch(PDOException $e) {
	echo "Bağlanamadı " . $e->getMessage();
}
$baglanti->exec("SET NAMES 'utf8'; SET CHARSET 'utf8'");
$silkayitid = $_GET['kitapid'];
$sorgu = $baglanti->prepare("DELETE FROM kitaplar WHERE id=?");
$sorgu->execute([$silkayitid]);
if($sorgu->rowCount() == 1) {
	echo "Kayıt Başarı İle Silindi";
}
else {
	echo "Kayıt Silinemedi";
}
?>
</body>
</html>