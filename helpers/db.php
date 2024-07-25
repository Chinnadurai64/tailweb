<?php
class Database {
    public static function connect() {
        try {
            $pdo = new PDO('pgsql:host=localhost;dbname=school', 'postgres', 'postgres');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    public static function cryptoJsAesDecrypt($passphrase, $jsonString){
		$jsondata = json_decode($jsonString);
		try {
			$salt = hex2bin($jsondata->s);
			$iv  = hex2bin($jsondata->iv);
		} catch(\Exception $e) {
            return null;
        }
		$ct = base64_decode($jsondata->ct);
		$concatedPassphrase = $passphrase.$salt;
		$md5 = array();
		$md5[0] = md5($concatedPassphrase, true);
		$result = $md5[0];
		for ($i = 1; $i < 3; $i++) {
			$md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
			$result .= $md5[$i];
		}
		$key = substr($result, 0, 32);
		$data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
		return json_decode($data, true);
	}
}
?>

