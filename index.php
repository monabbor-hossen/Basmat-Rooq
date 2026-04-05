<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fayenor | Premier Gateway to the Saudi Market</title>
    
    <!-- External Assets -->
    <link rel="stylesheet" href="assets/css/theme.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Translate Integration -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,ar', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</head>

<body class="antialiased selection:bg-[#52ecc5] selection:text-[#01120c]">

    <!-- Ambient Background Lighting (Infused with Cyan) -->
    <div class="bg-orb w-[300px] sm:w-[600px] h-[300px] sm:h-[600px] bg-[#8FA8C5] top-[-5%] left-[-10%]"></div>
    <div class="bg-orb w-[250px] sm:w-[500px] h-[250px] sm:h-[500px] bg-[#52ecc5] bottom-[-10%] right-[-10%]"
        style="animation-delay: -5s; opacity: 0.1;"></div>
    <div class="bg-orb w-[200px] sm:w-[400px] h-[200px] sm:h-[400px] bg-[#023020] top-[40%] left-[60%]"
        style="animation-delay: -10s;"></div>

    <!-- Top Navigation -->
    <nav class=" top-0 left-0 w-full z-50 transition-all duration-300 nav-blur pt-3 pb-3 sm:pt-4 sm:pb-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-2 sm:gap-3 group z-50 flex-shrink-0">
                    <div class="relative flex items-center justify-center">
                        <img src="https://fayenor.com//assets/img/logo.png" alt="Fayenor Logo"
                            class="h-8 sm:h-10 w-auto transition-transform duration-500 group-hover:scale-105"
                            style="filter: brightness(0) invert(1);">
                        <div
                            class="absolute inset-0 bg-[#52ecc5] blur-xl opacity-0 group-hover:opacity-30 transition-opacity">
                        </div>
                    </div>
                </a>

                <!-- Right Actions (Translate & Login) - Unified for all screens -->
                <div class="flex items-center gap-3 sm:gap-4 z-50">
                    <!-- Translate Option -->
                    <button id="translate-btn"
                        class="flex items-center justify-center gap-2 px-2 py-2 sm:px-3 sm:py-2 rounded-lg text-gray-300 hover:text-[#52ecc5] hover:bg-white/5 transition-all focus:outline-none group"
                        title="Translate to Arabic">
                        <i
                            class="fa-solid fa-globe text-lg group-hover:drop-shadow-[0_0_8px_rgba(82,236,197,0.6)] transition-all"></i>
                        <span class="font-sans font-medium text-sm hidden sm:block mt-0.5">العربية</span>
                    </button>
                    <!-- Google Translate Hidden Widget -->
                    <div id="google_translate_element" style="display:none;"></div>

                    <!-- Portal Login -->
                    <a href="public/login.php"
                        class="flex items-center justify-center gap-2 px-4 py-1.5 sm:px-6 sm:py-2.5 rounded-full border border-[rgba(176,196,222,0.4)] bg-[#023020]/60 text-white hover:bg-[#52ecc5] hover:text-[#023020] hover:border-[#52ecc5] transition-all duration-300 shadow-[0_0_10px_rgba(176,196,222,0.1)] hover:shadow-[0_0_20px_rgba(82,236,197,0.4)]">
                        <i class="fa-solid fa-arrow-right-to-bracket text-sm sm:text-base"></i>
                        <span
                            class="text-[11px] sm:text-sm font-bold tracking-wider uppercase whitespace-nowrap">Portal</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Interactive 3D Hero Section -->
    <header
        class="relative min-h-[100vh] min-h-[100svh] flex items-center justify-center pt-36 sm:pt-44 pb-12 overflow-hidden perspective-base"
        id="parallax-scene">

        <canvas id="hero-canvas" class="absolute top-0 left-0 w-full h-full pointer-events-none"
            style="z-index: 0; opacity: 0.95;"></canvas>

        <div class="container mx-auto px-4 sm:px-6 relative z-10 preserve-3d w-full">
            <div class="flex flex-col items-center text-center preserve-3d max-w-5xl mx-auto mt-4 sm:mt-10">

                <!-- Floating Vision 2030 Element -->
                <div class="mb-8 sm:mb-10 animate-3d-float z-40" data-depth="0.2">
                    <div
                        class="glass-3d px-4 py-2 sm:px-6 sm:py-3 rounded-full flex items-center gap-3 sm:gap-4 border-[#52ecc5]/20 shadow-[0_0_30px_rgba(82,236,197,0.1)]">
                        <img src="https://www.mofa.gov.sa/_catalogs/masterpage/mofa_pub/assets/ar-SA/images/2030.png"
                            alt="Saudi Vision 2030" class="h-6 sm:h-10 w-auto object-contain">
                        <div class="h-4 sm:h-6 w-px bg-gray-600"></div>
                        <span
                            class="text-[10px] sm:text-sm font-bold tracking-widest text-[#B0C4DE] uppercase drop-shadow-[0_0_10px_rgba(176,196,222,0.5)]">Aligning
                            Corporate Strategy</span>
                    </div>
                </div>

                <!-- 3D Main Title (Updated with luminous crisp gradient) -->
                <h1 class="text-4xl sm:text-6xl md:text-[5rem] lg:text-[5.5rem] font-black tracking-tight sm:tracking-tighter mb-6 sm:mb-8 z-50 leading-tight sm:leading-tight"
                    data-depth="0.6">
                    <span class="text-white text-3d block pb-1 sm:pb-2">Master The</span>
                    <span
                        class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-white via-[#52ecc5] to-[#B0C4DE] drop-shadow-[0_0_20px_rgba(82,236,197,0.4)] pb-2"
                        style="line-height: 1.1;">
                        Saudi Bureaucracy.
                    </span>
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-xl md:text-2xl text-gray-300 font-light mb-10 sm:mb-12 z-30 max-w-[95%] sm:max-w-3xl leading-relaxed mx-auto drop-shadow-md"
                    data-depth="0.3">
                    We are architects of corporate formation. From intricate MISA licensing to full ecosystem
                    integration across Saudi ministries, Fayenor paves your legally sound entry into the Kingdom.
                </p>

                <!-- 3D Action Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 w-full max-w-5xl z-40 preserve-3d px-2 sm:px-0"
                    data-depth="0.4">

                    <!-- Card 1 -->
                    <div
                        class="glass-3d p-5 sm:p-8 interactive-card flex flex-col items-center text-center group cursor-pointer">
                        <div
                            class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl icon-box flex items-center justify-center mb-4 sm:mb-6 transition-all group-hover:shadow-[0_0_25px_rgba(82,236,197,0.4)] group-hover:scale-110 z-30">
                            <i class="fa-solid fa-file-signature text-2xl sm:text-3xl text-[#52ecc5]"></i>
                        </div>
                        <h3 class="text-base sm:text-xl font-bold text-white mb-2 z-20">AoA & C.R. Issuance</h3>
                        <p class="text-xs sm:text-sm text-gray-400 z-10 leading-relaxed">Precision drafting of Articles
                            of Association and Ministry of Commerce registrations.</p>
                    </div>

                    <!-- Card 2 -->
                    <div
                        class="glass-3d p-5 sm:p-8 interactive-card flex flex-col items-center text-center group cursor-pointer border-[#52ecc5]/30 shadow-[0_0_30px_rgba(82,236,197,0.1)] relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-b from-[#52ecc5]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                        <div
                            class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl icon-box flex items-center justify-center mb-4 sm:mb-6 transition-all group-hover:shadow-[0_0_25px_rgba(82,236,197,0.4)] group-hover:scale-110 z-30">
                            <i class="fa-solid fa-building-columns text-2xl sm:text-3xl text-[#52ecc5]"></i>
                        </div>
                        <h3 class="text-base sm:text-xl font-bold text-white mb-2 z-20">MISA Investor Gateway</h3>
                        <p class="text-xs sm:text-sm text-gray-400 z-10 leading-relaxed">Securing foreign direct
                            investment licenses, RHQ setups, and entrepreneurial permits.</p>
                    </div>

                    <!-- Card 3 -->
                    <div
                        class="glass-3d p-5 sm:p-8 interactive-card flex flex-col items-center text-center group cursor-pointer">
                        <div
                            class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl icon-box flex items-center justify-center mb-4 sm:mb-6 transition-all group-hover:shadow-[0_0_25px_rgba(82,236,197,0.4)] group-hover:scale-110 z-30">
                            <i class="fa-solid fa-scale-balanced text-2xl sm:text-3xl text-[#52ecc5]"></i>
                        </div>
                        <h3 class="text-base sm:text-xl font-bold text-white mb-2 z-20">Compliance Architecture</h3>
                        <p class="text-xs sm:text-sm text-gray-400 z-10 leading-relaxed">Structuring labor, tax, and
                            municipality compliance for immediate operational readiness.</p>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <!-- Government Ecosystem Section -->
    <section id="ecosystem" class="py-20 sm:py-32 relative z-10 perspective-base preserve-3d">
        <div class="container mx-auto px-4 sm:px-6 max-w-7xl">
            <div class="flex flex-col lg:flex-row items-center gap-12 sm:gap-20 preserve-3d">

                <!-- Left: 3D Orbiting Logos -->
                <div class="w-full lg:w-1/2 flex justify-center preserve-3d">
                    <div class="logo-orbit-container">

                        <!-- Center Element: Fayenor -->
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
                            <div
                                class="w-24 h-24 sm:w-36 sm:h-36 rounded-full glass-3d flex items-center justify-center border-[3px] border-[#52ecc5] shadow-[0_0_60px_rgba(82,236,197,0.4)] bg-[#023020]">
                                <img src="https://fayenor.com//assets/img/logo.png" alt="Fayenor"
                                    class="h-12 sm:h-20 w-auto" style="filter: brightness(0) invert(1);">
                            </div>
                        </div>

                        <!-- Orbiting Elements -->
                        <div class="orbit-item"
                            style="transform: translate(-50%, -50%) rotateY(0deg) translateZ(var(--orbit-radius));">
                            <div class="orbit-card"><img
                                    src="https://fayenor.com/assets/img/icons/Ministry_of_Investment_Logo-Dark.svg"
                                    alt="MISA"
                                    class="filter brightness-0 invert drop-shadow-[0_0_8px_rgba(255,255,255,0.5)]">
                            </div>
                        </div>
                        <div class="orbit-item"
                            style="transform: translate(-50%, -50%) rotateY(60deg) translateZ(var(--orbit-radius));">
                            <div class="orbit-card"><img
                                    src="https://fayenor.com/assets/img/icons/Ministry_of_Commerce_Logo.svg"
                                    alt="Commerce"
                                    class="filter brightness-0 invert drop-shadow-[0_0_8px_rgba(255,255,255,0.5)]">
                            </div>
                        </div>
                        <div class="orbit-item"
                            style="transform: translate(-50%, -50%) rotateY(120deg) translateZ(var(--orbit-radius));">
                            <div class="orbit-card bg-[#e2e8f0]"><img
                                    src="https://fayenor.com/assets/img/icons/GOSI-General-Organization-for-Social-Insurance.jpg"
                                    alt="GOSI" class="h-full w-full object-contain"></div>
                        </div>
                        <div class="orbit-item"
                            style="transform: translate(-50%, -50%) rotateY(180deg) translateZ(var(--orbit-radius));">
                            <div class="orbit-card bg-[#e2e8f0]"><img
                                    src="https://fayenor.com/assets/img/icons/qiwa.svg" alt="Qiwa"></div>
                        </div>
                        <div class="orbit-item"
                            style="transform: translate(-50%, -50%) rotateY(240deg) translateZ(var(--orbit-radius));">
                            <div class="orbit-card"><img src="https://fayenor.com/assets/img/icons/MUQEEM.png"
                                    alt="Muqeem" class="drop-shadow-[0_0_8px_rgba(255,255,255,0.5)]"></div>
                        </div>
                        <div class="orbit-item"
                            style="transform: translate(-50%, -50%) rotateY(300deg) translateZ(var(--orbit-radius));">
                            <div class="orbit-card bg-[#e2e8f0]"><img
                                    src="https://fayenor.com/assets/img/icons/Saudi_building_code_logo.svg" alt="SBC">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right: Text Content -->
                <div class="w-full lg:w-1/2 preserve-3d" data-depth="0.2">
                    <h2
                        class="text-xs sm:text-sm font-bold tracking-widest text-[#52ecc5] uppercase mb-3 sm:mb-4 translate-z-20 text-center lg:text-left drop-shadow-[0_0_10px_rgba(82,236,197,0.4)]">
                        The Ecosystem
                    </h2>
                    <h3
                        class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-6 sm:mb-8 text-3d leading-tight translate-z-40 text-center lg:text-left">
                        Connecting You to the Core of Saudi Business.
                    </h3>

                    <div
                        class="space-y-4 sm:space-y-6 text-gray-300 translate-z-30 text-base sm:text-lg px-2 sm:px-0 text-center lg:text-left">
                        <p>
                            Entering the Saudi market requires simultaneous navigation of multiple government portals.
                            Fayenor acts as your central processing unit.
                        </p>
                        <p>
                            Once your <strong class="text-[#52ecc5] drop-shadow-[0_0_8px_rgba(82,236,197,0.3)]">MISA
                                License</strong> is secured, we cascade your
                            profile across the necessary ecosystem: activating your <strong
                                class="text-[#52ecc5]">Qiwa</strong> and <strong class="text-[#52ecc5]">Muqeem</strong>
                            accounts for foreign workforce visas, registering your entity with <strong
                                class="text-[#52ecc5]">GOSI</strong> for social insurance compliance, and securing your
                            municipality permits via the <strong class="text-[#52ecc5]">Saudi Building Code
                                (SBC)</strong>.
                        </p>
                    </div>

                    <div class="mt-8 sm:mt-10 translate-z-40 flex justify-center lg:justify-start">
                        <div
                            class="inline-flex items-center gap-3 sm:gap-4 p-1.5 pr-5 sm:pr-8 rounded-full bg-[#023020]/80 border border-[#52ecc5]/40 shadow-[0_0_20px_rgba(82,236,197,0.15)]">
                            <div
                                class="bg-gradient-to-br from-[#52ecc5] to-[#B0C4DE] text-[#01120c] w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center font-black text-sm sm:text-lg shadow-inner">
                                10+
                            </div>
                            <span class="text-xs sm:text-base font-bold tracking-wide text-white">Government Portals
                                Managed</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Consultancy Expertise Section -->
    <section id="expertise"
        class="py-20 sm:py-32 relative z-10 bg-gradient-to-b from-transparent to-[#01120c] perspective-base preserve-3d">
        <div class="container mx-auto px-4 sm:px-6 max-w-7xl text-center preserve-3d">

            <h2 class="text-3xl sm:text-5xl font-bold mb-12 sm:mb-20 text-white text-3d translate-z-40">Consultancy
                Beyond Setup</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 sm:gap-12 text-left preserve-3d">

                <!-- Expertise Card 1 -->
                <div class="glass-3d p-8 sm:p-12 interactive-card relative overflow-hidden group border-[#B0C4DE]/20">
                    <div
                        class="absolute top-0 right-0 w-40 sm:w-64 h-40 sm:h-64 bg-[#52ecc5] opacity-0 blur-[80px] sm:blur-[100px] group-hover:opacity-15 transition-opacity duration-500">
                    </div>
                    <h3
                        class="text-xl sm:text-2xl font-bold text-white mb-4 sm:mb-6 translate-z-40 flex items-center gap-3 sm:gap-4">
                        <span class="text-[#52ecc5] text-2xl sm:text-3xl drop-shadow-[0_0_15px_rgba(82,236,197,0.4)]"><i
                                class="fa-solid fa-gavel"></i></span>
                        Legal Entity Structuring
                    </h3>
                    <p class="text-gray-300 leading-relaxed text-sm sm:text-lg translate-z-20 mb-8">
                        Choosing between an LLC, Joint Stock Company, or a Foreign Branch determines your operational
                        freedom. Our legal consultants draft bilingual Articles of Association precisely tailored to
                        shield liability while maximizing your scope under the Foreign Investment Law.
                    </p>
                    <ul class="space-y-3 sm:space-y-4 text-xs sm:text-base text-gray-400 translate-z-30 font-medium">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-[#52ecc5]"></i>
                            Notarization & Chamber Registration</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-[#52ecc5]"></i> Shareholder
                            Agreements</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-[#52ecc5]"></i> Board
                            Resolution Drafting</li>
                    </ul>
                </div>

                <!-- Expertise Card 2 -->
                <div class="glass-3d p-8 sm:p-12 interactive-card relative overflow-hidden group border-[#B0C4DE]/20">
                    <div
                        class="absolute top-0 right-0 w-40 sm:w-64 h-40 sm:h-64 bg-[#52ecc5] opacity-0 blur-[80px] sm:blur-[100px] group-hover:opacity-15 transition-opacity duration-500">
                    </div>
                    <h3
                        class="text-xl sm:text-2xl font-bold text-white mb-4 sm:mb-6 translate-z-40 flex items-center gap-3 sm:gap-4">
                        <span class="text-[#52ecc5] text-2xl sm:text-3xl drop-shadow-[0_0_15px_rgba(82,236,197,0.4)]"><i
                                class="fa-solid fa-users-viewfinder"></i></span>
                        Workforce & Localization
                    </h3>
                    <p class="text-gray-300 leading-relaxed text-sm sm:text-lg translate-z-20 mb-8">
                        We bridge the gap between foreign talent acquisition and Saudization mandates (Nitaqat). We
                        construct your Qiwa and Muqeem platforms, manage block visa approvals, and formulate GOSI
                        strategies to keep your company in the Platinum compliance tier.
                    </p>
                    <ul class="space-y-3 sm:space-y-4 text-xs sm:text-base text-gray-400 translate-z-30 font-medium">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-[#52ecc5]"></i> Saudization
                            Tier Strategy</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-[#52ecc5]"></i> Investor
                            Iqama Processing</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-check text-[#52ecc5]"></i> Employment
                            Contract Authentication</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact"
        class="bg-[#010c08] pt-16 sm:pt-24 pb-8 sm:pb-12 border-t border-[#023020] relative z-10 preserve-3d">
        <div class="container mx-auto px-4 sm:px-6 max-w-7xl">
            <div
                class="glass-3d p-8 sm:p-16 mb-12 sm:mb-20 flex flex-col md:flex-row items-center justify-between gap-8 sm:gap-10 translate-z-30 text-center md:text-left border-[#52ecc5]/20 shadow-[0_0_40px_rgba(82,236,197,0.05)]">
                <div>
                    <h3 class="text-3xl sm:text-4xl font-bold text-white mb-3">Ready to establish your presence?</h3>
                    <p class="text-gray-300 text-base sm:text-lg">Speak directly with our senior corporate consultants
                        in Al-Qassim.</p>
                </div>
                <a href="mailto:Kh70007980@gmail.com"
                    class="btn-glow px-8 sm:px-10 py-4 sm:py-5 rounded-xl text-lg sm:text-xl font-bold tracking-wide flex items-center gap-4 w-full md:w-auto justify-center">
                    Contact Fayenor <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10 sm:gap-12 items-start mb-10 sm:mb-16 text-center sm:text-left">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <img src="https://fayenor.com//assets/img/logo.png" alt="Fayenor"
                        class="h-12 sm:h-16 w-auto mb-6 sm:mb-8 mx-auto sm:mx-0"
                        style="filter: brightness(0) invert(1);">
                    <p class="text-gray-400 text-sm sm:text-base max-w-md leading-relaxed mx-auto sm:mx-0">
                        Fayenor Company Limited is Saudi Arabia's premier business setup and corporate governance
                        consultancy, specializing in MISA facilitation and end-to-end legal compliance.
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="text-white font-bold mb-5 uppercase tracking-widest text-sm">Headquarters</h4>
                    <ul class="text-gray-400 space-y-4 text-sm sm:text-base">
                        <li class="flex items-start justify-center sm:justify-start gap-3">
                            <i class="fa-solid fa-location-dot text-[#52ecc5] mt-1"></i>
                            <span class="text-left">Unaizah, Al-Qassim<br>Kingdom of Saudi Arabia</span>
                        </li>
                        <li class="flex items-center justify-center sm:justify-start gap-3 mt-4">
                            <i class="fa-solid fa-envelope text-[#52ecc5]"></i>
                            Kh70007980@gmail.com
                        </li>
                    </ul>
                </div>

                <!-- Capabilities -->
                <div>
                    <h4 class="text-white font-bold mb-5 uppercase tracking-widest text-sm">Expertise</h4>
                    <ul class="text-gray-400 space-y-3 text-sm sm:text-base">
                        <li class="hover:text-[#52ecc5] transition-colors cursor-pointer">MISA Licensing</li>
                        <li class="hover:text-[#52ecc5] transition-colors cursor-pointer">Articles of Association</li>
                        <li class="hover:text-[#52ecc5] transition-colors cursor-pointer">Commercial Registration</li>
                        <li class="hover:text-[#52ecc5] transition-colors cursor-pointer">Muqeem & Qiwa Strategy</li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-[rgba(176,196,222,0.1)] pt-6 sm:pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                <div class="text-center md:text-left">&copy; 2026 Fayenor Company Limited. All rights reserved.</div>
                <div class="flex gap-6 justify-center">
                    <span class="hover:text-[#52ecc5] cursor-pointer transition-colors">Privacy</span>
                    <span class="hover:text-[#52ecc5] cursor-pointer transition-colors">Legal Framework</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Global Scripts -->
    <script src="assets/js/main.js" defer></script>
</body>

</html>