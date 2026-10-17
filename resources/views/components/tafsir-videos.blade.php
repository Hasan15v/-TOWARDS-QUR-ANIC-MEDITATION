{{-- =========================================================
     TAFSIR VIDEOS COMPONENT
     resources/views/components/tafsir-videos.blade.php
========================================================= --}}

@php

    /*
    |--------------------------------------------------------------------------
    | Get videos
    |--------------------------------------------------------------------------
    */

    $videoItems = collect();

    if (isset($tafsir) && $tafsir) {

        if ($tafsir->relationLoaded('videos')) {
            $videoItems = $tafsir->videos;
        } elseif (method_exists($tafsir, 'videos')) {
            $videoItems = $tafsir->videos;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Sort videos by part number
    |--------------------------------------------------------------------------
    */

    $videoItems = collect($videoItems)
        ->sortBy(function ($video) {
            return $video->part_number
                ?? $video->part
                ?? 0;
        })
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Unique ID
    |
    | Important because this component may appear multiple times
    | inside @foreach($tafsirs as $item)
    |--------------------------------------------------------------------------
    */

    $videoComponentId = 'tafsir-video-' . uniqid();

@endphp


@if($videoItems->count() > 0)

<div
    class="tafsir-videos-component"
    id="{{ $videoComponentId }}"
>

    {{-- =====================================================
         VIDEO BUTTON + DROPDOWN
    ====================================================== --}}

    <div class="tafsir-video-menu-wrapper">

        <button
            type="button"
            class="tafsir-video-menu-button"
            data-video-menu-button
        >

            <span class="tafsir-video-menu-icon">
                ▶
            </span>

            <span>
                Videos
            </span>

            <span class="tafsir-video-menu-arrow">
                ▾
            </span>

        </button>


        {{-- =================================================
             PARTS DROPDOWN
        ================================================== --}}

        <div
            class="tafsir-video-dropdown"
            data-video-dropdown
        >

            <div class="tafsir-video-dropdown-title">
                Available Parts
            </div>


            @foreach($videoItems as $index => $video)

                @php

                    $partNumber =
                        $video->part_number
                        ?? $video->part
                        ?? ($index + 1);

                    $videoTitle =
                        $video->title
                        ?? ('Part ' . $partNumber);

                    $videoUrl =
                        $video->video_url
                        ?? $video->url
                        ?? $video->video
                        ?? '';

                @endphp


                @if($videoUrl)

                    <button
                        type="button"
                        class="tafsir-video-part-button"
                        data-video-part-button
                        data-video-url="{{ $videoUrl }}"
                        data-video-title="{{ $videoTitle }}"
                        data-video-part="Part {{ $partNumber }}"
                    >

                        <span class="tafsir-video-part-icon">
                            ▶
                        </span>

                        <span class="tafsir-video-part-text">
                            Part {{ $partNumber }}
                        </span>

                        <span class="tafsir-video-part-arrow">
                            →
                        </span>

                    </button>

                @endif

            @endforeach

        </div>

    </div>



    {{-- =====================================================
         VIDEO MODAL
    ====================================================== --}}

    <div
        class="tafsir-video-modal"
        data-video-modal
        aria-hidden="true"
    >

        <div class="tafsir-video-modal-box">


            {{-- =================================================
                 MODAL HEADER
            ================================================== --}}

            <div class="tafsir-video-modal-header">

                <div class="tafsir-video-modal-title-wrapper">

                    <div class="tafsir-video-modal-play-icon">
                        ▶
                    </div>

                    <div>

                        <div
                            class="tafsir-video-modal-part"
                            data-video-modal-part
                        >
                            Part
                        </div>

                        <div
                            class="tafsir-video-modal-title"
                            data-video-modal-title
                        >
                            Tafsir Video
                        </div>

                    </div>

                </div>


                <button
                    type="button"
                    class="tafsir-video-modal-close"
                    data-video-modal-close
                    aria-label="Close"
                >
                    ×
                </button>

            </div>



            {{-- =================================================
                 VIDEO AREA
            ================================================== --}}

          <div class="tafsir-youtube-container">
    <iframe
        class="tafsir-youtube-frame"
        data-youtube-frame
        src=""
        title="Tafsir Video"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        allowfullscreen
    ></iframe>
</div>



            {{-- =================================================
                 FOOTER
            ================================================== --}}

            <div class="tafsir-video-modal-footer">

                <span>
                    Drag or scroll horizontally if the video is wider than the screen.
                </span>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
     CSS
========================================================= --}}

<style>

    /* =====================================================
       COMPONENT
    ====================================================== */

    .tafsir-videos-component {
        position: relative;
        display: inline-block;
        z-index: 50;
    }



    /* =====================================================
       VIDEO MENU WRAPPER
    ====================================================== */

    .tafsir-video-menu-wrapper {
        position: relative;
        display: inline-block;
        margin-left: 8px;
        z-index: 100;
    }



    /* =====================================================
       MAIN VIDEOS BUTTON
    ====================================================== */

    .tafsir-video-menu-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        height: 38px;
        padding: 0 12px;

        border: 1px solid rgba(49, 95, 69, 0.22);
        border-radius: 10px;

        background: rgba(49, 95, 69, 0.07);

        color: #315f45;

        font-family: Inter, sans-serif;
        font-size: 12px;
        font-weight: 700;

        cursor: pointer;

        transition:
            background 0.18s ease,
            border-color 0.18s ease,
            transform 0.18s ease;
    }


    .tafsir-video-menu-button:hover {
        background: rgba(49, 95, 69, 0.13);
        border-color: rgba(49, 95, 69, 0.35);
    }


    .tafsir-video-menu-button.active {
        background: #315f45;
        color: #fff;
        border-color: #315f45;
    }


    .tafsir-video-menu-icon {
        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 9px;

        padding-left: 1px;
    }


    .tafsir-video-menu-arrow {
        font-size: 11px;

        transition:
            transform 0.18s ease;
    }


    .tafsir-video-menu-button.active
    .tafsir-video-menu-arrow {
        transform: rotate(180deg);
    }



    /* =====================================================
       DROPDOWN
    ====================================================== */

    .tafsir-video-dropdown {
        position: absolute;

        top: calc(100% + 8px);
        right: 0;

        width: 190px;

        padding: 7px;

        background: #fffdf8;

        border: 1px solid rgba(92, 71, 45, 0.15);
        border-radius: 12px;

        box-shadow:
            0 12px 35px rgba(40, 30, 18, 0.14);

        opacity: 0;
        visibility: hidden;

        transform:
            translateY(-5px)
            scale(0.98);

        transform-origin: top right;

        transition:
            opacity 0.18s ease,
            visibility 0.18s ease,
            transform 0.18s ease;

        z-index: 1000;
    }


    .tafsir-video-dropdown.show {
        opacity: 1;
        visibility: visible;

        transform:
            translateY(0)
            scale(1);
    }


    .tafsir-video-dropdown-title {
        padding: 7px 9px 8px;

        color: #827865;

        font-family: Inter, sans-serif;

        font-size: 10px;
        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.6px;
    }



    /* =====================================================
       PART BUTTON
    ====================================================== */

    .tafsir-video-part-button {
        width: 100%;

        display: flex;
        align-items: center;

        gap: 9px;

        padding: 9px;

        border: 0;
        border-radius: 8px;

        background: transparent;

        color: #4d463b;

        cursor: pointer;

        text-align: left;

        font-family: Inter, sans-serif;

        transition:
            background 0.16s ease,
            color 0.16s ease;
    }


    .tafsir-video-part-button:hover {
        background: rgba(49, 95, 69, 0.08);
        color: #315f45;
    }


    .tafsir-video-part-icon {
        flex: 0 0 auto;

        width: 25px;
        height: 25px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 7px;

        background: rgba(49, 95, 69, 0.09);

        color: #315f45;

        font-size: 8px;

        padding-left: 1px;
    }


    .tafsir-video-part-text {
        flex: 1;

        font-size: 12px;
        font-weight: 600;
    }


    .tafsir-video-part-arrow {
        color: #9b917f;

        font-size: 13px;

        transition:
            transform 0.16s ease,
            color 0.16s ease;
    }


    .tafsir-video-part-button:hover
    .tafsir-video-part-arrow {
        color: #315f45;

        transform:
            translateX(2px);
    }



    /* =====================================================
       MODAL
    ====================================================== */

    .tafsir-video-modal {

        position: fixed;

        inset: 0;

        display: flex;

        align-items: flex-start;
        justify-content: center;

        padding:
            60px
            20px
            40px;

        background:
            rgba(20, 24, 21, 0.55);

        opacity: 0;

        visibility: hidden;

        pointer-events: none;

        transition:
            opacity 0.2s ease,
            visibility 0.2s ease;

        z-index: 99999;

        /*
         * الصفحة نفسها تبقى قابلة للـscroll
         */
        overflow-y: auto;

        /*
         * مهم حتى ما يصير scroll أفقي للـmodal نفسه
         */
        overflow-x: hidden;
    }


    .tafsir-video-modal.show {

        opacity: 1;

        visibility: visible;

        pointer-events: auto;
    }



    /* =====================================================
       MODAL BOX
    ====================================================== */

    .tafsir-video-modal-box {

        width: min(1200px, 95vw);

        max-width: 1200px;

        flex: 0 0 auto;

        background: #fffdf8;

        border:
            1px solid
            rgba(92, 71, 45, 0.16);

        border-radius: 16px;

        box-shadow:
            0 20px 60px
            rgba(20, 25, 21, 0.30);

        overflow: hidden;

        transform:
            translateY(-10px)
            scale(0.98);

        transition:
            transform 0.2s ease;
    }


    .tafsir-video-modal.show
    .tafsir-video-modal-box {

        transform:
            translateY(0)
            scale(1);
    }



    /* =====================================================
       MODAL HEADER
    ====================================================== */

    .tafsir-video-modal-header {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        padding:
            12px
            14px;

        border-bottom:
            1px solid
            rgba(92, 71, 45, 0.10);

        background: #fffdf8;
    }


    .tafsir-video-modal-title-wrapper {

        display: flex;

        align-items: center;

        gap: 10px;

        min-width: 0;
    }


    .tafsir-video-modal-play-icon {

        width: 34px;
        height: 34px;

        display: flex;

        align-items: center;
        justify-content: center;

        flex: 0 0 auto;

        border-radius: 9px;

        background: #315f45;

        color: #fff;

        font-size: 10px;

        padding-left: 2px;
    }


    .tafsir-video-modal-part {

        color: #315f45;

        font-family: Inter, sans-serif;

        font-size: 10px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.5px;
    }


    .tafsir-video-modal-title {

        max-width: 650px;

        overflow: hidden;

        color: #3e463f;

        font-family: Inter, sans-serif;

        font-size: 13px;

        font-weight: 600;

        white-space: nowrap;

        text-overflow: ellipsis;
    }



    /* =====================================================
       CLOSE BUTTON
    ====================================================== */

    .tafsir-video-modal-close {

        width: 34px;
        height: 34px;

        display: flex;

        align-items: center;
        justify-content: center;

        flex: 0 0 auto;

        border: 0;

        border-radius: 8px;

        background:
            rgba(92, 71, 45, 0.07);

        color: #655c4e;

        font-size: 22px;

        line-height: 1;

        cursor: pointer;

        transition:
            background 0.16s ease,
            color 0.16s ease;
    }


    .tafsir-video-modal-close:hover {

        background:
            rgba(180, 70, 60, 0.10);

        color: #9b4037;
    }



    /* =====================================================
       VIDEO PLAYER CONTAINER
    ====================================================== */

    .tafsir-video-modal-player {
    width: 100%;
    background: #000;

    overflow-x: auto;
    overflow-y: hidden;

    -webkit-overflow-scrolling: touch;

    touch-action: pan-x;

    scrollbar-width: thin;
}

.tafsir-youtube-container {
    width: 100%;
    min-width: 100%;

    background: #000;

    aspect-ratio: 16 / 9;
}

.tafsir-youtube-frame {
    display: block;

    width: 100%;
    height: 100%;

    border: 0;

    background: #000;
}


    /* =====================================================
       VIDEO
    ====================================================== */

    .tafsir-video-element {

        display: block;

        /*
         * لا تستخدم width: 100%
         *
         * نخلي الفيديو يحافظ على أبعاده.
         */
        width: auto;

        /*
         * أقل شيء يكون بعرض الصندوق
         */
        min-width: 100%;

        /*
         * مهم جداً:
         * لا نخليه ينكمش
         */
        max-width: none;

        /*
         * ارتفاع كبير مناسب للشاشة
         */
        max-height: 75vh;

        height: auto;

        background: #000;

        object-fit: contain;
    }



    /* =====================================================
       FOOTER
    ====================================================== */

    .tafsir-video-modal-footer {

        padding:
            9px
            14px;

        color: #8a806e;

        font-family: Inter, sans-serif;

        font-size: 10px;

        background: #fffdf8;

        border-top:
            1px solid
            rgba(92, 71, 45, 0.08);
    }



    /* =====================================================
       MOBILE
    ====================================================== */

    @media (max-width: 700px) {

        .tafsir-video-menu-wrapper {
            margin-left: 4px;
        }


        .tafsir-video-menu-button {

            height: 35px;

            padding:
                0 9px;

            font-size: 11px;
        }


        .tafsir-video-dropdown {

            right: auto;

            left: 0;
        }


        .tafsir-video-modal {

            padding:
                45px
                8px
                20px;
        }


        .tafsir-video-modal-box {

            width: 100%;

            max-width: none;

            border-radius: 13px;
        }


        .tafsir-video-modal-player {

            width: 100%;

            /*
             * horizontal scroll
             */
            overflow-x: auto;

            overflow-y: hidden;

            touch-action: pan-x;
        }


        .tafsir-video-element {

            /*
             * لا نصغر الفيديو
             */
            width: auto;

            min-width: 100%;

            max-width: none;

            max-height: 70vh;

            height: auto;
        }


        .tafsir-video-modal-title {

            max-width: 220px;
        }

    }



    /* =====================================================
       SMALL MOBILE
    ====================================================== */

    @media (max-width: 400px) {

        .tafsir-video-modal-title {

            max-width: 160px;
        }


        .tafsir-video-modal-footer {

            font-size: 9px;
        }

    }

</style>



{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Get this component only
    |--------------------------------------------------------------------------
    */

    const component =
        document.getElementById(
            @json($videoComponentId)
        );

    if (!component) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const menuButton =
        component.querySelector(
            '[data-video-menu-button]'
        );

    const dropdown =
        component.querySelector(
            '[data-video-dropdown]'
        );

    const modal =
        component.querySelector(
            '[data-video-modal]'
        );

    const modalClose =
        component.querySelector(
            '[data-video-modal-close]'
        );

    const youtubeFrame =
        component.querySelector(
            '[data-youtube-frame]'
        );

    const playerContainer =
        component.querySelector(
            '[data-video-player-container]'
        );

    const modalPart =
        component.querySelector(
            '[data-video-modal-part]'
        );

    const modalTitle =
        component.querySelector(
            '[data-video-modal-title]'
        );

    const partButtons =
        component.querySelectorAll(
            '[data-video-part-button]'
        );


    /*
    |--------------------------------------------------------------------------
    | Check required elements
    |--------------------------------------------------------------------------
    */

    if (
        !menuButton ||
        !dropdown ||
        !modal ||
        !modalClose ||
        !youtubeFrame
    ) {
        console.error(
            'Tafsir Videos: Required element is missing.'
        );

        return;
    }



    /*
    |--------------------------------------------------------------------------
    | Open / Close Parts Dropdown
    |--------------------------------------------------------------------------
    */

    menuButton.addEventListener(
        'click',
        function (event) {

            event.stopPropagation();

            dropdown.classList.toggle('show');

            menuButton.classList.toggle('active');

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Close dropdown when clicking outside
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function (event) {

            if (
                !dropdown.contains(event.target) &&
                !menuButton.contains(event.target)
            ) {

                dropdown.classList.remove('show');

                menuButton.classList.remove('active');

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Convert YouTube URL to Embed URL
    |--------------------------------------------------------------------------
    */

    function convertToYouTubeEmbed(url) {

        try {

            const parsedUrl = new URL(url);

            let videoId = '';


            /*
            |--------------------------------------------------------------------------
            | youtu.be/VIDEO_ID
            |--------------------------------------------------------------------------
            */

            if (
                parsedUrl.hostname === 'youtu.be' ||
                parsedUrl.hostname === 'www.youtu.be'
            ) {

                videoId =
                    parsedUrl.pathname
                        .replace(/^\/+/, '')
                        .split('/')[0]
                        .trim();

            }


            /*
            |--------------------------------------------------------------------------
            | youtube.com/watch?v=VIDEO_ID
            |--------------------------------------------------------------------------
            */

            else if (
                parsedUrl.hostname === 'youtube.com' ||
                parsedUrl.hostname === 'www.youtube.com'
            ) {

                /*
                | /watch?v=...
                */

                if (
                    parsedUrl.pathname === '/watch'
                ) {

                    videoId =
                        parsedUrl.searchParams.get('v')
                        || '';

                }


                /*
                | /embed/VIDEO_ID
                */

                else if (
                    parsedUrl.pathname.startsWith('/embed/')
                ) {

                    videoId =
                        parsedUrl.pathname
                            .split('/embed/')[1]
                            .split('/')[0];

                }


                /*
                | /shorts/VIDEO_ID
                */

                else if (
                    parsedUrl.pathname.startsWith('/shorts/')
                ) {

                    videoId =
                        parsedUrl.pathname
                            .split('/shorts/')[1]
                            .split('/')[0];

                }


                /*
                | /live/VIDEO_ID
                */

                else if (
                    parsedUrl.pathname.startsWith('/live/')
                ) {

                    videoId =
                        parsedUrl.pathname
                            .split('/live/')[1]
                            .split('/')[0];

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Invalid URL
            |--------------------------------------------------------------------------
            */

            if (!videoId) {

                console.error(
                    'Could not extract YouTube video ID:',
                    url
                );

                return '';

            }


            /*
            |--------------------------------------------------------------------------
            | Return embed URL
            |--------------------------------------------------------------------------
            */

            return (
                'https://www.youtube.com/embed/' +
                encodeURIComponent(videoId) +
                '?rel=0'
            );

        } catch (error) {

            console.error(
                'Invalid YouTube URL:',
                url,
                error
            );

            return '';

        }

    }



    /*
    |--------------------------------------------------------------------------
    | Open Video
    |--------------------------------------------------------------------------
    */

    partButtons.forEach(
        function (button) {

            button.addEventListener(
                'click',
                function () {

                    const videoUrl =
                        button.dataset.videoUrl;

                    const videoTitle =
                        button.dataset.videoTitle ||
                        'Tafsir Video';

                    const videoPart =
                        button.dataset.videoPart ||
                        'Part';


                    /*
                    |--------------------------------------------------------------------------
                    | Check URL
                    |--------------------------------------------------------------------------
                    */

                    if (!videoUrl) {

                        console.error(
                            'Video URL is empty.'
                        );

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Convert YouTube URL
                    |--------------------------------------------------------------------------
                    */

                    const youtubeUrl =
                        convertToYouTubeEmbed(
                            videoUrl
                        );


                    if (!youtubeUrl) {

                        console.error(
                            'Invalid YouTube URL:',
                            videoUrl
                        );

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Update modal title
                    |--------------------------------------------------------------------------
                    */

                    if (modalPart) {

                        modalPart.textContent =
                            videoPart;

                    }


                    if (modalTitle) {

                        modalTitle.textContent =
                            videoTitle;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Close dropdown
                    |--------------------------------------------------------------------------
                    */

                    dropdown.classList.remove(
                        'show'
                    );

                    menuButton.classList.remove(
                        'active'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Reset scroll position
                    |--------------------------------------------------------------------------
                    */

                    if (playerContainer) {

                        playerContainer.scrollLeft = 0;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Set YouTube iframe
                    |--------------------------------------------------------------------------
                    */

                    youtubeFrame.src =
                        youtubeUrl;


                    /*
                    |--------------------------------------------------------------------------
                    | Open modal
                    |--------------------------------------------------------------------------
                    */

                    modal.classList.add(
                        'show'
                    );

                    modal.setAttribute(
                        'aria-hidden',
                        'false'
                    );

                }
            );

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Close Modal
    |--------------------------------------------------------------------------
    */

    function closeVideoModal() {

        /*
        |--------------------------------------------------------------
        | Stop YouTube video
        |--------------------------------------------------------------
        |
        | Removing iframe source completely stops playback.
        |
        */

        youtubeFrame.src = '';


        /*
        |--------------------------------------------------------------
        | Close modal
        |--------------------------------------------------------------
        */

        modal.classList.remove(
            'show'
        );

        modal.setAttribute(
            'aria-hidden',
            'true'
        );

    }



    /*
    |--------------------------------------------------------------------------
    | Close Button
    |--------------------------------------------------------------------------
    */

    modalClose.addEventListener(
        'click',
        closeVideoModal
    );



    /*
    |--------------------------------------------------------------------------
    | Click Outside Modal
    |--------------------------------------------------------------------------
    */

    modal.addEventListener(
        'click',
        function (event) {

            if (
                event.target === modal
            ) {

                closeVideoModal();

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | ESC
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                modal.classList.contains('show')
            ) {

                closeVideoModal();

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Horizontal Mouse Wheel
    |--------------------------------------------------------------------------
    */

    if (playerContainer) {

        playerContainer.addEventListener(
            'wheel',
            function (event) {

                /*
                |----------------------------------------------------------
                | Shift + Wheel
                |----------------------------------------------------------
                */

                if (event.shiftKey) {

                    event.preventDefault();

                    playerContainer.scrollLeft +=
                        event.deltaY;

                }

            },
            {
                passive: false
            }
        );

    }

});

</script>

@endif