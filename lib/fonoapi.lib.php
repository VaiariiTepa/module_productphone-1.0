<?php
/**
 * Create: 25/04/18 08:44
 */

class fonoapi{
  protected $key;
  protected $url;

  // ---------------------------------------------------------------

  /**
   * fonoapi constructor
   * @param $_key
   */
  public function __construct(){
    global $conf;

    $this->url = "https://fonoapi.freshpixl.com/v1/";
    $this->key = "afb39f300992d08040dc5fc25fcf7ca4cc6d2b83c9f47622";

  }


  /**
   * Gets Device Data Object
   */
  public function getDevice($device, $brand,$position = null ){
      $url = $this->url."getdevice";
      $t_data = array();
      $postData = array(
      'device' => trim($device),
      'brand' => trim($brand),
      'position' => $position,
      'token' => $this->key
    );

    $sendPostData = json_decode($this->sendPostData($url, $postData));
    if (!isset($sendPostData->status)) {
        $t_data = $sendPostData;
    }
    return $t_data;
  }

  /**
   * Sends Post Data to the Server
   */
  function sendPostData($url, $post){
    try {
      $rUrl = null;
      if (isset($_SERVER['HTTP_HOST']) && $_SERVER['REQUEST_URI']) {
        $rUrl = "http".(!empty($_SERVER['HTTPS'])?"s":""). "://" .$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      }
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_REFERER, $rUrl);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      $result = curl_exec($ch);
      if (FALSE === $result)
        throw new Exception(curl_error($ch), curl_errno($ch));
      curl_close($ch);
      return $result;
    } catch (Exception $e) {
      $result["status"] = $e->getCode();
      $result["message"] = "Curl Failed" ;
      $result["innerException"] = $e->getMessage();
      $result["parameters"] = $post;
      $result["url"] = $url;
      return json_encode($result);
    }
  }

}
/*

USAGE
-----
$_fonoapi = new fonoapi();
$t_data = $this->getDevice('iphone x');

*/

