<html>
<head>
<meta charset="utf-8" />
</head>
<body>
<?php

try {
	$baglanti = new PDO("mysql:host=localhost:3308;dbname=ekitap", "root", "");
	echo "Bağlandı";
}
catch(PDOException $e) {
	echo "Bağlanamadı " . $e->getMessage();
}
$baglanti->exec("SET NAMES 'utf8'; SET CHARSET 'utf8'");
$sorgu = $baglanti->query("SELECT * FROM kitaplar", PDO::FETCH_ASSOC);
echo '<table border="1">';
echo '<tr><th>ISBN</th><th>Kitap Adı</th><th>Yazar</th><th>Kategori</th><th>Olay</th></tr>';
while($satir = $sorgu->fetch()) {
	echo '<tr>';
	echo '<td>' . $satir["isbn"] . '</td>';
	echo '<td>' . $satir["kitapadi"] . '</td>';
	echo '<td>' . $satir["yazar"] . '</td>';
	echo '<td>' . $satir["kategori"] . '</td>';
	echo "<td><a href=\"sil.php?kitapid={$satir['id']}\">SİL</a></td>";
	echo "</tr>";
}
echo "</table>";
?>
</body>
</html>