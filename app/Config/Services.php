<?php namespace Config;

use CodeIgniter\Config\BaseService;
use App\Services\AcademicCalendarService;
use App\Services\StudentPromotionService; 

class Services extends BaseService
{
    public static function academicCalendar($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('academicCalendar');
        }

        return new AcademicCalendarService();
    }

    public static function studentPromotion(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('studentPromotion');
        }

        return new StudentPromotionService();
    }

    public static function smsService($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('smsService');
        }

        return new \App\Services\SmsService();
    }
}
