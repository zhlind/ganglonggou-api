<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/17
 * Time: 15:08
 */

namespace app\api\service;


use PHPMailer\PHPMailer;

class Email
{
    public function setEmail($head,$body){

        $mail = new PHPMailer();
        $toemail = '382506262@qq.com';//收件人
        $mail->addAddress("3001374619@qq.com");
        $mail->addAddress("3001397358@qq.com");
        $mail->addAddress("3001306821@qq.com");
        $mail->addAddress("811718475@qq.com");
        $mail->addAddress("987303897@qq.com");
        $mail->isSMTP();// 使用SMTP服务
        $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = "smtp.163.com";// 发送方的SMTP服务器地址
        $mail->SMTPAuth = true;// 是否使用身份验证
        $mail->Username = "13890605917@163.com";/// 发送方的163邮箱用户名，就是你申请163的SMTP服务使用的163邮箱
        $mail->Password = "Li15183322241";// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！
        $mail->SMTPSecure = "ssl";// 使用ssl协议方式
        $mail->Port = 465;// 163邮箱的ssl协议方式端口号是465/994

        $mail->setFrom("13890605917@163.com","岗隆数码自营商城");// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示
        $mail->addAddress($toemail,'管理员');// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)
        $mail->addReplyTo("13890605917@163.com","系统");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
        //$mail->addCC("xxx@163.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)
        //$mail->addBCC("xxx@163.com");// 设置秘密抄送人(这个人也能收到邮件)
        //$mail->addAttachment("bug0.jpg");// 添加附件

        $mail->Subject = $head;// 邮件标题

        $mail->Body = $body;// 邮件正文

        //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用

        $mail->send();
        /* if(!$mail->send()){// 发送邮件
             发生错误
         }else{
             成功
         }*/
    }
}