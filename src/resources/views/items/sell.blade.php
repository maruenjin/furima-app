@extends('layouts.app')
@section('title','商品を出品')

@section('content')
<div class="container" style="max-width:720px;">
  <h1 style="font-size:20px;margin:0 0 16px;">商品を出品</h1>

  @if (session('status'))
    <div style="background:#e8f5e9;border:1px solid #c8e6c9;padding:8px 12px;border-radius:6px;margin-bottom:12px;">
      {{ session('status') }}
    </div>
  @endif

  <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" style="display:grid;gap:14px;">
    @csrf

    {{-- 商品画像（プレビュー付き） --}}
    <div>
      <label style="display:block;margin-bottom:6px;">商品画像</label>
      <div style="border:1px dashed #ccc;border-radius:8px;padding:16px;text-align:center;">
        <img id="preview" src="{{ asset('images/noimage.png') }}" alt="" style="max-width:100%;max-height:220px;display:block;margin:0 auto 8px;object-fit:contain;">
        <input type="file" name="image" accept="image/*" onchange="previewImage(event)">
        @error('image')<div style="color:#e53935;font-size:12px;">{{ $message }}</div>@enderror
        <div style="font-size:12px;color:#666;">4MB 以下の jpg/png/webp/gif</div>
      </div>
    </div>

    {{-- カテゴリ（複数） --}}
    <div>
      <label style="display:block;margin-bottom:6px;">商品の詳細（カテゴリ）</label>
      <div style="display:flex;flex-wrap:wrap;gap:8px;">
        @foreach ($categories as $cat)
          <label style="display:inline-flex;align-items:center;gap:6px;border:1px solid #ddd;border-radius:999px;padding:4px 10px;cursor:pointer;">
            <input type="checkbox" name="categories[]" value="{{ $cat }}" {{ in_array($cat, old('categories',[]))?'checked':'' }}>
            <span style="font-size:12px;">{{ $cat }}</span>
          </label>
        @endforeach
      </div>
      @error('categories')<div style="color:#e53935;font-size:12px;">{{ $message }}</div>@enderror
    </div>

    {{-- 商品の状態 --}}
    <div>
      <label>商品の状態</label>
      <select name="condition" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        <option value="">選択してください</option>
        @foreach (['新品未使用','未使用に近い','良好','やや傷や汚れあり','状態が悪い'] as $opt)
          <option value="{{ $opt }}" {{ old('condition')===$opt?'selected':'' }}>{{ $opt }}</option>
        @endforeach
      </select>
      @error('condition')<div style="color:#e53935;font-size:12px;">{{ $message }}</div>@enderror
    </div>

    {{-- 商品名 --}}
    <div>
      <label>商品名</label>
      <input type="text" name="name" value="{{ old('name') }}"
             style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
      @error('name')<div style="color:#e53935;font-size:12px;">{{ $message }}</div>@enderror
    </div>

    {{-- ブランド名 --}}
    <div>
      <label>ブランド名</label>
      <input type="text" name="brand" value="{{ old('brand') }}"
             style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
      @error('brand')<div style="color:#e53935;font-size:12px;">{{ $message }}</div>@enderror
    </div>

    {{-- 商品の説明 --}}
    <div>
      <label>商品の説明</label>
      <textarea name="description" rows="5" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">{{ old('description') }}</textarea>
      @error('description')<div style="color:#e53935;font-size:12px;">{{ $message }}</div>@enderror
    </div>

    {{-- 販売価格 --}}
    <div>
      <label>販売価格</label>
      <input type="number" name="price" value="{{ old('price') }}"
             style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
      @error('price')<div style="color:#e53935;font-size:12px;">{{ $message }}</div>@enderror
    </div>

    <div>
      <button class="btn-primary-red">出品する</button>
      
    </div>
  </form>
</div>

@push('styles')
<style>
/* ざっくりボタン色（既存の .btn-primary-red があれば不要） */
.btn-primary-red{background:#ff6b6b;color:#fff;border:none;border-radius:6px;padding:10px 16px;cursor:pointer;font-weight:600}
.btn-primary-red:hover{opacity:.92}
</style>
@endpush

<script>
function previewImage(e){
  const file = e.target.files?.[0];
  if(!file) return;
  const img = document.getElementById('preview');
  img.src = URL.createObjectURL(file);
}
</script>
@endsection
