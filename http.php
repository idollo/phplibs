<?php

class Http{
    const OPT_METHOD_POST="post";
    const OPT_METHOD_GET="get";


}

class HttpRequest{
    public $header;
    public $cookie;
    public $method;
    public $data;

    public function send($data){

    }

    public function request($url, $options=array()){
        $ch = curl_init();

    }

    public function get($url){

    }

    public function post($url, $data=array()){
        return $this->request($url)
    }

    /**
     * 上传multipart/form-data数据
     * 可上传文件, @see MultipartFormData
     */
    public function postMultipartFormData($url, MultipartFormData $formdata){
        // Content-Length  934424
        // Content-Type    multipart/form-data; boundary=---------------------------1797812992748043254993700440
        $this->setHeader("Content-Type","multipart/form-data; boundary={$formdata->boundary}");
        // $this->header("Content-Length", $formdata->dataLength );
        return $this->post( $formdata->getPostData() );
    }

    // 设置cookie
    public function cookie($name,$value, $options=array()){ }
    // 设置userAgent
    public function userAgent(){}
}





class HttpResponse{
    private $header;
    private $response;
    private $cookie;
    private $status;
}

?>