<?php
class Translator {
    public function getTranslation($lang) {
        $translations = [
            'en' => [
                'hero_title'     => 'ROOQFLOW',
                'hero_desc'      => 'Digitizing Saudi Investment Portfolios for global stakeholders.',
                'current_status' => 'License Intelligence',
                'login'          => 'Access Portal',
                'services'       => 'MISA Services',
                'milestones'     => 'Tracking Milestones'
            ],
            'ar' => [
                'hero_title'     => 'بصمة روق',
                'hero_desc'      => 'رقمنة محافظ الاستثمار السعودي لأصحاب المصلحة العالميين.',
                'current_status' => 'ذكاء التراخيص',
                'login'          => 'دخول البوابة',
                'services'       => 'خدمات ميزة',
                'milestones'     => 'مراحل التتبع'
            ]
        ];
        return $translations[$lang] ?? $translations['en'];
    }
}