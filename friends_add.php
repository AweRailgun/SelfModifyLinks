<?php
error_reporting(0); 

//此处需修改
$blog_address='';          //此处填写博客地址，末尾带“/”，例如https://miku.ie/
$friendlink_address='';    //此处填写博客友链地址，末尾带“/”，例如https://miku.ie/friends/
$host_ip='';               //此处填写博客IP，如果数据库和本程序在同一服务器（绝大多数情况）直接填写localhost
$mysql_username='';        //此处填写数据库账号，默认wordpress
$mysql_password='';        //此处填写数据库密码
$wp_dbname='';             //此处填写wordpress表名，默认wordpress
$smtp_host='';             //此处填写邮件SMTP服务器地址
$smtp_username='';         //此处填写SMTP用户名，一般为邮箱账号
$smtp_password='';         //此处填写SMTP密码，部分邮箱是授权码(例如163邮箱)
$smtp_secure='';           //允许 TLS 或者 ssl 协议，建议TLS
$smtp_port='';             //此处填写SMTP端口
$set_from='';              //此处填写邮箱账号
$mail_name='';             //此处填写昵称，可随便填写
$your_email='';            //此处填写你的邮箱地址，用于接受友链申请提示
$blog_intro='
<h4>本站信息</h4>
<li>Name: AweRailgun</li>
<li>Bio:爱折腾的萌新</li>
<li>URL: https://miku.ie</li>
<li>Avatar: <a href="https://cdn.jsdelivr.net/gh/awerailgun/filescdn@main/icon/me.jpg">点击获取</a></li>';       
//可选，用于友链填写完成页面展示，直接修改上述模板，如不需要直接留空

//结束

$err_msg ='';
$flag='0';
$blog_rssreply='';
$blog_name=$_POST["blog_name"];
$blog_email=$_POST["blog_email"]; 
$blog_introduce=$_POST["blog_introduce"];
$blog_url=$_POST["blog_url"];
$blog_icon=$_POST["blog_icon"];
$others=$_POST["others"];
$RSS_check=$_POST["RSS_check"];
$blog_rss=$_POST["blog_rss"];
$adv_check=$_POST["adv_check"];
$blog_set_ssl=$_POST["blog_set_ssl"];

if($_SERVER['HTTP_REFERER']!=$friendlink_address){
    $err_msg .='#非法访问！&nbsp;&nbsp;&nbsp;';
    $flag='2';
}

if($blog_name=='' && $_SERVER['HTTP_REFERER']==$friendlink_address){
    $flag='3';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './phpmailer/src/Exception.php';
require './phpmailer/src/PHPMailer.php';
require './phpmailer/src/SMTP.php';


if($flag =='0'){
    if ($others==''){
        $others='（备注为空）';
    }

    if ($RSS_check=='ture'){
        $RSS_check='有RSS';
    }
    else{
        $RSS_check='无RSS';
    }

    if ($blog_rss==''){
        $blog_rssreply='（未填写RSS地址）';
    }
    else{
        $blog_rssreply=$blog_rss;
    }

    if ($adv_check=='true'){
        $adv_check='有广告';
    }
    else{
        $adv_check='无广告';
    }

    if ($others==''){
        $others='（备注为空）';
    }

    if ($blog_set_ssl=='1'){
        $blog_set_ssl='无SSL';
    }
    elseif($blog_set_ssl=='2'){
        $blog_set_ssl='部分SSL';
    }
    elseif($blog_set_ssl=='3'){
        $blog_set_ssl='全站SSL';
    }
    $database = mysqli_connect($host_ip,$mysql_username,$mysql_password,$wp_dbname); 
    mysqli_select_db($database,$wp_dbname);
    $idmax = mysqli_fetch_row(mysqli_query($database,"SELECT link_id FROM `wp_links` ORDER BY `wp_links`.`link_id` DESC limit 0,1"));;
    $newid = $idmax[0] + 1;
    $result = mysqli_query($database,"INSERT INTO wp_links(link_id,link_url,link_name,link_image,link_target,link_description,link_visible,link_owner,link_rating,link_updated,link_rel,link_notes,link_rss) 
    VALUES('{$newid}','{$blog_url}','{$blog_name}','{$blog_icon}','','{$blog_introduce}','N','1','0','0000-00-00 00:00:00','','','{$blog_rss}')");
    if($result !=1){
        $flag='1';
        $err_msg .='#上传数据库错误&nbsp;&nbsp;&nbsp;';}
    mysqli_close($database);

    $link_manager=$blog_address.'wp-admin/link.php?action=edit&link_id='.strval($newid);
    $temp1=file_get_contents("./friends_add_mail.html");
    $temp1=str_replace('({$blog_name$})',$blog_name,$temp1);
    $temp1=str_replace('({$blog_email$})',$blog_email,$temp1); 
    $temp1=str_replace('({$blog_introduce$})',$blog_introduce,$temp1); 
    $temp1=str_replace('({$blog_url$})',$blog_url,$temp1); 
    $temp1=str_replace('({$blog_icon$})',$blog_icon,$temp1); 
    $temp1=str_replace('({$others$})',$others,$temp1); 
    $temp1=str_replace('({$RSS_check$})',$RSS_check,$temp1); 
    $temp1=str_replace('({$blog_rssreply$})',$blog_rssreply,$temp1); 
    $temp1=str_replace('({$adv_check$})',$adv_check,$temp1); 
    $temp1=str_replace('({$blog_set_ssl$})',$blog_set_ssl,$temp1); 
    $temp1=str_replace('({$link_manager$})',$link_manager,$temp1); 
    $temp1=str_replace('({$blog_address$})',$blog_address,$temp1);
    $temp3=file_get_contents("./friends_add_mail_user.html");
    $temp3=str_replace('({$blog_address$})',$blog_address,$temp3);

    $mail = new PHPMailer(true);                          
    try {
        $mail->CharSet ="UTF-8";                          
        $mail->SMTPDebug = 0;                             
        $mail->isSMTP();                                  
        $mail->Host = $smtp_host;                    
        $mail->SMTPAuth = true;                           
        $mail->Username = $smtp_username;                
        $mail->Password = $smtp_password;                   
        $mail->SMTPSecure = $smtp_secure;                        
        $mail->Port = $smtp_port;                                
        $mail->setFrom($set_from, $mail_name);    
        $mail->addAddress($your_email);    
        $mail->addReplyTo($set_from, $mail_name);      
        $mail->isHTML(false);                             
        $mail->Subject = '友链申请';
        $mail->Body    = $temp1;
        $mail->AltBody = '如果邮件客户端不支持HTML则显示此内容';
        $mail->send();
    } catch (Exception $e) {
        $flag='1';
        $err_msg .='#管理员邮件发送失败('.$mail->ErrorInfo.')';
    }

    $mail = new PHPMailer(true);                         
    try {
        $mail->CharSet ="UTF-8";                          
        $mail->SMTPDebug = 0;                             
        $mail->isSMTP();                                  
        $mail->Host = $smtp_host;                    
        $mail->SMTPAuth = true;                           
        $mail->Username = $smtp_username;              
        $mail->Password = $smtp_password;                  
        $mail->SMTPSecure = $smtp_secure;                       
        $mail->Port = $smtp_port;                               
        $mail->setFrom($set_from, $mail_name);    
        $mail->addAddress($blog_email);  
        $mail->addReplyTo($set_from, $mail_name);       
        $mail->isHTML(false);                            
        $mail->Subject = '已收到友链申请';
        $mail->Body    = $temp3;
        $mail->AltBody = '如果邮件客户端不支持HTML则显示此内容';
        $mail->send();
    } catch (Exception $e) {
        $flag='1';
        $err_msg .='#用户邮件发送失败('.$mail->ErrorInfo.')';
    }
}

if ($flag == '0' || $flag == '3'){
    $temp2 = file_get_contents("./add_success.html");
    $temp2=str_replace('({$blog_intro$})',$blog_intro,$temp2);
    echo $temp2;
}
else {
    $temp2 = file_get_contents("./add_error.html");
    $temp2=str_replace('({$blog_intro$})',$blog_intro,$temp2);
    $temp2=str_replace('({$err_msg$})',$err_msg,$temp2);
    echo $temp2;
}
?> 
