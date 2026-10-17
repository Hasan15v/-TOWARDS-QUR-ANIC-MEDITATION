@extends('layouts.app')

@section('title', 'Edit YouTube Video')

@section('content')

<div class="container" style="max-width:850px; margin:40px auto;">

```
<div style="margin-bottom:25px;">
    <h1 style="margin:0 0 8px;">
        Edit YouTube Video
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
        action="{{ route('admin.tafsirs.videos.update', $video) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

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
                value="{{ old('title', $video->title) }}"
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

        {{-- URL --}}
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
                value="{{ old('video_url', $video->video_url) }}"
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

        {{-- Preview --}}
        <div id="youtubePreview"
             style="
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
                style="
                    width:100%;
                    box-sizing:border-box;
                    padding:12px;
                    border:1px solid #ccc;
                    border-radius:7px;
                    resize:vertical;
                "
            >{{ old('description', $video->description) }}</textarea>
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
                value="{{ old('sort_order', $video->sort_order) }}"
                min="0"
                style="
                    width:150px;
                    padding:11px;
                    border:1px solid #ccc;
                    border-radius:7px;
                "
            >
        </div>

        <div style="
            display:flex;
            gap:10px;
            flex-wrap:wrap;
        ">

            <button
                type="submit"
                style="
                    border:0;
                    background:#0d6efd;
                    color:#fff;
                    padding:11px 22px;
                    border-radius:7px;
                    cursor:pointer;
                    font-weight:600;
                "
            >
                Update Video
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
```

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
const frame = document.getElementById('youtubeFrame');

function updatePreview() {

    const videoId = getYouTubeId(videoUrlInput.value.trim());

    if (videoId) {

        frame.src = 'https://www.youtube.com/embed/' + videoId;

    } else {

        frame.src = '';
    }
}


videoUrlInput.addEventListener('input', updatePreview);

updatePreview();
</script>

@endsection
