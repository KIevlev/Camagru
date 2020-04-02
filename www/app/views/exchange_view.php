<?php
class Exchange_View
{
    public function push_img($img)
    {
        header("Content-type: image/jpeg");
        echo $img;
    }
}