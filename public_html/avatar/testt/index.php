<?php
class Thumbnail {
    protected $image;
    
    public function __construct() {
        $this->image = imagecreatetruecolor(122, 274);
        imagefill($this->image, 0, 0, imagecolorallocatealpha($this->image, 0, 0, 0, 127));
        imagesavealpha($this->image, true);
    }
    
    public function addImage(string $path) {
        imagecopy($this->image, imagecreatefrompng($path), 0, 0, 0, 0, 122, 274);
    }
    
    public function addItem(int $file) {
        $this->addImage($_SERVER['DOCUMENT_ROOT'] . "/assets/images/shop/avatar_images/{$file}.png");
    }
    
    public function addLimb(string $limb, string $color) {
        $this->addImage(__DIR__ . "/avatar/{$limb}/{$color}.png");
    }
    
    public function save(int $file) {
        imagepng($this->image, $_SERVER['DOCUMENT_ROOT'] . "/assets/images/avatars/{$file}.png");
    }
  
  	public function deleteImage($img) {
      	if($img!='/assets/images/avatar.png'){unlink(__DIR__ . $img);}
    }
}

$image = new Thumbnail;
//Body Parts $image->addLimb('head', 'yellow');
//Body Parts $image->addLimb('torso', 'black');
//Body Parts $image->addLimb('left_arm', 'yellow');
//Body Parts $image->addLimb('right_arm', 'yellow');
//Body Parts $image->addLimb('left_leg', 'black');
//Body Parts $image->addLimb('right_leg', 'black');
//Add item $image->addItem('epicface');
//Add item $image->addItem('cap');
//$image->addItem('sign');
//$image->save('avatar'); Saves Avatar
?>