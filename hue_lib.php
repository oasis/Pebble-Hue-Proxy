<?php

/* Based on code by airox at http://www.domoticaforum.eu/viewtopic.php?f=17&t=8345 */

class Hue {

   const POST = 'POST';
   const PUT = 'PUT';
   const GET = 'GET';
   
   const TYPE_BULB = 0;
   const TYPE_GRP = 1;
   
   /**
    * @var string
    */
   private $url;

   /**
    * @var string
    */
   private $username;

   private $transitionTime = 10;
   
   public function __construct($ip, $username=false) {
      $this->url = 'http://'.$ip;
      $this->username = $username;
   }
   
   public function setTransitionTime($time) {
      $this->transitionTime = $time;
   }
   
   private function callapi($url, $type, $datain='') {
      $params = array(
            'http' => array(
                  'method' => $type,
                  'content' => $datain
            )
      );

      $context = stream_context_create($params);
      $data = json_decode(file_get_contents($url, 0, $context), true);

      return $data;
   }

   public function register() {
      $data = $this->callapi($this->url.'/api', self::POST, '{"username": "'.$this->username.'", "devicetype": "'.$this->username.'"}');
      if ( isset($data[0]['success']['username']) ) {
         return $data[0]['success']['username']." is now registered with the bridge.";
      }
      return "Register failed... Did you press the button on the bridge?";
   }

   private function statechange($json, $name, $type) {
      if ( $type == self::TYPE_BULB ) {
         $url = $this->url.'/api/'.$this->username.'/lights/'.$name.'/state';
      } elseif ( $type == self::TYPE_GRP ) {
         $url = $this->url.'/api/'.$this->username.'/groups/'.$name.'/action';
      } else {
         return false;
      }
      return $this->callapi($url, self::PUT, $json);
   }
   
   public function turnon($name,$type=0) {
      return $this->statechange('{"bri":254,"transitiontime": '.$this->transitionTime.',"on":true}', $name, $type);
   }
   
   public function turnoff($name,$type=0) {
      return $this->statechange('{"on":false,"transitiontime": '.$this->transitionTime.'}', $name, $type);
   }
   
   public function dimlights($name,$type=0) {
      return $this->statechange('{"bri":35,"transitiontime": '.$this->transitionTime.',"on":true}', $name, $type);
   }
   
   public function changebri($name, $bri,$type=0) {
      return $this->statechange('{"bri":'.round($bri).', "hue":15331, "sat": 121,"transitiontime": '.$this->transitionTime.',"on":true}', $name, $type);
   }
   
   public function flashonce($name,$type=0) {
      return $this->statechange('{"alert":"select"}', $name, $type);
   }
   
   public function flashrepeat($name,$type=0) {
      return $this->statechange('{"alert":"select"}', $name, $type);
   }
   
   public function setrgb($name,$r,$g,$b, $type=0) {
      $hsv = self::rgbToHsv($r, $g, $b);
      // h, s, v
      $hsv[0] = $hsv[0]*182;
      $hsv[1] = ($hsv[1]/100)*254;
      $hsv[2] = ($hsv[2]/100)*254;
      return $this->sethsl($name, $hsv[2], $hsv[0], $hsv[1], $type);
   }
   
   public function sethsl($name, $bri, $hue, $sat, $type=0) {
      return $this->statechange('{"bri":'.round($bri).',"hue":'.round($hue).',"transitiontime": '.$this->transitionTime.', "sat":'.round($sat).', "on":true}', $name, $type);
   }
   
   public function getgroups() {
      return $this->callapi($this->url.'/api/'.$this->username.'/groups/0', self::GET);
   }
   
   public function getinfo() {
      return $this->callapi($this->url.'/api/'.$this->username, self::GET);
   }
   
   public function getstate($name,$type=0) {
      if ( $type == self::TYPE_BULB ) {
         $url = $this->url.'/api/'.$this->username.'/lights/'.$name;
      } elseif ( $type == self::TYPE_GRP ) {
         $url = $this->url.'/api/'.$this->username.'/groups/'.$name;
      } else {
         return false;
      }

      return $this->callapi($url, self::GET);

   }
   
   public static function rgbToHsv($r,$g,$b) {
      $r = $r / 255;
      $g = $g / 255;
      $b = $b / 255;

      $min = min($r, $g, $b);
      $max = max($r, $g, $b);

      $v = $max;
      $delta = $max - $min;

      if ($delta == 0) {
         return array(0, 0, $v * 100);
      }
      
      if ($max != 0) {
         $s = $delta / $max;
      } else {
         $s = 0;
         $h = -1;
         return array($h, $s, $v);
      }
      
      if ($r == $max) {
         $h = ($g - $b) / $delta;
      } else if ($g == $max) {
         $h = 2 + ($b - $r) / $delta;
      } else {
         $h = 4 + ($r - $g) / $delta;
      }
      
      $h *= 60;
      if ($h < 0) {
         $h += 360;
      }
      return array($h, $s * 100, $v * 100);
   }

}

?>