<?php
namespace App\Interfaces;
interface ImageRepositoryInterface {
    public function upload_image($base64_image,$image_path);

}
