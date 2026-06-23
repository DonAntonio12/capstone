@extends('admin.layout')

@section('content')
<h2 style="font-size:2rem;font-weight:700;color:#222;margin-bottom:2rem;">Add Soil Type</h2>
@if($errors->any())
    <div style="background:#fef2f2;color:#b91c1c;padding:0.8rem 1.2rem;border-radius:7px;margin-bottom:1.2rem;">
        <ul style="margin:0;padding-left:1.2rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="{{ route('admin.soil_types.store') }}" enctype="multipart/form-data" style="background:#232d25;padding:2.5rem 2rem;border-radius:18px;box-shadow:0 2px 18px rgba(0,0,0,0.18);max-width:900px;min-width:340px;width:96vw;margin:0 auto;color:#f3fdf7;">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        <div style="grid-column:1/3;">
            <label style="font-weight:800;font-size:1.13rem;letter-spacing:0.01em;color:#FFD600;">Name</label>
            <input type="text" name="name" required placeholder="e.g. Loam" style="width:100%;padding:0.8rem 0.9rem;border-radius:9px;font-size:1.08rem;background:#232d25;color:#fff;border:2px solid #FFD600;" />
            @error('name')<div style="color:#FFD600;font-size:0.97em;">{{ $message }}</div>@enderror
        </div>
        <div style="grid-column:1/3;">
            <label style="font-weight:800;font-size:1.13rem;letter-spacing:0.01em;color:#FFD600;">Texture</label>
            <textarea name="description" rows="2" required placeholder="e.g. Balanced, crumbly" style="width:100%;padding:0.8rem 0.9rem;border-radius:9px;font-size:1.08rem;background:#232d25;color:#fff;border:2px solid #FFD600;"></textarea>
            @error('description')<div style="color:#FFD600;font-size:0.97em;">{{ $message }}</div>@enderror
        </div>
        <div style="grid-column:1/3;">
            <label style="font-weight:800;font-size:1.13rem;letter-spacing:0.01em;color:#FFD600;">Photo</label>
            <input type="file" name="image" accept="image/*" id="soilImageInput" style="width:100%;padding:0.8rem 0.9rem;border-radius:9px;font-size:1.08rem;background:#232d25;color:#fff;border:2px solid #FFD600;" />
            <div id="soilImagePreviewWrap" style="margin-top:0.5rem;"></div>
            @error('image')<div style="color:#FFD600;font-size:0.97em;">{{ $message }}</div>@enderror
        </div>
        <div style="grid-column:1/3;font-weight:800;margin-top:1.2rem;color:#FFD600;font-size:1.09rem;">NPK & pH Thresholds</div>
        <div>
            <label style="font-weight:700;color:#FFD600;">N (min-max)</label>
            <input type="text" name="thresholds[N]" placeholder="e.g. 0.18-0.25" style="width:100%;padding:0.8rem 0.9rem;border-radius:9px;font-size:1.08rem;background:#232d25;color:#fff;border:2px solid #FFD600;" />
            @error('thresholds.N')<div style="color:#FFD600;font-size:0.97em;">{{ $message }}</div>@enderror
        </div>
        <div>
            <label style="font-weight:700;color:#FFD600;">P (min-max)</label>
            <input type="text" name="thresholds[P]" placeholder="e.g. 0.10-0.15" style="width:100%;padding:0.8rem 0.9rem;border-radius:9px;font-size:1.08rem;background:#232d25;color:#fff;border:2px solid #FFD600;" />
            @error('thresholds.P')<div style="color:#FFD600;font-size:0.97em;">{{ $message }}</div>@enderror
        </div>
        <div>
            <label style="font-weight:700;color:#FFD600;">K (min-max)</label>
            <input type="text" name="thresholds[K]" placeholder="e.g. 0.25-0.35" style="width:100%;padding:0.8rem 0.9rem;border-radius:9px;font-size:1.08rem;background:#232d25;color:#fff;border:2px solid #FFD600;" />
            @error('thresholds.K')<div style="color:#FFD600;font-size:0.97em;">{{ $message }}</div>@enderror
        </div>
        <div>
            <label style="font-weight:700;color:#FFD600;">pH (min-max)</label>
            <input type="text" name="thresholds[pH]" placeholder="e.g. 6.0-7.0" style="width:100%;padding:0.8rem 0.9rem;border-radius:9px;font-size:1.08rem;background:#232d25;color:#fff;border:2px solid #FFD600;" />
            @error('thresholds.pH')<div style="color:#FFD600;font-size:0.97em;">{{ $message }}</div>@enderror
        </div>
        <div style="grid-column:1/3;">
            <label style="font-weight:700;color:#FFD600;">Remarks</label>
            <textarea name="remarks" rows="2" style="width:100%;padding:0.8rem 0.9rem;border-radius:9px;font-size:1.08rem;background:#232d25;color:#fff;border:2px solid #FFD600;"></textarea>
            @error('remarks')<div style="color:#FFD600;font-size:0.97em;">{{ $message }}</div>@enderror
        </div>
        <div style="grid-column:1/3;">
            <label style="font-weight:700;color:#FFD600;">Why suitable?</label>
            <textarea name="why_suitable" rows="2" style="width:100%;padding:0.8rem 0.9rem;border-radius:9px;font-size:1.08rem;background:#232d25;color:#fff;border:2px solid #FFD600;"></textarea>
            @error('why_suitable')<div style="color:#FFD600;font-size:0.97em;">{{ $message }}</div>@enderror
        </div>
        <div style="grid-column:1/3;">
            <label style="font-weight:700;color:#FFD600;">Best Crops <span style="color:#bbb;font-size:0.95em;">(comma separated)</span></label>
            <input type="text" name="best_crops" placeholder="e.g. Rice, Corn, Vegetables" style="width:100%;padding:0.8rem 0.9rem;border-radius:9px;font-size:1.08rem;background:#232d25;color:#fff;border:2px solid #FFD600;" />
            @error('best_crops')<div style="color:#FFD600;font-size:0.97em;">{{ $message }}</div>@enderror
        </div>
    </div>
    <div style="margin-top:2.5rem;text-align:right;">
        <button type="submit" style="background:#FFD600;color:#232d25;font-weight:800;border:none;border-radius:9px;padding:1rem 2.5rem;font-size:1.15rem;cursor:pointer;">Save</button>
        <a href="{{ route('admin.soil.index') }}" style="margin-left:1.2rem;color:#FFD600;text-decoration:underline;font-weight:700;">Cancel</a>
    </div>
</form>
<script>
    document.getElementById('soilImageInput').addEventListener('change', function(e) {
        const wrap = document.getElementById('soilImagePreviewWrap');
        wrap.innerHTML = '';
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                wrap.innerHTML = `<img src='${ev.target.result}' alt='Preview' style='max-width:160px;max-height:120px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.08);margin-top:0.5rem;'>`;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection 