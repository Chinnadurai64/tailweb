<?php
class Helper {
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
    public static function validateInput($username, $password) {
        $errors = [];
        $Errorsmsg = [
            'format' => "Invalid email format.",
            'Uempty' =>"Username is required.",
            'Pempty' => "Password is required.",
            'length' => "Password must be at least 8 characters long.",
            'complexity' => "Password must contain at least one letter, one number, and one special character."
        ];
        if (empty($username)) {
            $errors[] = $Errorsmsg['Uempty'];
        } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $errors[] = $Errorsmsg['format'];
        }
        if (empty($password) || strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[\W_]/', $password)) {
            $errors[] = $Errorsmsg['Pempty'] ?? ($Errorsmsg['length'] ?? $Errorsmsg['complexity']);
        }
    
        return $errors;
    }
}
?>

