<?php

function sendMail($to, $subject, $content) {
    vendor('PHPMailer.class#PHPMailer');
    $mail = new PHPMailer();
    // 装配邮件服务器
    $mail->IsSMTP();
    $mail->Host = 'smtp.qq.com';
    $mail->SMTPAuth = 1;
    $mail->Username = 'admin@opihome.me';
    $mail->Password = 'jomufwlrnmimbeaf';
    $mail->SMTPSecure = 'tls';
    $mail->CharSet = 'utf-8';
    // 装配邮件头信息
    $mail->From = 'admin@opihome.me';
    $mail->AddAddress($to);
    $mail->FromName = '生活助手';
    $mail->IsHTML(1);
    // 装配邮件正文信息
    $mail->Subject = $subject;
    $mail->Body = $content;
    // 发送邮件
    if (!$mail->Send()) {
        return FALSE;
    } else {
        return TRUE;
    }
}