<?php
// modified by elfo
class StackImage
{
	private $image;
	private $width;
	private $height;
	
	public function __construct($Path)
	{
		if(!isset($Path) || !file_exists($Path))
			return;
		$this->image = imagecreatefrompng($Path);
		imagesavealpha($this->image, true);
		imagealphablending($this->image, true);
		$this->width = imagesx($this->image);
		$this->height = imagesy($this->image);
	}
	
	public function AddLayer($Path)
	{
		if(!isset($Path) || !file_exists($Path))
			return;
		$new = imagecreatefrompng($Path);
		imagesavealpha($new, true);
		imagealphablending($new, true);
		imagecopy($this->image, $new, 0, 0, 0, 0, imagesx($new), imagesy($new));
	}

  public function GetImage()
  {
    return $this->image;
  }
	
	public function Output($type = "image/png")
	{
		header("Content-Type: {$type}");
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
  public function CropImage() {
    // preserve transparency
    $transparent = imagecolorallocatealpha($this->image, 0, 0, 0, 127);
    imagefill($this->image, 0, 0, $transparent);
    // crop image
    $this->image = imagecrop($this->image, ['x' => 50, 'y' => 30, 'width' => 100, 'height' => 175]);
  }
}
