<?php
use Poncho\Database\Database;
include('vendor/autoload.php');
?>
<form action="?section=homepage" method="POST">
    <input type="number" name='days'>
    <input type="submit" name='submit'>
</form>
<?php
$days = isset($_POST['submit']) ? $_POST['days'] : 7;
echo "<h1>Politiciens ".$days." derniers jours </h1>";
$db = new Database();

$sql = 
'SELECT JSON_OBJECT(
			"firstname", p.firstname,
			"lastname", p.lastname,
			"count", count(p.lastname),
            "descriptions", GROUP_CONCAT(m.description SEPARATOR ";;;"),
            "idMedia", GROUP_CONCAT(m.idMedia SEPARATOR ";;;")
		) as politicsName
FROM media m
INNER JOIN medpol mp ON m.idMedia = mp.fk_media 
INNER JOIN politicians p ON p.idPol = mp.fk_pol 
WHERE p.idPol = mp.fk_pol and m.date > CURDATE() - INTERVAL '.$days.' DAY
GROUP BY p.lastname
HAVING count(p.lastname) > 1
order by count(p.lastname) DESC
';
$politiciansByMedia = [];
$stmt = $db->getQuery($sql);
$arrayobj = new ArrayObject();
$out;
foreach ($stmt->fetchAll() as $row) {
    $arrayobj->append(json_decode($row->politicsName));
    // preg_match_all('/".*?"/', $row->descriptions, $row->descriptions);
    // $sayArray = explode(";;;", $row->descriptions);


}
foreach ($arrayobj as $key) {
    preg_match_all('/".*?"/', $key->descriptions, $out);
    $key->descriptions = [];
    foreach ($out[0] as $value) {
        array_push($key->descriptions, $value);
    }
}

foreach ($arrayobj as $key => $value) {
    echo $value->lastname . "<br>";
    var_dump($value->idMedia);
    foreach ($value->descriptions as $key) {
        echo $key . "<br>";
    }
    echo "<hr>";
}

?>