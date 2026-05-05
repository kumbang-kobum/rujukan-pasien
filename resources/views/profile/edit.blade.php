@extends('layouts.app')
@section('title', 'Profil')

@section('content')
<div class="row g-3">
  <div class="col-12 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <i class="fas fa-user me-2"></i> Informasi Profil
      </div>
      <div class="card-body">
        @include('profile.partials.update-profile-information-form')
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header bg-secondary text-white">
        <i class="fas fa-key me-2"></i> Ubah Password
      </div>
      <div class="card-body">
        @include('profile.partials.update-password-form')
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card border-danger shadow-sm">
      <div class="card-header bg-danger text-white">
        <i class="fas fa-user-times me-2"></i> Hapus Akun
      </div>
      <div class="card-body">
        @include('profile.partials.delete-user-form')
      </div>
    </div>
  </div>
</div>
@endsection
