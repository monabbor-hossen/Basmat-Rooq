<?php
class Translator {
    public function getTranslation($lang) {
        $data = [
            'en' => [
                'hero_title'    => 'ROOQFLOW',
                'hero_desc'     => 'Digitizing Saudi Investment Portfolios for global stakeholders.',
                'about_title'   => 'What is Basmat Rooq?',
                'about_text'    => 'Basmat Rooq Company Limited is a specialized consultancy based in Unaizah, Al-Qassim, dedicated to digitizing the manual tracking of Saudi Ministry of Investment (MISA) licenses.',
                'what_we_do'    => 'What We Do',
                'service_desc'  => 'We provide real-time monitoring of government milestones including MISA, SBC, GOSI, QIWA, and Muqeem for active portfolios like Jahangir Contracting and Fonon.',
                'contact_info'  => 'Contact Us',
                'email'         => 'Kh70007980@gmail.com',
                'location'      => 'Unaizah, Al-Qassim',
                'login'         => 'Access Portal',
                'status'        => 'License Intelligence'
            ],
            'ar' => [
                'hero_title'    => 'بصمة روق',
                'hero_desc'     => 'رقمنة محافظ الاستثمار السعودي لأصحاب المصلحة العالميين.',
                'about_title'   => 'ما هي بصمة روق؟',
                'about_text'    => 'شركة بصمة روق المحدودة هي شركة استشارية متخصصة مقرها في عنيزة، القصيم، مكرسة لرقمنة التتبع اليدوي لتراخيص وزارة الاستثمار السعودية (MISA).',
                'what_we_do'    => 'ماذا نفعل',
                'service_desc'  => 'نحن نقدم مراقبة في الوقت الفعلي للمعايير الحكومية بما في ذلك MISA و SBC و GOSI و QIWA و Muqeem للمحافظ النشطة.',
                'contact_info'  => 'اتصل بنا',
                'email'         => 'Kh70007980@gmail.com',
                'location'      => 'عنيزة، القصيم',
                'login'         => 'دخول البوابة',
                'status'        => 'ذكاء التراخيص'
            ]
        ];
        return $data[$lang] ?? $data['en'];
    }
}