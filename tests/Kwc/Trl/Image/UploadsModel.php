<?php
class Kwc_Trl_Image_UploadsModel extends Kwf_Test_Uploads_Model
{
    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->createRow()->copyFile(dirname(__FILE__).'/1.jpg', '1', 'jpg', 'image/jpeg');
        $this->createRow()->copyFile(dirname(__FILE__).'/2.jpg', '2', 'jpg', 'image/jpeg');
    }
}
