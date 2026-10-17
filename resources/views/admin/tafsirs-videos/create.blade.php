@extends('layouts.app')

@section('title', 'Add YouTube Video')

@section('content')

<div class="container" style="max-width:850px; margin:40px auto;">

<div style="margin-bottom:25px;">
    <h1 style="margin:0 0 8px;">
        Add YouTube Video
    </h1>

    <p style="color:#777;">
        {{ $tafsir->surah->name ?? 'Tafsir' }}
        —
        {{ ucfirst($tafsir->level) }}
    </p>
</div>

@if($errors->any())
    <div style="
        background:#f8d7da;
        color:#842029;
        padding:15px;
        border-radius:8px;
        margin-bottom:20px;
    ">
        <ul style="margin:0; padding-left:20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="
    background:#fff;
    border:1px solid #ddd;
    border-radius:12px;
    padding:25px;
">

    <form
        action="{{ route('admin.tafsirs.videos.store', $tafsir) }}"
        method="POST"
    >

        @csrf

        {{-- Title --}}
        <div style="margin-bottom:20px;">
            <label style="
                display:block;
                font-weight:600;
                margin-bottom:8px;
            ">
                Video Title
            </label>

            <input
                type="text"
                name="title"
                value="{{ old('title') }}"
                placeholder="Example: Explanation of Surah Al-Balad"
                required
                style="
                    width:100%;
                    box-sizing:border-box;
                    padding:12px;
                    border:1px solid #ccc;
                    border-radius:7px;
                "
            >
        </div>

        {{-- YouTube URL --}}
        <div style="margin-bottom:20px;">
            <label style="
                display:block;
                font-weight:600;
                margin-bottom:8px;
            ">
                YouTube Video URL
            </label>

            <input
                type="url"
                id="video_url"
                name="video_url"
                value="{{ old('video_url') }}"
                placeholder="https://www.youtube.com/watch?v=XXXXXXXX"
                required
                style="
                    width:100%;
                    box-sizing:border-box;
                    padding:12px;
                    border:1px solid #ccc;
                    border-radius:7px;
                "
            >

            <small style="
                display:block;
                color:#777;
                margin-top:7px;
            ">
                Example:
                https://www.youtube.com/watch?v=XXXXXXXX
                <br>
                or:
                https://youtu.be/XXXXXXXX
            </small>
        </div>

        {{-- Preview --}}
        <div id="youtubePreview"
             style="
                display:none;
                margin-bottom:25px;
             ">

            <h3 style="margin-bottom:12px;">
                Video Preview
            </h3>

            <div style="
                position:relative;
                width:100%;
                padding-bottom:56.25%;
                height:0;
                overflow:hidden;
                border-radius:10px;
                background:#000;
            ">

                <iframe
                    id="youtubeFrame"
                    src=""
                    title="YouTube preview"
                    style="
                        position:absolute;
                        top:0;
                        left:0;
                        width:100%;
                        height:100%;
                        border:0;
                    "
                    allowfullscreen
                ></iframe>

            </div>
        </div>

        {{-- Description --}}
        <div style="margin-bottom:20px;">
            <label style="
                display:block;
                font-weight:600;
                margin-bottom:8px;
            ">
                Description
            </label>

            <textarea
                name="description"
                rows="5"
                placeholder="Optional video description..."
                style="
                    width:100%;
                    box-sizing:border-box;
                    padding:12px;
                    border:1px solid #ccc;
                    border-radius:7px;
                    resize:vertical;
                "
            >{{ old('description') }}</textarea>
        </div>

        {{-- Order --}}
        <div style="margin-bottom:25px;">
            <label style="
                display:block;
                font-weight:600;
                margin-bottom:8px;
            ">
                Display Order
            </label>

            <input
                type="number"
                name="sort_order"
                value="{{ old('sort_order', 0) }}"
                min="0"
                style="
                    width:150px;
                    padding:11px;
                    border:1px solid #ccc;
                    border-radius:7px;
                "
            >
        </div>

        {{-- Buttons --}}
        <div style="
            display:flex;
            gap:10px;
            flex-wrap:wrap;
        ">

            <button
                type="submit"
                style="
                    border:0;
                    background:#198754;
                    color:#fff;
                    padding:11px 22px;
                    border-radius:7px;
                    cursor:pointer;
                    font-weight:600;
                "
            >
                Save Video
            </button>

            <a
                href="{{ route('admin.tafsirs.videos.index', $tafsir) }}"
                style="
                    text-decoration:none;
                    background:#6c757d;
                    color:#fff;
                    padding:11px 22px;
                    border-radius:7px;
                "
            >
                Cancel
            </a>

        </div>

    </form>

</div>

</div>

<script>
function getYouTubeId(url) {

    if (!url) {
        return null;
    }

    const patterns = [
        /youtube\.com\/watch\?v=([^&]+)/,
        /youtu\.be\/([^?&]+)/,
        /youtube\.com\/embed\/([^?&]+)/,
        /youtube\.com\/shorts\/([^?&]+)/
    ];

    for (const pattern of patterns) {

        const match = url.match(pattern);

        if (match) {
            return match[1];
        }
    }

    return null;
}


const videoUrlInput = document.getElementById('video_url');
const preview = document.getElementById('youtubePreview');
const frame = document.getElementById('youtubeFrame');

videoUrlInput.addEventListener('input', function () {

    const videoId = getYouTubeId(this.value.trim());

    if (videoId) {

        frame.src = 'https://www.youtube.com/embed/' + videoId;

        preview.style.display = 'block';

    } else {

        frame.src = '';

        preview.style.display = 'none';
    }

});
</script>

@endsection
