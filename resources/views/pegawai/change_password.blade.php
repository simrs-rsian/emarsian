@extends('includeView.layout')
@section('title', 'Ganti Password Dahulu')

@section('content')
<div class="col-12 grid-margin">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Ganti Password Default</h4>

      @if (session('status'))
        <div class="alert alert-success mb-3">
          {{ session('status') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger mb-3">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('pegawai.update_password') }}" method="POST" novalidate>
        @csrf

        {{-- PASSWORD LAMA --}}
        <div class="form-group">
          <label for="old_password">Password Lama</label>
          <div class="input-group">
            <input type="password" name="old_password" id="old_password" class="form-control" required>
            <div class="input-group-append">
              <button type="button" class="btn btn-outline-secondary toggle-password" data-target="old_password">👁</button>
            </div>
          </div>
        </div>

        {{-- PASSWORD BARU --}}
        <div class="form-group">
          <label for="new_password">Password Baru</label>
          <div class="input-group">
            <input 
              type="password" 
              name="new_password" 
              id="new_password" 
              class="form-control" 
              required 
              minlength="6"
              pattern="^(?=.*[a-z])(?=.*[A-Z]).+$"
              title="Harus ada huruf besar dan kecil, minimal 6 karakter">
            <div class="input-group-append">
              <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password">👁</button>
            </div>
          </div>
          <small class="form-text text-muted">Minimal 6 karakter dan harus ada huruf besar dan kecil.</small>
          <div id="password-strength" class="mt-2"></div>
        </div>

        {{-- KONFIRMASI PASSWORD --}}
        <div class="form-group">
          <label for="new_password_confirmation">Konfirmasi Password Baru</label>
          <div class="input-group">
            <input 
              type="password" 
              name="new_password_confirmation" 
              id="new_password_confirmation" 
              class="form-control" 
              required 
              minlength="6">
            <div class="input-group-append">
              <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password_confirmation">👁</button>
            </div>
          </div>
        </div>

        <div class="text-left mt-4">
          <button type="submit" class="btn btn-success">Simpan Password Baru</button>
          <a href="{{ route('pegawai.profile') }}" class="btn btn-light">Kembali</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Show/Hide Password
  document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function () {
      const input = document.getElementById(this.dataset.target);
      input.type = input.type === 'password' ? 'text' : 'password';
      this.textContent = input.type === 'password' ? '👁' : '🙈';
    });
  });

  // Live validation style
  document.querySelectorAll('input[type="password"]').forEach(input => {
    input.addEventListener('input', () => {
      if (input.checkValidity()) {
        input.classList.add('is-valid');
        input.classList.remove('is-invalid');
      } else {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
      }
    });
  });

  // Password Strength Meter
  const strengthDisplay = document.getElementById('password-strength');
  document.getElementById('new_password').addEventListener('input', function () {
    const val = this.value;
    let strength = 0;
    if (val.length >= 6) strength += 1;
    if (/[a-z]/.test(val)) strength += 1;
    if (/[A-Z]/.test(val)) strength += 1;
    if (/\d/.test(val)) strength += 1;
    if (/[@$!%*#?&]/.test(val)) strength += 1;

    let strengthText = '';
    let strengthColor = '';

    if (strength <= 2) {
      strengthText = 'Lemah';
      strengthColor = 'text-danger';
    } else if (strength <= 4) {
      strengthText = 'Sedang';
      strengthColor = 'text-warning';
    } else {
      strengthText = 'Kuat';
      strengthColor = 'text-success';
    }

    strengthDisplay.innerHTML = `<strong class="${strengthColor}">Kekuatan Password: ${strengthText}</strong>`;
  });
</script>
@endsection
