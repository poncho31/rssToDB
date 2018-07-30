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
            "say", GROUP_CONCAT((m.description REGEXP("/.a/")) SEPARATOR ";;;")
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
foreach ($stmt->fetchAll() as $row) {
	$arrayobj->append(json_decode($row->politicsName));
}
foreach ($arrayobj as $key) {
    // echo "<pre>";
	echo $key->lastname . "<br>";
	echo $key->firstname . "<br>";
	echo $key->count . "<br>";
    var_dump($key->say) . "<hr>";
    // // var_dump($key->say);
    // $sayArray = explode(";;;", $key->say);
    // $out = [];
    // // $key->say = [];
    // foreach ($sayArray as $sayKey) {
    //     preg_match_all('/".*?"/', $sayKey, $out);
    //     // $key->say = $out;
    //     // var_dump($out);
    //     foreach ($out as $key => $value) {
    //         if(!empty($key)) { echo $value;}
    //     }
    // }
    // // var_dump($key->say);
    echo "<hr>";
}
var_dump($arrayobj);

?>