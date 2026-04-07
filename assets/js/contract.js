/* ==========================================================================
   1. EDIT PAGE: RICH TEXT EDITOR INITIALIZATION
   ========================================================================== */
// Only run this if jQuery is loaded (prevents errors on the viewing page)
if (typeof jQuery !== 'undefined') {
    $(document).ready(function () {
        if ($('.rich-editor').length) {
            $('.rich-editor').summernote({
                tabsize: 2,
                height: 140,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });
        }
    });
}
/* ==========================================================================
   2. VIEW PAGE: PDF GENERATION FUNCTION
   ========================================================================== */
function generatePDF() {
    const element = document.getElementById('contract-content');
    const pages = document.querySelectorAll('.document-page');
    const logoImg = document.querySelector('.brand-logo');

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    let originalSrc = null;

    // --- STEP 1: CONVERT LOGO TO WHITE ---
    if (logoImg) {
        canvas.width = logoImg.naturalWidth;
        canvas.height = logoImg.naturalHeight;
        ctx.drawImage(logoImg, 0, 0);
        ctx.globalCompositeOperation = 'source-in';
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        originalSrc = logoImg.src;
        logoImg.src = canvas.toDataURL('image/png');
    }

    // --- STEP 2: BAKE FILTER INTO WATERMARK ---
    const watermarkImg = document.querySelector('.watermark');
    let originalWatermarkSrc = null;

    if (watermarkImg && watermarkImg.naturalWidth > 0) {
        originalWatermarkSrc = watermarkImg.src;
        canvas.width = watermarkImg.naturalWidth;
        canvas.height = watermarkImg.naturalHeight;
        ctx.filter = 'brightness(0) drop-shadow(2px 0 0 white) drop-shadow(-2px 0 0 white) drop-shadow(0 2px 0 white) drop-shadow(0 -2px 0 white) invert(1)';
        ctx.drawImage(watermarkImg, 0, 0, canvas.width, canvas.height);
        watermarkImg.src = canvas.toDataURL('image/png');
        watermarkImg.style.filter = 'none';
    }

    // 1. Prepare for PDF
    pages.forEach(p => {
        p.style.marginBottom = '0px';
        p.style.boxShadow = 'none';
        // REMOVED the p.style.height hack! Let the CSS handle the exact size.
    });

    // Fetch naming data safely from the HTML data attributes
    const clientName = element.getAttribute('data-client-name') || 'Client';
    const licenseType = element.getAttribute('data-license-type') || 'Service';
    const filename = `${licenseType}_Agreement_${clientName}.pdf`;
    const opt = {
        margin: 0,
        filename: filename,
        image: {
            type: 'jpeg',
            quality: 1
        },
        html2canvas: {
            scale: 2,
            useCORS: true,
            scrollY: 0,
            windowWidth: document.documentElement.offsetWidth
        },
        jsPDF: {
            unit: 'px',
            format: [794, 1123],
            orientation: 'portrait',
            hotfixes: ['px_scaling']
        },
        // FIX: Removed the conflicting 'before' rule. Now it ONLY listens to your CSS.
        pagebreak: { mode: 'css' }
    };

    // 2. Generate and then return normal web view styling
    html2pdf().set(opt).from(element).save().then(() => {
        pages.forEach(p => {
            p.style.marginBottom = '40px';
            p.style.boxShadow = '0 15px 30px rgba(0,0,0,0.2)';
        });

        // Put the original images back so the website looks normal!
        if (logoImg && originalSrc) {
            logoImg.src = originalSrc;
        }
        if (watermarkImg && originalWatermarkSrc) {
            watermarkImg.src = originalWatermarkSrc;
            watermarkImg.style.filter = '';
        }
    });
}