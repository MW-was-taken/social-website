<?php
class StackImage
{
  private $image;
  private $width;
  private $height;

  public function __construct($Path)
  {
    if (!isset($Path) || !file_exists($Path)) {
      // return error image
      header("Content-Type: image/png");
      imagepng(imagecreatefrompng('../cdn/no-file.png'));
    }
    $this->image = imagecreatefrompng($Path);
    imagesavealpha($this->image, true);
    imagealphablending($this->image, true);
    $this->width = imagesx($this->image);
    $this->height = imagesy($this->image);
  }

  public function AddLayer($Path)
  {
    if (!isset($Path) || !file_exists($Path)) {
      // return error image
      header("Content-Type: image/png");
      imagepng(imagecreatefrompng('../cdn/no-file.png'));
    }

    $new = imagecreatefrompng($Path);
    imagesavealpha($new, true);
    imagealphablending($new, true);
    imagecopy($this->image, $new, 0, 0, 0, 0, imagesx($new), imagesy($new));
  }

  public function Output($type = "image/png")
  {
    header("Content-Type: {$type}");
    
    $this->CropImage();

    imagepng($this->image);
    imagedestroy($this->image);
  }

  public function GetWidth()
  {
    return $this->width;
  }

  public function GetHeight()
  {
    return $this->height;
  }
  public function CropImage()
  {

    $this->image = imagecrop($this->image, ['x' => 25, 'y' => 6, 'width' => 150, 'height' => 200]);
    $transparent = imagecolorallocatealpha($this->image, 0, 0, 0, 127);
    imagecolortransparent($this->image, $transparent);
    imagefill($this->image, 0, 0, $transparent);
    imagealphablending($this->image, false);
    imagesavealpha($this->image, true);

  }
  public function Error($path)
  {
    header("Content-Type: image/png");
    imagepng(imagecreatefrompng($path));
    exit();
  }
}
