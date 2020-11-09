AES
---
**AES-256-ECB (Rijndael-128) pure PHP demo. Comparing with OpenSSL (ext-openssl).**

#### Install:
```php
composer require cast/aes
```

#### Usage:
```php
<?php
use PhpAes\Aes;

$aes = new Aes($key, 'ECB');
$aesCipherText = $aes->encrypt($plaintext);
$aesDecrypted = $aes->decrypt($aesCipherText);

```

Based on https://github.com/phillipsdata/phpaes

Links:
* https://blog.remirepo.net/post/2015/07/07/About-libmcrypt-and-php-mcrypt
* https://www.php.net/manual/ru/migration71.deprecated.php
* https://www.php.net/manual/en/function.mcrypt-encrypt.php

