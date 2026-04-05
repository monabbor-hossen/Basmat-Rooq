<?php if (isset($isLandingPage) && $isLandingPage): ?>
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
                    <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" alt="Fayenor"
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
                <div class="text-center md:text-left">&copy; <?php echo date('Y'); ?> Fayenor Company Limited. All rights reserved.</div>
                <div class="flex gap-6 justify-center">
                    <span class="hover:text-[#52ecc5] cursor-pointer transition-colors">Privacy</span>
                    <span class="hover:text-[#52ecc5] cursor-pointer transition-colors">Legal Framework</span>
                </div>
            </div>
        </div>
    </footer>
<?php endif; ?>

<script src="<?php echo BASE_URL; ?>assets/js/main.js" defer></script>

</body>
</html>