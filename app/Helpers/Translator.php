<?php
// app/Helpers/Translator.php
class Translator {
    public function getTranslation($lang) {
        $data = [
            'en' => [
                'hero_title' => 'ROOQFLOW',
                'hero_tagline' => 'Digitalizing Saudi Investment Tracking',
                'about_title' => 'About Basmat Rooq',
                'about_text' => 'Basmat Rooq Company Limited, based in Unaizah, Al-Qassim, is a specialized firm dedicated to digitizing the tracking of Saudi Ministry of Investment (MISA) licenses. we provide global investors with real-time transparency and efficiency.',
                'services_title' => 'What We Do',
                'services_text' => 'We streamline complex government workflows into a single digital dashboard. From MISA applications to GOSI, QIWA, and Muqeem integrations, we manage the 10 core milestones of your business setup in the Kingdom.',
                'tracking_title' => 'Live Intelligence',
                'contact_title' => 'Get In Touch',
                'location' => 'Unaizah, Al-Qassim, KSA',
                'email' => 'Kh70007980@gmail.com',
                'login' => 'Client Portal',
                'milestone_status' => 'Status'
            ],
            'ar' => [
                'hero_title' => 'بصمة روق',
                'hero_tagline' => 'رقمنة تتبع الاستثمار السعودي',
                'about_title' => 'عن بصمة روق',
                'about_text' => 'شركة بصمة روق المحدودة، ومقرها في عنيزة، القصيم، هي شركة متخصصة مكرسة لرقمنة عملية تتبع تراخيص وزارة الاستثمار السعودية (MISA). نحن نوفر للمستثمرين العالميين الشفافية والكفاءة في الوقت الفعلي.',
                'services_title' => 'ماذا نفعل',
                'services_text' => 'نحن نقوم بتبسيط سير العمل الحكومي المعقد في لوحة تحكم رقمية واحدة. من طلبات MISA إلى تكاملات GOSI و QIWA و Muqeem، نحن ندير المعالم العشرة الأساسية لتأسيس عملك في المملكة.',
                'tracking_title' => 'الذكاء المباشر',
                'contact_title' => 'تواصل معنا',
                'location' => 'عنيزة، القصيم، المملكة العربية السعودية',
                'email' => 'Kh70007980@gmail.com',
                'login' => 'بوابة العملاء',
                'milestone_status' => 'الحالة'
            ]
        ];
        return $data[$lang] ?? $data['en'];
    }
}