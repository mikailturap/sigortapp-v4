<!DOCTYPE html>
<html>
<head>
    <title>Poliçe Detayı - {{ $policy->policy_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 20px;
            border: 1px solid #eee;
            padding: 10px;
        }
        .section-title {
            background-color: #f2f2f2;
            padding: 5px;
            margin-top: 0;
            margin-bottom: 10px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td, table th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .status-aktif {
            color: green;
            font-weight: bold;
        }
        .status-pasif {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Poliçe Detayı</h1>
            <h2>Poliçe No: {{ $policy->policy_number }}</h2>
        </div>

        <div class="section">
            <h3 class="section-title">Müşteri Bilgileri</h3>
            <table>
                <tr><td><strong>Müşteri Ünvan:</strong></td><td>{{ $policy->customer_title }}</td></tr>
                <tr><td><strong>TC/Vergi No:</strong></td><td>{{ $policy->customer_identity_number }}</td></tr>
                <tr><td><strong>Müşteri Telefon:</strong></td><td>{{ $policy->customer_phone }}</td></tr>
                <tr><td><strong>Doğum Tarihi:</strong></td><td>{{ $policy->customer_birth_date }}</td></tr>
                <tr><td><strong>Adres:</strong></td><td>{{ $policy->customer_address }}</td></tr>
            </table>
        </div>

        <div class="section">
            <h3 class="section-title">Sigorta Ettiren Bilgileri</h3>
            <table>
                <tr><td><strong>Sigorta Ettiren Ünvan:</strong></td><td>{{ $policy->insured_name ?? 'Müşteri ile aynı' }}</td></tr>
                <tr><td><strong>Sigorta Ettiren Telefon:</strong></td><td>{{ $policy->insured_phone ?? 'Müşteri ile aynı' }}</td></tr>
            </table>
        </div>

        <div class="section">
            <h3 class="section-title">Poliçe Detayları</h3>
            <table>
                <tr><td><strong>Poliçe Türü:</strong></td><td>{{ $policy->policy_type }}</td></tr>
                <tr><td><strong>Poliçe Şirketi:</strong></td><td>{{ $policy->policy_company ?? '-' }}</td></tr>
                <tr><td><strong>Poliçe No:</strong></td><td>{{ $policy->policy_number }}</td></tr>
                <tr><td><strong>Plaka/Diğer:</strong></td><td>{{ $policy->plate_or_other ?? '-' }}</td></tr>
                <tr><td><strong>Tanzim Tarihi:</strong></td><td>{{ $policy->issue_date }}</td></tr>
                <tr><td><strong>Başlangıç Tarihi:</strong></td><td>{{ $policy->start_date }}</td></tr>
                <tr><td><strong>Bitiş Tarihi:</strong></td><td>{{ $policy->end_date }}</td></tr>
                <tr><td><strong>Belge Seri/Diğer/UAVT:</strong></td><td>{{ $policy->document_info ?? '-' }}</td></tr>
            </table>
        </div>

        @if($policy->policy_type == 'TARSİM')
            <div class="section">
                <h3 class="section-title">TARSİM Bilgileri</h3>
                <table>
                    <tr><td><strong>TARSİM İşletme No:</strong></td><td>{{ $policy->tarsim_business_number ?? '-' }}</td></tr>
                    <tr><td><strong>TARSİM Hayvan No:</strong></td><td>{{ $policy->tarsim_animal_number ?? '-' }}</td></tr>
                </table>
            </div>
        @endif

        <div class="section">
            <h3 class="section-title">Durum</h3>
            <table>
                <tr>
                    <td><strong>Durum:</strong></td>
                    <td>
                        @if($policy->status == 'aktif')
                            <span class="status-aktif">Aktif</span>
                        @else
                            <span class="status-pasif">Pasif</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
