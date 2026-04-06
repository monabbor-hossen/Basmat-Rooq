<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../app/Auth/AutoLogin.php';
checkAutoLogin();
$root = dirname(__DIR__);

// Load Core Dependencies
require_once $root . '/app/Config/Config.php';
require_once $root . '/app/Helpers/Translator.php';

// Language Switching Logic
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    $gLang = $_GET['lang'] === 'ar' ? '/en/ar' : '/en/en';
    setcookie('googtrans', $gLang, time() + (86400 * 30), '/'); // Sync Google Translate with PHP session
}
$lang = $_SESSION['lang'] ?? 'en';
$isRTL = ($lang === 'ar');

// Initialize Translation Helper
$translator = new Translator();
$text = $translator->getTranslation($lang);

$_isLandingPage = isset($isLandingPage) && $isLandingPage;
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $isRTL ? 'rtl' : 'ltr'; ?>" <?php if ($_isLandingPage)
              echo ' class="scroll-smooth"'; ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if ($_isLandingPage): ?>
        <title>Fayenor | Premier Gateway to the Saudi Market</title>

        <!-- External Assets -->
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/theme.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <!-- Google Translate Integration (Hidden UI) -->
        <style>
            .goog-te-banner-frame,
            .VIpgJd-ZVi9od-aZ2wEe-wOHMyf,
            .VIpgJd-ZVi9od-ORHb-OEVmcd,
            #goog-gt-tt {
                display: none !important;
            }

            html,
            body {
                top: 0px !important;
                position: static !important;
            }

            .goog-text-highlight {
                background-color: transparent !important;
                box-shadow: none !important;
            }
        </style>
        <script>
            // Aggressive script to visually hide the Google Translate banner without breaking its API
            document.addEventListener("DOMContentLoaded", () => {
                const observer = new MutationObserver(() => {
                    const iframes = document.querySelectorAll('iframe.goog-te-banner-frame, iframe.skiptranslate');
                    iframes.forEach(iframe => {
                        iframe.style.setProperty('display', 'none', 'important');
                        iframe.style.setProperty('visibility', 'hidden', 'important');
                        iframe.style.setProperty('height', '0px', 'important');
                        iframe.style.setProperty('width', '0px', 'important');
                        iframe.style.setProperty('border', 'none', 'important');
                    });
                    if (document.body && document.body.style.top !== '0px') {
                        document.body.style.setProperty('top', '0px', 'important');
                        document.body.style.setProperty('position', 'static', 'important');
                    }
                });
                observer.observe(document.documentElement, { attributes: true, childList: true, subtree: true });
            });
        </script>
        <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({ pageLanguage: 'en', includedLanguages: 'en,ar', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false }, 'google_translate_element');
            }
        </script>
        <script type="text/javascript"
            src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <?php else: ?>
        <title>FAYENOR | <?php echo $text['hero_title'] ?? 'Portal'; ?></title>
        <link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/img/favicon.svg" type="image/svg+xml" />
        <link rel="icon" href="<?php echo BASE_URL; ?>assets/img/favicon.svg" type="image/svg+xml" />

        <?php if ($isRTL): ?>
            <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.rtl.min.css">
            <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/rtl.css">
        <?php else: ?>
            <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
        <?php endif; ?>

        <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/theme.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/mobile.css">
    <?php endif; ?>
</head>

<?php if ($_isLandingPage): ?>

    <body class="antialiased selection:bg-[#52ecc5] selection:text-[#01120c]" style="overflow-x: hidden;">

        <!-- Ambient Background Lighting -->
        <div class="bg-orb w-[300px] sm:w-[600px] h-[300px] sm:h-[600px] bg-[#8FA8C5] top-[-5%] left-[-10%]"></div>
        <div class="bg-orb w-[250px] sm:w-[500px] h-[250px] sm:h-[500px] bg-[#52ecc5] bottom-[-10%] right-[-10%]"
            style="animation-delay: -5s; opacity: 0.1;"></div>
        <div class="bg-orb w-[200px] sm:w-[400px] h-[200px] sm:h-[400px] bg-[#023020] top-[40%] left-[60%]"
            style="animation-delay: -10s;"></div>

        <!-- Top Navigation (Landing Page) -->
        <nav class="fixed top-0 left-0 w-full z-[100] transition-all duration-300 nav-blur pt-3 pb-3 sm:pt-4 sm:pb-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 sm:h-20">
                    <!-- Logo -->
                    <a href="<?php echo BASE_URL; ?>" class="flex items-center gap-2 sm:gap-3 group z-50 flex-shrink-0">
                        <div class="relative flex items-center justify-center">
                            <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" alt="Fayenor Logo"
                                class="h-8 sm:h-10 w-auto transition-transform duration-500 group-hover:scale-105"
                                style="filter: brightness(0) invert(1);">
                            <div
                                class="absolute inset-0 bg-[#52ecc5] blur-xl opacity-0 group-hover:opacity-30 transition-opacity">
                            </div>
                        </div>
                    </a>

                    <!-- Right Actions -->
                    <div class="flex items-center gap-2 sm:gap-4 z-50 flex-shrink-0">
                        <!-- Language Switcher -->
                        <a href="?lang=<?php echo ($lang === 'en' ? 'ar' : 'en'); ?>"
                            class="flex items-center justify-center gap-2 px-2 py-2 sm:px-3 sm:py-2 rounded-lg text-gray-300 hover:text-[#52ecc5] hover:bg-white/5 transition-all group"
                            title="<?php echo $lang === 'en' ? 'Switch to Arabic' : 'Switch to English'; ?>">
                            <i
                                class="fa-solid fa-globe text-lg group-hover:drop-shadow-[0_0_8px_rgba(82,236,197,0.6)] transition-all"></i>
                            <span class="font-sans font-medium text-sm hidden sm:block mt-0.5">
                                <?php echo $lang === 'en' ? 'العربية' : 'English'; ?>
                            </span>
                        </a>
                        <div id="google_translate_element" style="display:none !important;"></div>

                        <!-- Portal Login -->
                        <a href="<?php echo BASE_URL; ?>public/login.php"
                            class="flex items-center justify-center gap-1.5 px-3 py-1.5 sm:px-6 sm:py-2.5 rounded-full border border-[rgba(176,196,222,0.4)] bg-[#023020]/60 text-white hover:bg-[#52ecc5] hover:text-[#023020] hover:border-[#52ecc5] transition-all duration-300 shadow-[0_0_10px_rgba(176,196,222,0.1)] hover:shadow-[0_0_20px_rgba(82,236,197,0.4)] flex-shrink-0">
                            <i class="fa-solid fa-arrow-right-to-bracket text-sm sm:text-base"></i>
                            <span
                                class="hidden sm:inline text-[11px] sm:text-sm font-bold tracking-wider uppercase whitespace-nowrap"><?php echo $text['login'] ?? 'Portal'; ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

    <?php else: ?>

        <body>
            <div id="global-loader" class="global-loader">
                <div class="rooq-spinner"></div>
            </div>

            <!-- Top Navigation (Portal) -->
            <nav class="navbar py-3 shadow-sm"
                style="background-color: var(--rooq-primary); border-bottom: 3px solid var(--rooq-secondary); z-index: 1050; position: relative;">
                <div class="container d-flex justify-content-between align-items-center flex-nowrap">
                    <a class="navbar-brand m-0 p-0 d-flex align-items-center" href="<?php echo BASE_URL; ?>"
                        style="width: 35%; max-width: 160px; min-width: 100px;">
                        <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" alt="FAYENOR"
                            style="width: 100%; height: auto; filter: brightness(0) invert(1);">
                    </a>
                    <div class="d-flex align-items-center gap-2 gap-md-3">
                        <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>"
                            class="btn btn-outline-light rounded-pill px-3 px-md-4 py-1 py-md-2"
                            style="font-size: clamp(0.75rem, 2vw, 1rem); white-space: nowrap;">
                            <?php echo ($lang == 'en' ? 'العربية' : 'English'); ?>
                        </a>
                        <a href="<?php echo BASE_URL; ?>public/login"
                            class="btn btn-rooq-primary rounded-pill px-3 px-md-4 py-1 py-md-2 fw-bold shadow-sm"
                            style="font-size: clamp(0.75rem, 2vw, 1rem); white-space: nowrap;">
                            <?php echo $text['login']; ?>
                        </a>
                    </div>
                </div>
            </nav>
        <?php endif; ?>