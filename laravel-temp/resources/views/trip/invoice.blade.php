<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $booking['booking_code'] }} — Tapak Lampung</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        :root{
            --green:#1a7a4a;--green-light:#e8f5ee;--green-dark:#0f4a2c;
            --text:#0d1f17;--text-2:#4a6a58;--text-3:#8aaa97;
            --border:#dce8e1;--bg:#f4f7f5;
            --gold:#a07828;--gold-light:#fdf3e0;
        }
        html{font-size:14px;}
        body{font-family:'DM Sans',sans-serif;background:#fff;color:var(--text);-webkit-print-color-adjust:exact;print-color-adjust:exact;}

        /* ── SCREEN WRAPPER ── */
        .screen-wrapper{min-height:100vh;background:var(--bg);padding:2rem 1rem 4rem;display:flex;flex-direction:column;align-items:center;}
        .screen-actions{display:flex;gap:10px;margin-bottom:1.5rem;width:100%;max-width:720px;}
        .btn-print{display:inline-flex;align-items:center;gap:7px;padding:10px 20px;background:linear-gradient(135deg,var(--green),#25a066);color:#fff;border:none;border-radius:10px;font-family:'Outfit',sans-serif;font-size:13.5px;font-weight:600;cursor:pointer;transition:all .2s;box-shadow:0 3px 12px rgba(26,122,74,.25);}
        .btn-print:hover{transform:translateY(-1px);box-shadow:0 5px 18px rgba(26,122,74,.35);}
        .btn-print svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
        .btn-back{display:inline-flex;align-items:center;gap:7px;padding:10px 18px;background:#fff;color:var(--text-2);border:1.5px solid var(--border);border-radius:10px;font-size:13px;font-weight:500;text-decoration:none;transition:all .2s;}
        .btn-back:hover{border-color:var(--green);color:var(--green);}
        .btn-back svg{width:15px;height:15px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}

        /* ── INVOICE PAPER ── */
        .invoice{background:#fff;width:100%;max-width:720px;border-radius:20px;box-shadow:0 8px 40px rgba(0,0,0,.08);overflow:hidden;}

        /* ── HEADER ── */
        .invoice-header{background:linear-gradient(135deg,var(--green-dark) 0%,var(--green) 60%,#2ecc7a 100%);padding:2.5rem 2.5rem 2rem;position:relative;overflow:hidden;}
        .invoice-header::before{content:'';position:absolute;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,.05);top:-100px;right:-80px;}
        .invoice-header::after{content:'';position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.04);bottom:-80px;left:-60px;}
        .header-top{display:flex;justify-content:space-between;align-items:flex-start;position:relative;z-index:1;}
        .brand{display:flex;align-items:center;gap:10px;}
        .brand-icon{width:40px;height:40px;background:rgba(255,255,255,.2);backdrop-filter:blur(4px);border-radius:11px;display:flex;align-items:center;justify-content:center;border:1.5px solid rgba(255,255,255,.3);}
        .brand-icon svg{width:20px;height:20px;stroke:#fff;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
        .brand-name{font-family:'Outfit',sans-serif;font-weight:700;font-size:18px;color:#fff;}
        .brand-sub{font-size:11px;color:rgba(255,255,255,.65);}
        .invoice-label{text-align:right;}
        .invoice-label .inv-title{font-family:'Outfit',sans-serif;font-size:22px;font-weight:800;color:#fff;letter-spacing:-.3px;}
        .invoice-label .inv-no{font-size:12px;color:rgba(255,255,255,.7);margin-top:3px;font-family:monospace;letter-spacing:.05em;}
        .header-divider{border:none;border-top:1px solid rgba(255,255,255,.15);margin:1.5rem 0 1.25rem;position:relative;z-index:1;}
        .header-meta{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;position:relative;z-index:1;}
        .meta-item{}
        .meta-label{font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.55);margin-bottom:3px;}
        .meta-value{font-size:13px;font-weight:600;color:#fff;}

        /* ── STATUS BADGE ── */
        .status-section{padding:1.25rem 2.5rem;background:var(--green-light);display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--border);}
        .status-icon{width:36px;height:36px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 8px rgba(26,122,74,.15);}
        .status-icon svg{width:18px;height:18px;stroke:var(--green);fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round;}
        .status-text{flex:1;}
        .status-title{font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:var(--green);}
        .status-desc{font-size:11.5px;color:var(--text-2);}
        .status-badge{padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;background:var(--green);color:#fff;text-transform:uppercase;letter-spacing:.06em;}

        /* ── BODY ── */
        .invoice-body{padding:2rem 2.5rem;}

        /* ── PARTIES ── */
        .parties{display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem;padding-bottom:2rem;border-bottom:1px solid var(--border);}
        .party-label{font-size:10px;text-transform:uppercase;letter-spacing:.1em;color:var(--text-3);margin-bottom:.5rem;font-weight:600;}
        .party-name{font-family:'Outfit',sans-serif;font-size:16px;font-weight:700;color:var(--text);margin-bottom:.25rem;}
        .party-detail{font-size:12.5px;color:var(--text-2);line-height:1.6;}

        /* ── ITEMS TABLE ── */
        .section-title{font-family:'Outfit',sans-serif;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-3);margin-bottom:1rem;}
        .items-table{width:100%;border-collapse:collapse;margin-bottom:1.5rem;}
        .items-table th{padding:10px 14px;text-align:left;font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--text-3);background:var(--bg);border-bottom:2px solid var(--border);}
        .items-table th:last-child{text-align:right;}
        .items-table td{padding:13px 14px;font-size:13.5px;border-bottom:1px solid var(--border);vertical-align:middle;}
        .items-table td:last-child{text-align:right;font-weight:600;}
        .items-table tr:last-child td{border-bottom:none;}
        .item-name{font-weight:600;color:var(--text);}
        .item-desc{font-size:11.5px;color:var(--text-3);margin-top:2px;}

        /* ── TOTALS ── */
        .totals-wrap{display:flex;justify-content:flex-end;margin-bottom:2rem;}
        .totals{width:280px;}
        .total-row{display:flex;justify-content:space-between;padding:7px 0;font-size:13px;}
        .total-row:not(:last-child){border-bottom:1px dashed var(--border);}
        .total-row .lbl{color:var(--text-2);}
        .total-row .val{font-weight:500;}
        .total-row.grand{border-top:2px solid var(--green);border-bottom:none;padding-top:12px;margin-top:4px;}
        .total-row.grand .lbl{font-family:'Outfit',sans-serif;font-weight:700;font-size:14px;color:var(--text);}
        .total-row.grand .val{font-family:'Outfit',sans-serif;font-weight:800;font-size:16px;color:var(--green);}

        /* ── INFO BOXES ── */
        .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:2rem;}
        .info-box{background:var(--bg);border:1px solid var(--border);border-radius:12px;padding:1rem 1.2rem;}
        .info-box-title{font-size:11px;text-transform:uppercase;letter-spacing:.08em;font-weight:700;color:var(--text-3);margin-bottom:.6rem;}
        .info-row{display:flex;justify-content:space-between;font-size:12.5px;padding:4px 0;}
        .info-row .k{color:var(--text-2);}
        .info-row .v{font-weight:500;color:var(--text);}

        /* ── PAYMENT INFO ── */
        .payment-note{background:var(--gold-light);border:1px solid rgba(160,120,40,.2);border-radius:12px;padding:1.1rem 1.3rem;margin-bottom:2rem;display:flex;gap:.8rem;align-items:flex-start;}
        .payment-note svg{width:17px;height:17px;stroke:#a07828;fill:none;stroke-width:2;flex-shrink:0;margin-top:.1rem;}
        .payment-note-text{font-size:12.5px;color:#6b5000;line-height:1.6;}
        .payment-note-text strong{color:#a07828;}

        /* ── FOOTER ── */
        .invoice-footer{padding:1.5rem 2.5rem;background:var(--bg);border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
        .footer-brand{font-family:'Outfit',sans-serif;font-size:13px;font-weight:700;color:var(--green);}
        .footer-tagline{font-size:11px;color:var(--text-3);margin-top:2px;}
        .footer-qr{text-align:right;}
        .footer-qr .qr-label{font-size:10px;color:var(--text-3);margin-bottom:4px;}
        .footer-note{font-size:11px;color:var(--text-3);text-align:center;margin-top:1rem;padding:0 2.5rem 1.5rem;}

        /* ── PRINT ── */
        @media print{
            .screen-wrapper{background:#fff;padding:0;}
            .screen-actions{display:none;}
            .invoice{box-shadow:none;border-radius:0;max-width:100%;}
            .invoice-header{-webkit-print-color-adjust:exact;}
            .status-section{-webkit-print-color-adjust:exact;}
        }

        @media(max-width:600px){
            .invoice-header{padding:1.5rem;}
            .invoice-body{padding:1.5rem;}
            .parties{grid-template-columns:1fr;}
            .info-grid{grid-template-columns:1fr;}
            .totals{width:100%;}
            .header-meta{grid-template-columns:1fr 1fr;}
            .invoice-footer{flex-direction:column;gap:.5rem;text-align:center;}
        }
    </style>
</head>
<body>
<div class="screen-wrapper">
    {{-- Action Buttons (tidak ikut print) --}}
    <div class="screen-actions">
        <a href="{{ session('invoice_back_url', route('home')) }}" class="btn-back">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali
        </a>
        <button class="btn-print" onclick="window.print()">
            <svg viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Cetak / Simpan PDF
        </button>
        <button class="btn-print" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);box-shadow:0 3px 12px rgba(37,99,235,.25);" onclick="downloadPDF()">
            <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download PDF
        </button>
    </div>

    {{-- Invoice Paper --}}
    <div class="invoice" id="invoice-content">

        {{-- Header --}}
        <div class="invoice-header">
            <div class="header-top">
                <div class="brand">
                    <div class="brand-icon">
                        <svg viewBox="0 0 24 24"><path d="M3 20L12 4L21 20"/><circle cx="12" cy="14" r="2.5"/><line x1="7" y1="17" x2="17" y2="17"/></svg>
                    </div>
                    <div>
                        <div class="brand-name">Tapak Lampung</div>
                        <div class="brand-sub">Platform Pariwisata Lampung</div>
                    </div>
                </div>
                <div class="invoice-label">
                    <div class="inv-title">KWITANSI</div>
                    <div class="inv-no">{{ $booking['booking_code'] }}</div>
                </div>
            </div>
            <hr class="header-divider">
            <div class="header-meta">
                <div class="meta-item">
                    <div class="meta-label">Tanggal Booking</div>
                    <div class="meta-value">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Jenis Layanan</div>
                    <div class="meta-value">{{ $booking['type'] ?? 'Open Trip' }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Status Pembayaran</div>
                    <div class="meta-value">⏳ Menunggu Konfirmasi</div>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="status-section">
            <div class="status-icon">
                <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="status-text">
                <div class="status-title">Permintaan Booking Diterima</div>
                <div class="status-desc">Tim kami akan menghubungi Anda dalam 1×24 jam untuk konfirmasi pembayaran</div>
            </div>
            <div class="status-badge">Pending</div>
        </div>

        {{-- Body --}}
        <div class="invoice-body">

            {{-- Parties --}}
            <div class="parties">
                <div>
                    <div class="party-label">Dari</div>
                    <div class="party-name">Tapak Lampung</div>
                    <div class="party-detail">
                        Platform Pariwisata Terintegrasi<br>
                        Provinsi Lampung, Indonesia<br>
                        support@tapaklampung.com
                    </div>
                </div>
                <div>
                    <div class="party-label">Kepada</div>
                    <div class="party-name">{{ $booking['name'] }}</div>
                    <div class="party-detail">
                        📱 {{ $booking['phone'] }}<br>
                        ✉️ {{ $booking['email'] }}
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="section-title">Rincian Pemesanan</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Deskripsi</th>
                        <th>Qty</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="color:var(--text-3);">1</td>
                        <td>
                            <div class="item-name">{{ $booking['trip_name'] ?? $booking['item_name'] }}</div>
                            <div class="item-desc">
                                @if(isset($booking['organizer']))
                                    Organizer: {{ $booking['organizer'] }} &nbsp;·&nbsp;
                                @endif
                                @if(isset($booking['schedule']))
                                    Jadwal: {{ \Carbon\Carbon::parse($booking['schedule'])->locale('id')->isoFormat('D MMMM YYYY') }} &nbsp;·&nbsp;
                                @endif
                                @if(isset($booking['duration']))
                                    {{ $booking['duration'] }}
                                @endif
                            </div>
                        </td>
                        <td>{{ $booking['participants'] ?? $booking['qty'] ?? 1 }} orang</td>
                        <td>Rp {{ number_format(($booking['total_price'] / ($booking['participants'] ?? 1)), 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($booking['total_price'], 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Totals --}}
            <div class="totals-wrap">
                <div class="totals">
                    <div class="total-row">
                        <span class="lbl">Subtotal</span>
                        <span class="val">Rp {{ number_format($booking['total_price'], 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span class="lbl">Biaya Layanan</span>
                        <span class="val" style="color:var(--green);">Gratis</span>
                    </div>
                    <div class="total-row">
                        <span class="lbl">Diskon</span>
                        <span class="val" style="color:var(--green);">—</span>
                    </div>
                    <div class="total-row grand">
                        <span class="lbl">Total Pembayaran</span>
                        <span class="val">Rp {{ number_format($booking['total_price'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Info Boxes --}}
            <div class="info-grid">
                @if(isset($booking['schedule']))
                <div class="info-box">
                    <div class="info-box-title">📅 Detail Trip</div>
                    <div class="info-row"><span class="k">Jadwal</span><span class="v">{{ \Carbon\Carbon::parse($booking['schedule'])->locale('id')->isoFormat('D MMMM YYYY') }}</span></div>
                    @if(isset($booking['duration']))<div class="info-row"><span class="k">Durasi</span><span class="v">{{ $booking['duration'] }}</span></div>@endif
                    @if(isset($booking['organizer']))<div class="info-row"><span class="k">Organizer</span><span class="v">{{ $booking['organizer'] }}</span></div>@endif
                    <div class="info-row"><span class="k">Peserta</span><span class="v">{{ $booking['participants'] ?? 1 }} orang</span></div>
                </div>
                @endif
                <div class="info-box">
                    <div class="info-box-title">💳 Cara Pembayaran</div>
                    <div class="info-row"><span class="k">Transfer Bank</span><span class="v">BCA / Mandiri</span></div>
                    <div class="info-row"><span class="k">E-Wallet</span><span class="v">GoPay / OVO / DANA</span></div>
                    <div class="info-row"><span class="k">Konfirmasi</span><span class="v">via WhatsApp</span></div>
                    <div class="info-row"><span class="k">Batas Bayar</span><span class="v">1×24 jam</span></div>
                </div>
            </div>

            {{-- Payment Note --}}
            <div class="payment-note">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div class="payment-note-text">
                    <strong>Simpan kwitansi ini sebagai bukti pemesanan.</strong> Organizer akan menghubungi nomor <strong>{{ $booking['phone'] }}</strong> via WhatsApp untuk instruksi pembayaran. Kode booking <strong>{{ $booking['booking_code'] }}</strong> wajib disebutkan saat konfirmasi. Pembayaran penuh wajib dilakukan dalam <strong>1×24 jam</strong> setelah konfirmasi.
                </div>
            </div>

        </div>{{-- end invoice-body --}}

        {{-- Footer --}}
        <div class="invoice-footer">
            <div>
                <div class="footer-brand">Tapak Lampung</div>
                <div class="footer-tagline">Jelajahi Keindahan Tersembunyi Lampung · tapaklampung.com</div>
            </div>
            <div>
                <div style="font-size:11px;color:var(--text-3);">Kode Booking</div>
                <div style="font-family:monospace;font-size:15px;font-weight:700;color:var(--green);letter-spacing:.1em;">{{ $booking['booking_code'] }}</div>
            </div>
        </div>
        <div class="footer-note">
            Kwitansi ini diterbitkan secara otomatis oleh sistem Tapak Lampung. Untuk pertanyaan, hubungi support@tapaklampung.com
        </div>

    </div>{{-- end .invoice --}}
</div>

<script>
function downloadPDF() {
    window.print();
}
</script>
</body>
</html>
