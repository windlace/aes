<?php

use PhpAes\Aes;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__.'/vendor/autoload.php';

// Don't use mcrypt, it's deprecated
// mcrypt is broken, it pads end of string with spaces, so it corrupts our string with trailed spaces.
// https://blog.remirepo.net/post/2015/07/07/About-libmcrypt-and-php-mcrypt
// @link https://www.php.net/manual/ru/migration71.deprecated.php
// @link https://www.php.net/manual/en/function.mcrypt-encrypt.php
$plaintext = "message to be encrypted  ";
$key = 'd41d8cd98f00b204e9800998ecf8427e';

// *** AES-256-ECB (Rijndael-128) pure PHP
// tested with PHP compiled without openssl and sodium, it works.
$aes = new Aes($key, 'ECB');
$aesCipherText = $aes->encrypt($plaintext);
$aesDecrypted = $aes->decrypt($aesCipherText);

// *** AES-256-ECB (Rijndael-128) openssl
$opensslCipherText = openssl_encrypt($plaintext, "AES-256-ECB", $key, OPENSSL_RAW_DATA, null);
$opensslDecrypted = openssl_decrypt($opensslCipherText, "AES-256-ECB", $key, OPENSSL_RAW_DATA, null);

// summary
$aesCipherTextHex     = bin2hex($aesCipherText);
$opensslCipherTextHex = bin2hex($opensslCipherText);
$cipherTextHashEquals = hash_equals($aesCipherText, $opensslCipherText) ? 'true' : 'false';
$decryptedHashEquals  = hash_equals($aesDecrypted, $opensslDecrypted) ? 'true' : 'false';

/*

*** AES-256-ECB ***
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
plain text:                 message to be encrypted
key:                        d41d8cd98f00b204e9800998ecf8427e
AES CipherTextHex:          fed85dbcc47e2d5a6d3776e649f749838333e05b58e26173cadd3415813f4860
openssl CipherTextHex:      fed85dbcc47e2d5a6d3776e649f749838333e05b58e26173cadd3415813f4860
cipherText HashEquals:      false - it's fine cause openssl adds it's own EVP_BytesToKey (16 bytes), but pure AES not requires it.
decrypted HashEquals:       true

*/

header('Content-Type: text/plain');
echo <<<END_OF_SEQ

*** AES-256-ECB ***
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
plain text:                                     {$plaintext}
key:                                            {$key}
AES CipherTextHex:                              {$aesCipherTextHex}
openssl CipherTextHex (with EVP_BytesToKey):    {$opensslCipherTextHex}
cipherText HashEquals:                          {$cipherTextHashEquals}
decrypted HashEquals:                           {$decryptedHashEquals}

END_OF_SEQ;

