<?php
namespace Net;
/**
 * multipart/form-data 请求数据对象
 */
class MultipartFormData{
    const LINE_BREAK=PHP_EOL:
    private $_fields;
    private $_boundary;
    /**
     * Constructor
     * @param array $data, data prepend. 
     */
    public function __construct($data=array()){
        $this->_fields = array();
        $this->_boundary = "--".base64_encode(uniqid(true));
        if(is_array($data)){
            foreach ($data as $key => $value) {
                $this->append($key, $value);
            }
        }
    }

    /**
     * 添加表单字段
     */
    public function append($name, $data=null, $filename=null, $metas=array()){
        // .append( MultipartFormDataField );
        if($name instanceof MultipartFormDataField){
            $this->_fields[] = $name;
            return true;
        }
        $this->_fields[] = new MultipartFormDataField($name, $data, $filename, $metas );
    }


    public function __get($key){
        switch ($key) {
            case 'boundary':
                return $this->_boundary;
            default: break;
        }
    }

    // 获取可post的表单数据
    public function getPostData(){
        $lines = array($this->boundary);
        foreach ($this->_fields as $i => $field) {
           $lines[] = $filed->getFormPartData();
        }
        $lines[] = $this->boundary;
        return implode( self::LINE_BREAK, $lines );
    }
}

/*!
 * 表单字段对象
 */
class MultipartFormDataField{
    const LINE_BREAK=PHP_EOL;

    public  $name;
    public  $filename;
    public  $data;
    public  $metas;
    private $_default_metas;


    public function __construct($name, $data=null, $filename=null, $metas=array()){
        $this->name = $name;
        $this->data = $data;
        $this->filename = $filename;
        $this->metas = is_array($metas)?$metas:array();
        $this->_default_metas = array();
    }

    /**
     * filename setter
     */
    public function filename($filename=null){
        if($filename!==null){
            $this->filename = $filename;
            return $filename;
        }
        return $this->filename;
    }

    /**
     * 设置表单字段元数据
     * set null to delete
     * .meta("Content-Type", "img/mpeg" );
     */
    public function meta($mname, $mvalue=null){
        $this->metas[$mname] = $mvalue;
    }

    /**
     * 输出为表单数据片段
     */
    public function getFormPartData(){
        $filename = "";
        if($this->filename){
            $filename = "filename=\"{$this->filename}\"";
            $this->_default_metas["Content-Type"] = "application/octet-stream";
        };
        $lines = array();
        $lines[] = "Content-Disposition: form-data; name=\"{$this->name}\"; {$filename}";
        $this->metas = array_merge($this->_default_metas, $this->metas);
        foreach($this->metas as $meta=>$value){
            $lines[] = "{$meta}: $value";
        }
        $lines[] = ""; // line holder
        $lines[] = $this->data;
        return implode( self::LINE_BREAK, $lines );
    }

    public function __toString(){ return $this->getFormPartData();  }
}

