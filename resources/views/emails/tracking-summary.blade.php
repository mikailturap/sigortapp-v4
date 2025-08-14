<p>Merhaba,</p>
<p>Aşağıda günlük poliçe takip özeti yer almaktadır:</p>
<ul>
    <li>Bugün Sona Erenler: {{ $counts['today'] ?? 0 }}</li>
    <li>Yaklaşan Yenilemeler: {{ $counts['upcoming'] ?? 0 }}</li>
    <li>Süresi Geçenler: {{ $counts['expired'] ?? 0 }}</li>
    <li>Toplam Aktif: {{ $counts['active'] ?? 0 }}</li>
    <li>Tarih: {{ now()->format('Y-m-d') }}</li>
    <li>Rapor oluşturma saati: {{ now()->format('H:i') }}</li>
    <li>Not: Detaylar ekte gönderilmiştir.</li>
 </ul>
<p>İyi çalışmalar.</p>


