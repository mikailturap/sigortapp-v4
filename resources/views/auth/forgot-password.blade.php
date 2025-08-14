<x-guest-layout>
    <div class="mb-4 text-muted">
        Şifrenizi mi unuttunuz? Sorun değil. Sadece e-posta adresinizi bize bildirin, size yeni bir şifre seçmenize olanak tanıyan bir şifre sıfırlama bağlantısı göndereceğiz.
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-3" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                Şifre Sıfırlama Bağlantısı Gönder
            </button>
        </div>
    </form>
</x-guest-layout>
