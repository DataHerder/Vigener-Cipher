<?php 
include ('VigenerCipher.php');
?>
<!DOCTYPE html>
<html>
<head>
<title>Viginer Cipher</title>
</head>
<body>
<?php 
$Vc = new VigenerCipher('anykeyyouwish[]');

$string = 'Encrypted data gets changed here.';
print "Original String: ".$string.'<br />';

$encrypt = $Vc->encrypt($string);
print 'Encrypted: '.$encrypt.'<br />';

$decrypt = $Vc->decrypt($encrypt);
print 'Decrypted: '.$decrypt.'<br />';

?>
</body>
</html>
