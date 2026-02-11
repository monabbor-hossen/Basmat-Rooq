<?php
class Translator {
    public function getTranslation($lang) {
        $data = [
            'en' => [
                'hero_title'     => 'Basmat Rooq',
                'hero_desc'      => 'Digitizing the Saudi Investment Journey.',
                'about_us'       => 'About Us',
                'about_desc'     => 'Basmat Rooq Company Limited is a premier consultancy based in Unaizah, Al-Qassim. We specialize in navigating the complexities of the Saudi Ministry of Investment (MISA) landscape.',
                'what_we_do'     => 'Our Services',
                'service_1'      => 'MISA License Processing',
                'service_1_desc' => 'End-to-end management of service licenses and investor permits.',
                'service_2'      => 'Digital Tracking',
                'service_2_desc' => 'Real-time monitoring of GOSI, QIWA, Muqeem, and SBC milestones.',
                'service_3'      => 'Legal Compliance',
                'service_3_desc' => 'Facilitating Articles of Association (AoA) and Chamber of Commerce registrations.',
                'contact_us'     => 'Get In Touch',
                'email_label'    => 'Email',
                'location_label' => 'Headquarters',
                'location_val'   => 'Unaizah, Al-Qassim, KSA',
                'login'          => 'Access Portal'
            ],
            'ar' => [
                'hero_title'     => 'بصمة روق',
                'hero_desc'      => 'رقمنة رحلة الاستثمار السعودي.',
                'about_us'       => 'من نحن',
                'about_desc'     => 'شركة بصمة روق المحدودة هي شركة استشارية رائدة مقرها في عنيزة، القصيم. نحن متخصصون في تتبع وتسهيل إجراءات وزارة الاستثمار السعودية (MISA).',
                'what_we_do'     => 'خدماتنا',
                'service_1'      => 'معالجة تراخيص MISA',
                'service_1_desc' => 'إدارة شاملة لتراخيص الخدمات وتصاريح المستثمرين.',
                'service_2'      => 'التتبع الرقمي',
                'service_2_desc' => 'مراقبة فورية لمراحل التأمينات (GOSI)، قوى (QIWA)، ومقيم.',
                'service_3'      => 'الامتثال القانوني',
                'service_3_desc' => 'تسهيل عقود التأسيس (AoA) واشتراكات الغرفة التجارية.',
                'contact_us'     => 'اتصل بنا',
                'email_label'    => 'البريد الإلكتروني',
                'location_label' => 'المقر الرئيسي',
                'location_val'   => 'عنيزة، القصيم، المملكة العربية السعودية',
                'login'          => 'دخول البوابة'
            ]
        ];
        return $data[$lang] ?? $data['en'];
    }
}