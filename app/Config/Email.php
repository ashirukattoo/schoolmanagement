<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail = 'ashirukattoo@gmail.com';
    public string $fromName  = 'LSS Management System';

    public string $protocol  = 'smtp';

    public string $SMTPHost  = 'smtp.gmail.com';
    public string $SMTPUser  = 'ashirukattoo@gmail.com';

    // 👇 PASTE APP PASSWORD YAKO HAPA (BILA SPACE)
    public string $SMTPPass  = 'euthgikejhivcayj';

    public int    $SMTPPort  = 587;
    public string $SMTPCrypto = 'tls';

    public string $mailType = 'html';
    public string $charset  = 'UTF-8';

    public string $newline  = "\r\n";
    public string $CRLF     = "\r\n";
}
