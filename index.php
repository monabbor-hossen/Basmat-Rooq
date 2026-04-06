<?php
$isLandingPage = true;
require_once 'includes/header.php';
?>

<!-- Interactive 3D Hero Section -->
<header
    class="relative min-h-[100vh] min-h-[100svh] flex items-center justify-center pt-36 sm:pt-44 pb-12 overflow-hidden perspective-base parallax-scene"
    id="hero-section">

    <canvas id="hero-canvas" class="absolute top-0 left-0 w-full h-full pointer-events-none"
        style="z-index: 0; opacity: 0.95;"></canvas>

    <div class="container mx-auto px-4 sm:px-6 relative z-10 preserve-3d w-full">
        <div class="flex flex-col items-center text-center preserve-3d max-w-5xl mx-auto mt-4 sm:mt-10">

            <!-- Floating Vision 2030 Element -->
            <div class="mb-8 sm:mb-10 animate-3d-float z-40" data-depth="0.2">
                <div
                    class="glass-3d px-4 py-2 sm:px-6 sm:py-3 rounded-full flex items-center gap-3 sm:gap-4 border-[#52ecc5]/20 shadow-[0_0_30px_rgba(82,236,197,0.1)]">
                    <img src="<?php echo BASE_URL; ?>/assets/img/Saudi_Vision_2030_logo.svg" alt="Saudi Vision 2030"
                        class="h-6 sm:h-10 w-auto object-contain">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 w-full max-w-5xl z-40 preserve-3d px-2 sm:px-0"
                data-depth="0.4">

                <!-- Card 1 -->
                <div
                    class="glass-3d p-5 sm:p-8 interactive-card flex flex-col items-center text-center group cursor-pointer">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl icon-box flex items-center justify-center mb-4 sm:mb-6 transition-all group-hover:shadow-[0_0_25px_rgba(82,236,197,0.4)] group-hover:scale-110 z-30">
                        <i class="bi bi-file-earmark-text text-2xl sm:text-3xl text-[#52ecc5]"></i>
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
                        <i class="bi bi-bank text-2xl sm:text-3xl text-[#52ecc5]"></i>
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
                        <i class="bi bi-bank2 text-2xl sm:text-3xl text-[#52ecc5]"></i>
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
                            <img src="<?php echo BASE_URL; ?>assets/img/logo.png" alt="Fayenor"
                                class="h-12 sm:h-20 w-auto" style="filter: brightness(0) invert(1);">
                        </div>
                    </div>

                    <!-- Orbiting Elements -->
                    <div class="orbit-item"
                        style="transform: translate(-50%, -50%) rotateY(0deg) translateZ(var(--orbit-radius));">
                        <div class="orbit-card"><img
                                src="<?php echo BASE_URL; ?>assets/img/icons/Ministry_of_Investment_Logo-Dark.svg"
                                alt="MISA"
                                class="filter brightness-0 invert drop-shadow-[0_0_8px_rgba(255,255,255,0.5)]">
                        </div>
                    </div>
                    <div class="orbit-item"
                        style="transform: translate(-50%, -50%) rotateY(60deg) translateZ(var(--orbit-radius));">
                        <div class="orbit-card"><img
                                src="<?php echo BASE_URL; ?>assets/img/icons/Ministry_of_Commerce_Logo.svg" alt="Commerce"
                                class="filter brightness-0 invert drop-shadow-[0_0_8px_rgba(255,255,255,0.5)]">
                        </div>
                    </div>
                    <div class="orbit-item"
                        style="transform: translate(-50%, -50%) rotateY(120deg) translateZ(var(--orbit-radius));">
                        <div class="orbit-card bg-[#e2e8f0]"><img
                                src="<?php echo BASE_URL; ?>assets/img/icons/GOSI-General-Organization-for-Social-Insurance.jpg"
                                alt="GOSI" class="h-full w-full object-contain"></div>
                    </div>
                    <div class="orbit-item"
                        style="transform: translate(-50%, -50%) rotateY(180deg) translateZ(var(--orbit-radius));">
                        <div class="orbit-card bg-[#e2e8f0]"><img src="<?php echo BASE_URL; ?>assets/img/icons/qiwa.svg"
                                alt="Qiwa"></div>
                    </div>
                    <div class="orbit-item"
                        style="transform: translate(-50%, -50%) rotateY(240deg) translateZ(var(--orbit-radius));">
                        <div class="orbit-card"><img src="<?php echo BASE_URL; ?>assets/img/icons/MUQEEM.png" alt="Muqeem"
                                class="drop-shadow-[0_0_8px_rgba(255,255,255,0.5)]"></div>
                    </div>
                    <div class="orbit-item"
                        style="transform: translate(-50%, -50%) rotateY(300deg) translateZ(var(--orbit-radius));">
                        <div class="orbit-card bg-[#e2e8f0]"><img
                                src="<?php echo BASE_URL; ?>assets/img/icons/Saudi_building_code_logo.svg" alt="SBC">
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
                            class="bi bi-hammer"></i></span>
                    Legal Entity Structuring
                </h3>
                <p class="text-gray-300 leading-relaxed text-sm sm:text-lg translate-z-20 mb-8">
                    Choosing between an LLC, Joint Stock Company, or a Foreign Branch determines your operational
                    freedom. Our legal consultants draft bilingual Articles of Association precisely tailored to
                    shield liability while maximizing your scope under the Foreign Investment Law.
                </p>
                <ul class="space-y-3 sm:space-y-4 text-xs sm:text-base text-gray-400 translate-z-30 font-medium">
                    <li class="flex items-center gap-3"><i class="bi bi-check-lg text-[#52ecc5]"></i>
                        Notarization & Chamber Registration</li>
                    <li class="flex items-center gap-3"><i class="bi bi-check-lg text-[#52ecc5]"></i> Shareholder
                        Agreements</li>
                    <li class="flex items-center gap-3"><i class="bi bi-check-lg text-[#52ecc5]"></i> Board
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
                            class="bi bi-people-fill"></i></span>
                    Workforce & Localization
                </h3>
                <p class="text-gray-300 leading-relaxed text-sm sm:text-lg translate-z-20 mb-8">
                    We bridge the gap between foreign talent acquisition and Saudization mandates (Nitaqat). We
                    construct your Qiwa and Muqeem platforms, manage block visa approvals, and formulate GOSI
                    strategies to keep your company in the Platinum compliance tier.
                </p>
                <ul class="space-y-3 sm:space-y-4 text-xs sm:text-base text-gray-400 translate-z-30 font-medium">
                    <li class="flex items-center gap-3"><i class="bi bi-check-lg text-[#52ecc5]"></i> Saudization
                        Tier Strategy</li>
                    <li class="flex items-center gap-3"><i class="bi bi-check-lg text-[#52ecc5]"></i> Investor
                        Iqama Processing</li>
                    <li class="flex items-center gap-3"><i class="bi bi-check-lg text-[#52ecc5]"></i> Employment
                        Contract Authentication</li>
                </ul>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>