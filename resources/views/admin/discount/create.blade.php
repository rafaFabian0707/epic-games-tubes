{{-- resources/views/admin/discounts/create.blade.php --}}
@extends('layouts.admin')
@section('title','Buat Diskon Baru')
@section('breadcrumb','Buat Diskon')

@section('content')
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('admin.discounts.index') }}"
       class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-xl font-bold text-white">Buat Diskon Baru</h1>
        <p class="text-gray-500 text-xs mt-0.5">Tentukan game, persentase, dan periode diskon</p>
    </div>
</div>

@if($errors->any())
<div class="bg-red-900/20 border border-red-700/40 rounded-2xl p-4 mb-5">
    <p class="text-sm font-semibold text-red-300 mb-2">{{ $errors->count() }} kesalahan validasi:</p>
    <ul class="space-y-1 pl-5">
        @foreach($errors->all() as $e)<li class="text-xs text-red-400 list-disc">{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.discounts.store') }}">
    @csrf
    @include('admin.discounts._form')
</form>
@endsection
