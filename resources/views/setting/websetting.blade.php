@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Setting Web</h4>

                    <div class="table-responsive pt-3">
                        <form action="{{ route('websetting.update', $settings->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Menampilkan pesan error -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Rumah Sakit</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $settings->name) }}">
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $settings->title) }}">
                            </div>
                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo</label><br>
                                @if($settings->logo)
                                    <img src="{{ asset($settings->logo) }}" alt="Logo" width="100">
                                @else
                                    <p class="text-danger"><b>Logo belum ada</b></p>
                                @endif
                                <input type="file" name="logo" class="form-control" id="logo">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $settings->email) }}">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telepon</label>
                                <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', $settings->phone) }}">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <input type="text" name="address" class="form-control" id="address" value="{{ old('address', $settings->address) }}">
                            </div>

                            <!-- Social Media -->
                            @foreach (['facebook', 'instagram', 'twitter', 'youtube', 'website'] as $field)
                                <div class="mb-3">
                                    <label for="{{ $field }}" class="form-label">{{ ucfirst($field) }}</label>
                                    <input type="text" name="{{ $field }}" class="form-control" id="{{ $field }}" value="{{ old($field, $settings->$field) }}">
                                </div>
                            @endforeach

                            <!-- Carousel Images -->
                            @foreach (['coursellink1' => 'Carousel 1', 'coursellink2' => 'Carousel 2', 'coursellink3' => 'Carousel 3'] as $field => $label)
                                <div class="mb-3">
                                    <label for="{{ $field }}" class="form-label">{{ $label }}</label><br>
                                    @if($settings->$field)
                                        <img src="{{ asset($settings->$field) }}" alt="{{ $label }}" width="100">
                                    @else
                                        <p class="text-danger"><b>{{ $label }} belum ada</b></p>
                                    @endif
                                    <input type="file" name="{{ $field }}" class="form-control" id="{{ $field }}">
                                </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
