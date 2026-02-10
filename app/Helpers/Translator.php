<?php
// app/Helpers/Translator.php

class Translator {
    public function getTranslation($lang) {
        $translations = [
            'en' => [
                'dashboard' => 'Dashboard',
                'services'  => 'MISA Services',
                'milestones'=> 'Tracking Milestones',
                'contact'   => 'Contact Us',
                'logout'    => 'Logout'
            ],
            'ar' => [
                'dashboard' => 'لوحة التحكم',
                'services'  => 'خدمات ميزة',
                'milestones'=> 'مراحل التتبع',
                'contact'   => 'اتصل بنا',
                'logout'    => 'تسجيل الخروج'
            ]
        ];

        return $translations[$lang] ?? $translations['en'];
    }
}