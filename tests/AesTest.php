<?php

use PhpAes\Aes;
use PHPUnit\Framework\TestCase;

class AesTest extends TestCase
{
    /**
     * @covers \PhpAes\Aes::encrypt
     * @covers \PhpAes\Aes::decrypt
     *
     * @return void
     */
    public function test()
    {
        $plaintext = "message to be encrypted  ";
        $key = 'd41d8cd98f00b204e9800998ecf8427e';

        // *** AES-256-ECB (Rijndael-128) pure PHP
        $aes = new Aes($key, 'ECB');
        $aesCipherText = $aes->encrypt($plaintext);
        $aesDecrypted = $aes->decrypt($aesCipherText);

        // *** AES-256-ECB (Rijndael-128) openssl
        $opensslCipherText = openssl_encrypt($plaintext, "AES-256-ECB", $key, OPENSSL_RAW_DATA, null);
        $opensslDecrypted = openssl_decrypt($opensslCipherText, "AES-256-ECB", $key, OPENSSL_RAW_DATA, null);

        $this->assertFalse(hash_equals($aesCipherText, $opensslCipherText));
        $this->assertTrue(hash_equals($aesDecrypted, $opensslDecrypted));
    }
}
