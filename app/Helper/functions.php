<?php

/**
 * 设置图片路径
 * @author  yy
 * @date 2018/7/26
 * @param $img 图片路径
 * @param $flag 1 后台上传图片 2前台上传图片
 * @param $type 默认设置用户图像 ，>0 设置其他图像
 */
function showImage($img , $flag , $type = 0){
    if(!empty($img)){
        if($flag > 1){
            $img = "http://www.think.cn/upload/".$img;
        }else{
            $img = "http://www.blog.cn/upload/".$img;
        }
    }elseif($type == 0){
        return "//cdn.9dcj.com/aliyun/2018-07-31/5b601452b87f1.png";
    }

    return $img;
}