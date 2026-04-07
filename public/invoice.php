<?php
// public/invoice.php
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Database.php';

if (session_status() === PHP_SESSION_NONE)
    session_start();

// 1. Get Parameters
$payment_id = isset($_GET['payment_id']) ? intval($_GET['payment_id']) : 0;
$client_id = isset($_GET['client_id']) ? $_GET['client_id'] : 'all';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$verify_token = $_GET['verify'] ?? '';

// 2. Generate a SECURE HASH to allow public QR verification (Bypasses login securely)
$secret_key = defined('CHAT_ENCRYPTION_KEY') ? CHAT_ENCRYPTION_KEY : 'Fayenor_Secure_Invoice_2026';
if ($payment_id > 0) {
    $expected_hash = hash('sha256', "single_{$payment_id}_{$secret_key}");
} else {
    $expected_hash = hash('sha256', "stmt_{$client_id}_{$start_date}_{$end_date}_{$secret_key}");
}

// 3. Security Check
$is_public_view = false;
if (!empty($verify_token) && hash_equals($expected_hash, $verify_token)) {
    $is_public_view = true; // The user scanned the QR code! Allow access without login.
} else {
    // Normal Access: Must be logged in
    if (!isset($_SESSION['user_id'])) {
        die("<div style='font-family:sans-serif;padding:40px;text-align:center;background:#01120c;color:#fff;height:100vh;'><h3>Unauthorized Access</h3><p>Please log in or use a valid QR verification scan.</p></div>");
    }
}

$db = (new Database())->getConnection();

// Safely handle roles for public viewers (they have no session)
$is_client = isset($_SESSION['role']) ? in_array($_SESSION['role'], ['client', '3']) : false;
$logged_in_account = isset($_SESSION['account_id']) ? intval($_SESSION['account_id']) : (isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0);

$payments = [];
$total_amount = 0;
$mode = 'single';

try {
    if ($payment_id > 0) {
        // --- SINGLE INVOICE MODE ---
        $mode = 'single';
        $stmt = $db->prepare("
            SELECT p.payment_id, p.client_id, p.amount, p.payment_method,
                   p.payment_status, p.payment_date, p.notes,
                   c.company_name, c.client_name, c.email, c.phone_number, c.account_id
            FROM payments p
            JOIN clients c ON p.client_id = c.client_id
            WHERE p.payment_id = ?
        ");
        $stmt->execute([$payment_id]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment) {
            die("<div style='font-family:sans-serif;padding:40px;text-align:center;'><h3>Invoice Not Found</h3><p>No payment record found for ID #" . $payment_id . ".</p></div>");
        }

        // Prevent clients from seeing other clients' invoices (unless public QR is used)
        if (!$is_public_view && $is_client && $payment['account_id'] != $logged_in_account) {
            die("<div style='font-family:sans-serif;padding:40px;text-align:center;'><h3>Access Denied</h3><p>You do not have permission to view this invoice.</p></div>");
        }

        $payments[] = $payment;
        $billed_to_name = $payment['company_name'];
        $billed_to_contact = !empty($payment['client_name']) ? $payment['client_name'] : '';
        $billed_to_email = $payment['email'] ?? '';
        $billed_to_phone = $payment['phone_number'] ?? '';
        $invoice_date = date('F d, Y', strtotime($payment['payment_date']));
        $invoice_no = "INV-" . str_pad($payment['payment_id'], 6, "0", STR_PAD_LEFT);
        $title = "PAYMENT RECEIPT";

    } else {
        // --- FULL STATEMENT MODE ---
        $mode = 'statement';
        $title = "STATEMENT OF ACCOUNT";
        $invoice_date = date('F d, Y');
        $invoice_no = "STM-" . date('Ymd-Hi');

        $query = "
            SELECT p.payment_id, p.client_id, p.amount, p.payment_method,
                   p.payment_status, p.payment_date, p.notes,
                   c.company_name, c.client_name, c.email, c.phone_number, c.account_id
            FROM payments p
            JOIN clients c ON p.client_id = c.client_id
            WHERE 1=1
        ";
        $params = [];

        // Security: clients only see their own data (unless public QR is used)
        if (!$is_public_view && $is_client) {
            $query .= " AND c.account_id = ?";
            $params[] = $logged_in_account;
        }
        if ($client_id !== 'all' && intval($client_id) > 0) {
            $query .= " AND p.client_id = ?";
            $params[] = intval($client_id);
        }
        if (!empty($start_date)) {
            $query .= " AND p.payment_date >= ?";
            $params[] = $start_date;
        }
        if (!empty($end_date)) {
            $query .= " AND p.payment_date <= ?";
            $params[] = $end_date;
        }
        $query .= " ORDER BY p.payment_date DESC";

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($payments) === 0) {
            die("<div style='font-family:sans-serif;padding:40px;text-align:center;'><h3>No Transactions Found</h3><p>No records match the selected filters.</p></div>");
        }

        // Always use the first (or only) client's real data for the Billed To section
        $billed_to_name = $payments[0]['company_name'];
        $billed_to_contact = $payments[0]['client_name'] ?? '';
        $billed_to_email = $payments[0]['email'] ?? '';
        $billed_to_phone = $payments[0]['phone_number'] ?? '';

        // If multiple different clients are present, show a generic header
        $client_ids = array_unique(array_column($payments, 'client_id'));
        if (count($client_ids) > 1) {
            $billed_to_name = "Multiple Clients";
            $billed_to_contact = "";
            $billed_to_email = "";
            $billed_to_phone = "";
        }
    }

    // Calculate total (completed payments only)
    foreach ($payments as $p) {
        if ($p['payment_status'] === 'Completed') {
            $total_amount += floatval($p['amount']);
        }
    }

} catch (Exception $e) {
    die("<div style='font-family:sans-serif;padding:40px;text-align:center;'><h3>Error</h3><p>Could not generate invoice. Please try again.</p><small style='color:#999'>" . htmlspecialchars($e->getMessage()) . "</small></div>");
}

// 4. GENERATE DYNAMIC QR CODE URL
$clean_base = rtrim(BASE_URL, '/');
if ($payment_id > 0) {
    $verify_link = $clean_base . "/public/invoice?payment_id={$payment_id}&verify={$expected_hash}";
} else {
    $verify_link = $clean_base . "/public/invoice?client_id={$client_id}&start_date={$start_date}&end_date={$end_date}&verify={$expected_hash}";
}
$qr_code_image = "https://api.qrserver.com/v1/create-qr-code/?size=120x120&margin=0&color=023020&data=" . urlencode($verify_link);


// Status badge helper
function statusBadge($status)
{
    if ($status === 'Completed')
        return '<span class="status-badge status-completed">✓ Completed</span>';
    if ($status === 'Pending')
        return '<span class="status-badge status-pending">⏳ Pending</span>';
    return '<span class="status-badge status-other">' . htmlspecialchars($status) . '</span>';
}

// Google Translate cookie detection
$gt_cookie = $_COOKIE['googtrans'] ?? '';
$is_rtl = (strpos($gt_cookie, '/en/ar') !== false);
$dir = $is_rtl ? 'rtl' : 'ltr';
?>
<!DOCTYPE html>
<html lang="en" dir="<?php echo $dir; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $invoice_no; ?> | Fayenor Invoice</title>
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/img/favicon.svg" type="image/svg+xml" />
    <link rel="icon" href="<?php echo BASE_URL; ?>assets/img/favicon.svg" type="image/svg+xml" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/theme.css">

    <style>
        .goog-te-banner-frame.skiptranslate,
        .goog-te-banner-frame,
        .VIpgJd-ZVi9od-ORHb-OEVmcd,
        iframe.skiptranslate {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }

        .goog-te-gadget,
        #google_translate_element,
        #goog-gt-tt,
        .goog-tooltip {
            display: none !important;
            visibility: hidden !important;
        }

        body {
            top: 0px !important;
            position: static !important;
        }

        body.translated-ltr,
        body.translated-rtl {
            margin-top: 0 !important;
            top: 0px !important;
        }
    </style>
</head>

<body class="invoice-page-body">

    <div class="inv-toolbar no-print">
        <div class="inv-toolbar-brand">
            <img src="<?php echo BASE_URL; ?>assets/img/logo.png" height="50" alt="Logo"
                style="filter: brightness(0) invert(1);">
            <?php if ($is_public_view): ?>
                <span
                    style="background: #52ecc5; color: #01120c; padding: 4px 10px; border-radius: 20px; font-size: 12px; margin-left: 15px; font-weight: bold;">✓
                    Verified Authentic</span>
            <?php endif; ?>
        </div>
        <div class="inv-toolbar-actions">
            <button class="inv-btn-download" id="btnDownloadPdf">&#x2B73; Download PDF</button>
            <button class="inv-btn-print" onclick="window.print()">&#x1F5A8; Print</button>
            <?php if (!$is_public_view): ?>
                <button class="inv-btn-close" onclick="window.close()">&#x2715; Close</button>
            <?php endif; ?>
        </div>
    </div>

    <div class="inv-page-wrap">
        <div class="invoice">

            <div class="invoice-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <img src="<?php echo BASE_URL; ?>assets/img/logo.png" alt="Fayenor"
                        style="max-height:60px; filter: brightness(0) invert(1);">
                </div>
                <div style="display: flex; align-items: center; gap: 20px; text-align: right;">
                    <div class="invoice-meta">
                        <div class="invoice-type"><?php echo $title; ?></div>
                        <div class="invoice-number"><?php echo $invoice_no; ?></div>
                        <div class="invoice-date-lbl"><?php echo $invoice_date; ?></div>
                    </div>
                    <div class="qr-code-box"
                        style="background: #fff; padding: 6px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                        <img src="<?php echo $qr_code_image; ?>" alt="Verify QR"
                            style="width: 75px; height: 75px; display: block; filter: none !important;">
                    </div>
                </div>
            </div>

            <div class="invoice-body">

                <div class="parties">
                    <div>
                        <div class="party-label">Billed To</div>
                        <div class="party-name"><?php echo htmlspecialchars($billed_to_name); ?></div>
                        <?php if (!empty($billed_to_contact)): ?>
                            <div class="party-contact"><?php echo htmlspecialchars($billed_to_contact); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($billed_to_email) && $billed_to_email !== 'N/A'): ?>
                            <div class="party-detail"><?php echo htmlspecialchars($billed_to_email); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($billed_to_phone) && $billed_to_phone !== 'N/A'): ?>
                            <div class="party-detail"><?php echo htmlspecialchars($billed_to_phone); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="party-from">
                        <div class="party-label">Issued By</div>
                        <div class="party-name">Fayenor Company</div>
                        <div class="party-detail">
                            Al-Rass, Al-Qassim<br>
                            Kingdom of Saudi Arabia<br>
                            info@fayenor.com
                        </div>
                    </div>
                </div>

                <div class="inv-divider"></div>

                <?php if ($mode === 'single' && count($payments) === 1):
                    $pay = $payments[0];
                    ?>
                    <div class="single-highlight">
                        <div>
                            <div class="hf-label">Transaction ID</div>
                            <div class="hf-value">#<?php echo str_pad($pay['payment_id'], 6, "0", STR_PAD_LEFT); ?></div>
                        </div>
                        <div>
                            <div class="hf-label">Payment Date</div>
                            <div class="hf-value"><?php echo date('d M Y', strtotime($pay['payment_date'])); ?></div>
                        </div>
                        <div>
                            <div class="hf-label">Payment Method</div>
                            <div class="hf-value"><?php echo htmlspecialchars($pay['payment_method']); ?></div>
                        </div>
                        <div>
                            <div class="hf-label">Amount Paid</div>
                            <div class="hf-value amount-big">SAR <?php echo number_format($pay['amount'], 2); ?></div>
                        </div>
                    </div>

                    <?php if (!empty($pay['notes'])): ?>
                        <div class="notes-box">
                            <div class="notes-label">Notes / Description</div>
                            <div class="notes-text"><?php echo htmlspecialchars($pay['notes']); ?></div>
                        </div>
                    <?php endif; ?>

                    <table class="items-table">
                        <thead>
                            <tr>
                                <th style="width:22%">Date</th>
                                <th style="width:40%">Project / Company</th>
                                <th style="width:20%">Method</th>
                                <th style="width:18%" class="text-right">Amount (SAR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo date('d M Y', strtotime($pay['payment_date'])); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($pay['company_name']); ?>
                                    <div class="ref-sub">Ref:
                                        #<?php echo str_pad($pay['payment_id'], 6, "0", STR_PAD_LEFT); ?></div>
                                </td>
                                <td><?php echo htmlspecialchars($pay['payment_method']); ?></td>
                                <td class="text-right"><?php echo number_format($pay['amount'], 2); ?></td>
                            </tr>
                        </tbody>
                    </table>

                <?php else: ?>

                    <table class="items-table">
                        <thead>
                            <tr>
                                <th style="width:20%">Date</th>
                                <th style="width:36%">Project / Company</th>
                                <th style="width:24%">Method</th>
                                <th style="width:20%" class="text-right">Amount (SAR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $pay): ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($pay['payment_date'])); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($pay['company_name']); ?>
                                        <div class="ref-sub">Ref:
                                            #<?php echo str_pad($pay['payment_id'], 6, "0", STR_PAD_LEFT); ?></div>
                                    </td>
                                    <td><?php echo htmlspecialchars($pay['payment_method']); ?></td>
                                    <td class="text-right"><?php echo number_format($pay['amount'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>

                <div class="total-bar">
                    <div class="total-label">Total Amount Paid (Completed)</div>
                    <div class="total-amount">SAR <?php echo number_format($total_amount, 2); ?></div>
                </div>

            </div>
            <div class="invoice-footer">
                <p><strong>Thank you for your business!</strong></p>
                <p>Scan the QR code at the top to verify the authenticity of this document online.</p>
                <p>Fayenor Company Limited · Unaizah, Al-Qassim, KSA · info@fayenor.com</p>
            </div>

        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        (function () {
            var btn = document.getElementById('btnDownloadPdf');
            if (!btn) return;

            function buildWhiteImageMap(root) {
                var promises = Array.from(root.querySelectorAll('img')).map(function (img) {
                    if (!(img.getAttribute('style') || '').includes('invert')) return Promise.resolve(null);
                    return new Promise(function (resolve) {
                        function process(src) {
                            var tmp = new Image();
                            tmp.crossOrigin = 'anonymous';
                            tmp.onload = function () {
                                var c = document.createElement('canvas');
                                c.width = tmp.naturalWidth || 100;
                                c.height = tmp.naturalHeight || 50;
                                var ctx = c.getContext('2d');
                                ctx.drawImage(tmp, 0, 0);
                                var id = ctx.getImageData(0, 0, c.width, c.height);
                                for (var i = 0; i < id.data.length; i += 4) {
                                    if (id.data[i + 3] > 0) {
                                        id.data[i] = id.data[i + 1] = id.data[i + 2] = 255;
                                    }
                                }
                                ctx.putImageData(id, 0, 0);
                                resolve({ img: img, dataUrl: c.toDataURL('image/png') });
                            };
                            tmp.onerror = function () { resolve(null); };
                            tmp.src = src;
                        }
                        if (img.complete && img.naturalWidth > 0) {
                            process(img.src);
                        } else {
                            img.addEventListener('load', function () { process(img.src); }, { once: true });
                        }
                    });
                });
                return Promise.all(promises).then(function (results) {
                    var map = new Map();
                    results.forEach(function (r) { if (r) map.set(r.img, r.dataUrl); });
                    return map;
                });
            }

            function doDownload() {
                var invoiceEl = document.querySelector('.invoice');
                if (!invoiceEl) return;

                btn.disabled = true;
                btn.textContent = 'Generating...';

                var A4_H_PX = 1123;

                buildWhiteImageMap(invoiceEl).then(function (whiteMap) {
                    return html2canvas(invoiceEl, {
                        scale: 2,
                        useCORS: true,
                        logging: false,
                        onclone: function (clonedDoc) {
                            var inv = clonedDoc.querySelector('.invoice');
                            var body = clonedDoc.querySelector('.invoice-body');

                            if (inv) {
                                inv.style.display = 'flex';
                                inv.style.flexDirection = 'column';
                                var naturalH = inv.scrollHeight || inv.offsetHeight || 0;
                                inv.style.minHeight = Math.max(naturalH, A4_H_PX) + 'px';
                                inv.style.boxShadow = 'none';
                                inv.style.borderRadius = '0';
                            }
                            if (body) {
                                body.style.flex = '1';
                            }

                            clonedDoc.querySelectorAll('img').forEach(function (clonedImg) {
                                whiteMap.forEach(function (dataUrl, originalImg) {
                                    if (clonedImg.src === originalImg.src || clonedImg.getAttribute('src') === originalImg.getAttribute('src')) {
                                        clonedImg.src = dataUrl;
                                        clonedImg.style.filter = 'none';
                                    }
                                });
                            });
                        }
                    });
                }).then(function (canvas) {
                    var imgData = canvas.toDataURL('image/jpeg', 0.95);
                    var pdfW = 210;
                    var pdfH = 297;
                    var imgW = pdfW;
                    var imgH = (canvas.height / canvas.width) * pdfW;

                    var jsPDF = window.jspdf.jsPDF;
                    var doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });

                    var yOffset = 0;
                    while (yOffset < imgH) {
                        if (yOffset > 0) doc.addPage();
                        doc.addImage(imgData, 'JPEG', 0, -yOffset, imgW, imgH);
                        yOffset += pdfH;
                    }

                    doc.save('<?php echo addslashes($invoice_no); ?>.pdf');
                    btn.disabled = false;
                    btn.innerHTML = '&#x2B73; Download PDF';
                }).catch(function (err) {
                    console.error('PDF generation failed:', err);
                    btn.disabled = false;
                    btn.innerHTML = '&#x2B73; Download PDF';
                });
            }

            btn.addEventListener('click', doDownload);

            if (new URLSearchParams(window.location.search).get('download') === '1') {
                window.addEventListener('load', function () {
                    setTimeout(doDownload, 600);
                });
            }
        })();
    </script>

    <div id="google_translate_element"></div>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({ pageLanguage: 'en', includedLanguages: 'ar', autoDisplay: false }, 'google_translate_element');
        }

        <?php if ($is_rtl): ?>
            document.addEventListener("DOMContentLoaded", function () {
                const convertToArabicNumerals = (node) => {
                    if (node.nodeType === 3) {
                        if (/[0-9]/.test(node.nodeValue)) {
                            node.nodeValue = node.nodeValue.replace(/[0-9]/g, w => String.fromCharCode(w.charCodeAt(0) + 1584));
                        }
                    } else if (node.nodeType === 1 && !['SCRIPT', 'STYLE', 'INPUT', 'TEXTAREA', 'SELECT', 'CODE'].includes(node.tagName)) {
                        for (let child of node.childNodes) {
                            convertToArabicNumerals(child);
                        }
                    }
                };
                convertToArabicNumerals(document.body);
                new MutationObserver((mutations) => {
                    mutations.forEach(m => {
                        if (m.type === 'childList') {
                            m.addedNodes.forEach(addedNode => {
                                if (addedNode.nodeType === 1 || addedNode.nodeType === 3) convertToArabicNumerals(addedNode);
                            });
                        } else if (m.type === 'characterData') {
                            convertToArabicNumerals(m.target);
                        }
                    });
                }).observe(document.body, { childList: true, subtree: true, characterData: true });
            });
        <?php endif; ?>
    </script>
</body>

</html>