@extends('layouts.app')

@section('title', 'Tafsir Videos')

@section('content')

<div class="container" style="max-width:1100px; margin:40px auto;">

<div style="
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    flex-wrap:wrap;
    margin-bottom:25px;
">

    <div>
        <h1 style="margin:0 0 8px;">
            YouTube Video Explanations
        </h1>

        <div style="color:#777;">
            <strong>Surah:</strong>
            {{ $tafsir->surah->name_en ?? 'Unknown' }}
        </div>

        <div style="color:#777;">
            <strong>Level:</strong>
            {{ ucfirst($tafsir->level) }}
        </div>
    </div>

    <div style="
        display:flex;
        gap:10px;
        flex-wrap:wrap;
    ">

                       <a
            href="{{ route('admin.tafsirs.videos.create', $tafsir) }}"
            style="
                display:inline-block;
                margin-top:10px;
                background:#198754;
                color:#fff;
                padding:10px 18px;
                border-radius:7px;
                text-decoration:none;
            "
        >
            + Add YouTube Video
        </a>

        <a
            href="{{ url()->previous() }}"
            style="
                text-decoration:none;
                background:#6c757d;
                color:#fff;
                padding:10px 18px;
                border-radius:7px;
            "
        >
            Back
        </a>



    </div>

</div>


@if(session('success'))

    <div style="
        background:#d1e7dd;
        color:#0f5132;
        padding:12px 16px;
        border-radius:7px;
        margin-bottom:20px;
    ">
        {{ session('success') }}
    </div>

@endif


@if($videos->count())

    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit, minmax(330px, 1fr));
        gap:22px;
    ">

        @foreach($videos as $video)

            <div style="
                background:#fff;
                border:1px solid #ddd;
                border-radius:12px;
                overflow:hidden;
                box-shadow:0 3px 10px rgba(0,0,0,.07);
            ">

                @php
                    $videoId = null;

                    if (preg_match(
                        '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([^?&]+)/',
                        $video->video_url,
                        $matches
                    )) {
                        $videoId = $matches[1];
                    }
                @endphp


                @if($videoId)

                    <div style="
                        position:relative;
                        width:100%;
                        padding-bottom:56.25%;
                        height:0;
                        background:#000;
                    ">

                        <iframe
                            src="https://www.youtube.com/embed/{{ $videoId }}"
                            title="{{ $video->title }}"
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

                @else

                    <div style="
                        height:220px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        background:#f5f5f5;
                        color:#888;
                    ">
                        Invalid YouTube URL
                    </div>

                @endif


                <div style="padding:18px;">

                    <h3 style="
                        margin:0 0 10px;
                    ">
                        {{ $video->title }}
                    </h3>


                    @if($video->description)

                        <p style="
                            color:#666;
                            line-height:1.6;
                            margin:0 0 15px;
                        ">
                            {{ $video->description }}
                        </p>

                    @endif


                    <div style="
                        color:#888;
                        font-size:13px;
                        margin-bottom:15px;
                    ">
                        Display Order:
                        {{ $video->sort_order }}
                    </div>


                    <div style="
                        display:flex;
                        gap:8px;
                    ">

                        <a
                            href="{{ route('admin.tafsirs.videos.edit', $video) }}"
                            style="
                                flex:1;
                                text-align:center;
                                text-decoration:none;
                                background:#0d6efd;
                                color:#fff;
                                padding:9px;
                                border-radius:6px;
                            "
                        >
                            Edit
                        </a>


                        <form
                            action="{{ route('admin.tafsirs.videos.destroy', $video) }}"
                            method="POST"
                            style="flex:1;"
                            onsubmit="return confirm('Delete this video?');"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                style="
                                    width:100%;
                                    border:0;
                                    background:#dc3545;
                                    color:#fff;
                                    padding:9px;
                                    border-radius:6px;
                                    cursor:pointer;
                                "
                            >
                                Delete
                            </button>

                        </form>

          

                    </div>

                </div>

            </div>

        @endforeach

    </div>

@else

    <div style="
        text-align:center;
        background:#fff;
        border:1px dashed #bbb;
        border-radius:12px;
        padding:60px 20px;
        color:#777;
    ">

        <div style="
            font-size:50px;
            margin-bottom:15px;
        ">
            🎥
        </div>

        <h3 style="margin-bottom:10px;">
            No YouTube Videos
        </h3>

        <p>
            Add the first video explanation for this Tafsir.
        </p>

       

    </div>

@endif

</div>

@endsection
