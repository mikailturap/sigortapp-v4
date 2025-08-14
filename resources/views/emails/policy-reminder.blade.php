<h1>Poliçe Hatırlatıcısı</h1>

<p>Merhaba,</p>

<p>Aşağıdaki poliçenizin bitiş tarihi yaklaşıyor:</p>

<ul>
    <li><strong>Poliçe Numarası:</strong> {{ $policy->policy_number }}</li>
    <li><strong>Müşteri Ünvanı:</strong> {{ $policy->customer_title }}</li>
    <li><strong>Poliçe Türü:</strong> {{ $policy->policy_type }}</li>
    <li><strong>Bitiş Tarihi:</strong> {{ $policy->end_date->format('d.m.Y') }}</li>
</ul>

<p>Detaylar için lütfen sistemimize giriş yapın.</p>

<p>Saygılarımızla,</p>
<p>Sigortapp Ekibi</p>
