<?php

/**
 * 文件操作类
 * Class File
 */
class File
{
    private $path;

    /**
     * 
     * @param [type] $path [description]
     */
    public function __construct($path)
    {
        $this->path=$path;
    }

    /**
     * 获取生成的文件目录
     * @return [type] [description]
     */
    public function getList($path='')
    {
        if(empty($path)){
            $path=$this->path;
        }

        $arr=scandir($path);
        
        return array_filter($arr,function($var){
            if($var=='.' || $var == '..'||$var == 'index.html'){
                return false;
            }
            return true;
        });
    }

    /**
     * 删除缓存文件(所有)
     */
    public function removeAll()
    {
        $dirname=$this->path;
        $this->removeRun($dirname);   
        mkdir ($dirname,0777,true);
		file_put_contents($dirname.'index.html','');
    }

    /**
     * 删除缓存文件(单个)
     * @param $dir
     */
    public function remove($dir)
    {
        $dirname=$this->path.$dir;

        $this->removeRun($dirname);
    }

    /**
     * 递归删除文件和文件夹
     * @return [type] [description]
     */
    private function removeRun($dirname)
    {
        
        if(is_dir($dirname)){
            
            $temp_dir=opendir($dirname);
            
            while($filename = readdir($temp_dir)){
                if($filename != "." && $filename != ".." ){
                    $temp = $dirname."/".$filename;
                   
                    if(is_dir($temp)){
                        $temp_fun=__FUNCTION__;
                        $this->$temp_fun($temp);
                    }else{
                        unlink($temp);
                    }
                }
            }
            closedir($temp_dir);
            rmdir($dirname);
        }
    }


    /**
     * 获取数据字典列表
     * @param $dir
     * @return array
     */
    public function getFileList($dir)
    {
        $path=$this->path.$dir;
        
        $data=$this->getList($path);
        
        usort($data,function($a,$b) use ($path){

            $a_time=filemtime($path.'/'.$a);
            $b_time=filemtime($path.'/'.$b);

            if ($a_time == $b_time) {
                    return 0;
            }
            return ($a_time > $b_time) ? -1 : 1;

        });
        return $data;
    }

    /**
     * 获取对应文件列表
     * @param $file
     * @return mixed
     */
    public function getFileData($file)
    {
        $path=$this->path.$file;

        $content    = file_get_contents($path);
        $content    = substr($content, 16);
        return unserialize($content);
    }
}