<x-guest-layout>
    <div class="mb-4 text-muted">
        Uygulamanın güvenli bir alanıdır. Devam etmeden önce lütfen şifrenizi onaylayın.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Şifre</label>
            <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
            @error('password')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                Onayla
            </button>
        </div>
    </form>
</x-guest-layout>
