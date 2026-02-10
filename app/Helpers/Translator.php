<?php
// app/Helpers/Translator.php

class Translator {
    public function getTranslation($lang) {
        $translations = [
            'en' => [
                'dashboard' => 'Dashboard',
                'services'  => 'MISA Services',
                'hero_desc' => 'Transforming Saudi investment tracking into a seamless digital journey.',
                'current_status' => 'Track Your Progress',
                
                'milestones'=> 'Tracking Milestones',
                'contact'   => 'Contact Us',
                'logout'    => 'Logout',
                'login'     => 'Log in'
                
            ],
            'ar' => [
                'dashboard' => 'لوحة التحكم',
                'services'  => 'خدمات ميزة',
                'hero_desc' => 'تحويل تتبع الاستثمار السعودي إلى رحلة رقمية سلسة.',
                'current_status' => 'تتبع تقدمك',
                
                'milestones'=> 'مراحل التتبع',
                'contact'   => 'اتصل بنا',
                'logout'    => 'تسجيل الخروج',
                'login'     => 'تسجيل الدخول'
            ]
        ];

        return $translations[$lang] ?? $translations['en'];
    }
}