<?php
  class Renderer {
    const size = 2048;

    private $render_data;
    private $textures;
    private $image;
    private $texture_cache;
    
    public function __construct($render_data, $textures) {
      $this->render_data = json_decode($render_data);
      $this->textures = $textures;
      $this->texture_cache = array();
    }

    public function render() {
      $this->image = self::create_transparent_image();

      foreach ($this->render_data->faces as $face) {
        $this->translate_face($face);

        $face_image = self::imagetranslatedtexture(self::create_transparent_image(), $face->texture_transformation,
          $this->load_png($this->textures[$face->texture_id]));

        if(isset($face->image_light)) {
          $face_image = self::image_light($face_image, $face->image_light);
        }

        $this->apply_mask($face->mask, $face_image);


        imagecopy($this->image, $face_image, 0, 0, 0, 0, self::size, self::size);

        imagedestroy($face_image);
      }

      return $this->image;
    }

    private function translate_face(&$face) {
      foreach($face->texture_transformation as &$transformation) {
        $this->translate_coordinates($transformation);
      }

      foreach($face->mask->data as &$mask_data) {
        $this->translate_coordinates($mask_data);
      }
    }

    private function translate_coordinates(&$coordinates) {
      // TODO
    }

    private function create_transparent_image() {
      $image = imagecreatetruecolor(self::size, self::size);
    
      // transparent background
      imagealphablending($image, false);
      imagesavealpha($image, true);
      $trans = imagecolorallocatealpha($image, 0, 0, 0, 127);
      imagefill($image, 0, 0, $trans);

      return $image;
    }

    private function load_png($texture) {
      $texture = strtolower($texture);

      if(isset($this->texture_cache[$texture])) {
        return $this->texture_cache[$texture];
      }
      
      if(strpos($texture, ":") === false) {
        $modid = "minecraft";
      } else {
        list($modid, $texture) = explode(":", $texture);
      }
      
      // Attempt to open
      if(strpos($texture, ";") === false) {
        $type = "";  
      } else {    
        list($type, $texture) = explode(";", $texture);
      }
      
      switch($type) {
      case "block":
        $im = @imagecreatefrompng("../images/$modid/blocks/$texture.png");
        break;
      case "item":
        $im = @imagecreatefrompng("../images/$modid/items/$texture.png");
        break;
      default:
        $im = @imagecreatefrompng("../images/$modid/blocks/$texture.png");
        if(!$im) $im = @imagecreatefrompng("../images/$modid/items/$texture.png");
      }
      
      // See if it failed
      if(!$im)
      {
        // Create the missing texture texture
        $im  = imagecreatetruecolor(2, 2);
        $tc  = imagecolorallocate($im, 255, 0, 255);
        
        imagesetpixel($im, 0, 0, $tc);
        imagesetpixel($im, 1, 1, $tc);
      }

      $this->texture_cache[$texture] = $im;     
      return $im;
    }

    private function imagetranslatedtexture($im, $coords, $texture) {
      $size_x = imagesx($texture);
      $size_y = imagesy($texture);
      
      $dx1 = ($coords[1][0] - $coords[0][0]) / $size_x;
      $dy1 = ($coords[1][1] - $coords[0][1]) / $size_x;
      
      $dx2 = ($coords[2][0] - $coords[0][0]) / $size_y;
      $dy2 = ($coords[2][1] - $coords[0][1]) / $size_y;
      
      $xpos = $coords[0][0];
      
      for($x = 0; $x < $size_x; $x++) {
        $ypos = $coords[0][1] + ($x * $dy1);
        
        for($y = 0; $y < $size_y; $y++) {
          imagefilledpolygon($im, array(
                $xpos,               $ypos,
                $xpos + $dx1,        $ypos + $dy1,
                $xpos + $dx1 + $dx2, $ypos + $dy1 + $dy2,
                $xpos + $dx2,        $ypos + $dy2
            ), 4, imagecolorat($texture, $x, $y));
          
          $xpos += $dx2;
          $ypos += $dy2;
        }
        
        $xpos += $dx1 - ($size_y * $dx2);
      }
    }
    
    // TODO handle transparency
    private function imagelight($im, $light) {
      $light = min(255, max(0, $light));
      
      if($light < 128) {
        $color = imagecolorallocatealpha($im, 0, 0, 0, $light);
      } else {
        $color = imagecolorallocatealpha($im, 255, 255, 255, 255 - $light);
      }
      
      imagefilledrectangle($im, 0, 0, imagesx($im), imagesy($im), $color);
      
      return $im;
    }

    private function apply_mask($mask_data, &$image) {
      switch($mask_data->type) {
        "none":
          break;
        // TODO more mask types
      }
    }
  }
?>
