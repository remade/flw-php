<?php
namespace Remade\Flutterwave;


class Encryption{

    /**
     * 3DES encryption
     *
     * @param $clearText
     * @param $key
     * @return string
     */
    public static function encrypt3Des($clearText, $key)
    {
        //Generate a key from a hash
        $key = md5(utf8_encode($key), true);
        //Take first 8 bytes of $key and append them to the end of $key.
        $key .= substr($key, 0, 8);

        $encData = openssl_encrypt($clearText, 'DES-EDE3', $key, OPENSSL_RAW_DATA);
        return base64_encode($encData);
    }

    /**
     * 3DES Decryption
     *
     * @param $cipherText
     * @param $key
     * @return string
     */
    public static function decrypt3Des($cipherText, $key)
    {
        //Generate a key from a hash
        $_key = md5(utf8_encode($key), true);
        //Take first 8 bytes of $key and append them to the end of $key.
        $_key .= substr($_key, 0, 8);
        $data = base64_decode($cipherText);

        $decData = openssl_decrypt($data, 'DES-EDE3', $_key, OPENSSL_RAW_DATA);

        return $decData;
    }
}