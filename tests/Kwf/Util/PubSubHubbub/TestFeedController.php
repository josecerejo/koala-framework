<?php
class Kwf_Util_PubSubHubbub_TestFeedController extends Kwf_Controller_Action
{
    public function indexAction()
    {
        $f = '/tmp/feedRequested'.(int)$this->_getParam('id');
        file_put_contents($f, file_get_contents($f)+1);

        $c = file_get_contents('/tmp/feed'.(int)$this->_getParam('id'));

        //file_put_contents('log/feedFetch'.date('H:i:s').uniqid(), print_r($_SERVER, true));

        if ($this->_getParam('etlm')) {
            Kwf_Media_Output::output(array(
                'contents' => $c,
                'mimeType' => 'application/atom+xml',
                'etag' => md5($c),
                'mtime' => time()-rand(0, 100000),
                'lifetime' => false
            ));
        } else {
            Kwf_Media_Output::output(array(
                'contents' => $c,
                'mimeType' => 'application/atom+xml',
                'lifetime' => false
            ));
        }
    }
}
