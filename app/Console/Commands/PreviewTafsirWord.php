<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Style\Font;

class PreviewTafsirWord extends Command
{
    protected $signature = 'tafsir:preview {file}';

    protected $description = 'Generate colored HTML preview from Quran Tafsir Word document';

    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return self::FAILURE;
        }

        $this->info('Loading Word document...');

        try {
            $phpWord = IOFactory::load($file);
        } catch (\Throwable $e) {
            $this->error('Could not read Word document.');
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */

        $blocks = [];

        $currentSurah = null;

        // الآية التي قرأناها قبل بداية التفسير
        $pendingVerse = null;

        // رقم التفسير الحالي
        $currentTafsirNumber = null;

        // HTML التفسير الحالي
        $currentTafsirHtml = '';

        /*
        |--------------------------------------------------------------------------
        | Read Word document
        |--------------------------------------------------------------------------
        */

        foreach ($phpWord->getSections() as $section) {

            foreach ($section->getElements() as $element) {

                $plainText = trim(
                    $this->extractElementText($element)
                );

                if ($plainText === '') {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Chapter
                |--------------------------------------------------------------------------
                */

                if (preg_match(
                    '/^Chapter\s+(\d+)$/iu',
                    $plainText,
                    $match
                )) {

                    $this->saveCurrentTafsir(
                        $blocks,
                        $currentSurah,
                        $pendingVerse,
                        $currentTafsirNumber,
                        $currentTafsirHtml
                    );

                    $pendingVerse = null;
                    $currentTafsirNumber = null;
                    $currentTafsirHtml = '';

                    $blocks[] = [
                        'type' => 'chapter',
                        'text' => $plainText,
                    ];

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Surah
                |--------------------------------------------------------------------------
                */

                if (preg_match(
                    '/^Sūrah\(t\)\s+(.+)$/iu',
                    $plainText,
                    $match
                )) {

                    $this->saveCurrentTafsir(
                        $blocks,
                        $currentSurah,
                        $pendingVerse,
                        $currentTafsirNumber,
                        $currentTafsirHtml
                    );

                    $pendingVerse = null;
                    $currentTafsirNumber = null;
                    $currentTafsirHtml = '';

                    $currentSurah = trim($match[1]);

                    $blocks[] = [
                        'type' => 'surah',
                        'text' => $currentSurah,
                    ];

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Verse range
                |
                | Example:
                | al-Fajr 89:1-14
                |--------------------------------------------------------------------------
                */

                if (preg_match(
                    '/^(.+?)\s+(\d+):(\d+)(?:-(\d+))?$/u',
                    $plainText
                )) {

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Tafsir marker
                |
                | Example:
                | (1)
                | (2)
                | (3)
                |--------------------------------------------------------------------------
                */

                if (preg_match(
                    '/^\((\d+)\)/u',
                    $plainText,
                    $match
                )) {

                    /*
                    |--------------------------------------------------------------------------
                    | Save previous tafsir
                    |--------------------------------------------------------------------------
                    */

                    $this->saveCurrentTafsir(
                        $blocks,
                        $currentSurah,
                        $pendingVerse,
                        $currentTafsirNumber,
                        $currentTafsirHtml
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | New tafsir
                    |--------------------------------------------------------------------------
                    */

                    $currentTafsirNumber = (int) $match[1];

                    /*
                    |--------------------------------------------------------------------------
                    | If we have a verse waiting, keep it.
                    |
                    | The verse is normally the Word paragraph
                    | immediately before (1), (2), etc.
                    |--------------------------------------------------------------------------
                    */

                    if ($pendingVerse === null) {
                        $pendingVerse = '';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Start tafsir HTML
                    |--------------------------------------------------------------------------
                    */

                    $currentTafsirHtml =
                        $this->elementToColoredHtml(
                            $element,
                            '(' . $currentTafsirNumber . ')'
                        );

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Tafsir continuation
                |--------------------------------------------------------------------------
                */

                if ($currentTafsirNumber !== null) {

                    $currentTafsirHtml .=
                        ' ' .
                        $this->elementToColoredHtml($element);

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Verse text
                |
                | Anything after the verse range and before (1), (2), etc.
                | is treated as the Quran verse.
                |--------------------------------------------------------------------------
                */

                if ($currentSurah !== null) {

                    /*
                    | If the text contains an Arabic verse number
                    | such as ١, ٢, ٣ ... we remove it from the
                    | displayed verse and use the tafsir number.
                    */

                    $verseText = $this->cleanVerseText($plainText);

                    if ($verseText !== '') {

                        if ($pendingVerse === null) {
                            $pendingVerse = $verseText;
                        } else {
                            $pendingVerse .= ' ' . $verseText;
                        }
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Save last tafsir
        |--------------------------------------------------------------------------
        */

        $this->saveCurrentTafsir(
            $blocks,
            $currentSurah,
            $pendingVerse,
            $currentTafsirNumber,
            $currentTafsirHtml
        );

        /*
        |--------------------------------------------------------------------------
        | Generate HTML
        |--------------------------------------------------------------------------
        */

        $content = '';

        foreach ($blocks as $block) {

            /*
            |--------------------------------------------------------------------------
            | Chapter
            |--------------------------------------------------------------------------
            */

            if ($block['type'] === 'chapter') {

                $content .= '
                <h2 class="chapter-title">
                    ' . e($block['text']) . '
                </h2>';

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Surah
            |--------------------------------------------------------------------------
            */

            if ($block['type'] === 'surah') {

                $content .= '
                <h2 class="surah-title">
                    Sūrah(t) ' . e($block['text']) . '
                </h2>';

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Verse + Tafsir
            |--------------------------------------------------------------------------
            */

            if ($block['type'] === 'tafsir') {

                $verseNumber = $block['number'];

                $content .= '
                <div class="ayah-block">

                    <div class="ayah">

                        <div class="ayah-number">
                            ﴿' . $verseNumber . '﴾
                        </div>

                        <div class="ayah-text">
                            ' . e($block['verse']) . '
                        </div>

                    </div>

                    <div class="tafsir">

                        <div class="tafsir-number">
                            Tafsir ' . $verseNumber . '
                        </div>

                        <div class="tafsir-text">
                            ' . $block['html'] . '
                        </div>

                    </div>

                </div>';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | HTML
        |--------------------------------------------------------------------------
        */

        $html = '<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Quran Tafsir Preview</title>

<style>

body {
    background: #f5f0e6;
    font-family: Georgia, "Times New Roman", serif;
    line-height: 1.9;
    margin: 0;
    padding: 40px;
}

.container {
    max-width: 1000px;
    margin: auto;
    background: white;
    padding: 50px;
    border-radius: 16px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
}

h1 {
    text-align: center;
    margin-bottom: 25px;
}

.chapter-title {
    margin-top: 50px;
    color: #333;
}

.surah-title {
    margin-top: 50px;
    margin-bottom: 30px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ddd;
}

/*
|--------------------------------------------------------------------------
| Ayah
|--------------------------------------------------------------------------
*/

.ayah-block {
    margin-bottom: 45px;
}

.ayah {
    background: #faf8f2;
    padding: 22px 25px;
    border-radius: 12px;
    margin-bottom: 18px;
    border-right: 4px solid #c8b98a;
}

.ayah-number {
    font-size: 22px;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
}

.ayah-text {
    font-size: 25px;
    line-height: 2.1;
    direction: rtl;
    text-align: right;
    color: #000;
}

/*
|--------------------------------------------------------------------------
| Tafsir
|--------------------------------------------------------------------------
*/

.tafsir {
    padding-left: 15px;
    padding-right: 15px;
}

.tafsir-number {
    font-weight: bold;
    font-size: 20px;
    margin-bottom: 8px;
    color: #333;
}

.tafsir-text {
    font-size: 19px;
}

/*
|--------------------------------------------------------------------------
| Colors
|--------------------------------------------------------------------------
*/

.color-black {
    color: #000000;
}

.color-red {
    color: #C00000;
}

.color-orange {
    color: #FF6600;
}

.color-green {
    color: #008000;
}

.color-blue {
    color: #0066FF;
}

/*
|--------------------------------------------------------------------------
| Layer controls
|--------------------------------------------------------------------------
*/

.layer-controls {
    text-align: center;
    margin-bottom: 15px;
}

.layer-btn {
    padding: 10px 20px;
    margin: 5px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    background: #ddd;
    font-size: 16px;
}

.layer-btn.active {
    background: #333;
    color: white;
}

.layer-note {
    text-align: center;
    margin-bottom: 35px;
    color: #666;
}

/*
|--------------------------------------------------------------------------
| Beginner mode
|--------------------------------------------------------------------------
*/

body.beginner-mode .color-green,
body.beginner-mode .color-blue {
    display: none;
}

</style>

</head>

<body class="beginner-mode">

<div class="container">

<h1>Quran Tafsir — Preview</h1>

<div class="layer-controls">

    <button
        type="button"
        class="layer-btn active"
        data-layer="beginner">
        Beginner
    </button>

    <button
        type="button"
        class="layer-btn"
        data-layer="advance">
        Advance
    </button>

</div>

<div class="layer-note" id="layerNote">
    Beginner: black, maroon and orange text
</div>

' . $content . '

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const buttons = document.querySelectorAll(".layer-btn");

    const note = document.getElementById("layerNote");

    buttons.forEach(function (button) {

        button.addEventListener("click", function () {

            const layer = this.dataset.layer;

            if (layer === "beginner") {

                document.body.classList.add("beginner-mode");

                buttons.forEach(function (btn) {

                    btn.classList.toggle(
                        "active",
                        btn.dataset.layer === "beginner"
                    );

                });

                note.textContent =
                    "Beginner: black, maroon and orange text";

            } else {

                document.body.classList.remove("beginner-mode");

                buttons.forEach(function (btn) {

                    btn.classList.toggle(
                        "active",
                        btn.dataset.layer === "advance"
                    );

                });

                note.textContent =
                    "Advance: all text colors";

            }

        });

    });

});

</script>

</body>

</html>';

        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */

        $output = storage_path('app/tafsir/preview.html');

        file_put_contents($output, $html);

        $this->newLine();

        $this->info('======================================');
        $this->info('Colored HTML created successfully!');
        $this->info('======================================');

        $this->info("Output: {$output}");

        return self::SUCCESS;
    }


    /*
    |--------------------------------------------------------------------------
    | Extract plain text
    |--------------------------------------------------------------------------
    */

    private function extractElementText($element): string
    {
        if ($element instanceof Text) {
            return (string) $element->getText();
        }

        if ($element instanceof TextRun) {

            $result = '';

            foreach ($element->getElements() as $child) {

                if ($child instanceof Text) {
                    $result .= $child->getText();
                }
            }

            return $result;
        }

        return '';
    }


    /*
    |--------------------------------------------------------------------------
    | Clean Quran verse
    |--------------------------------------------------------------------------
    */

    private function cleanVerseText(string $text): string
    {
        $text = trim($text);

        /*
        | Remove Arabic/English verse number at the beginning
        */

        $text = preg_replace(
            '/^[\s\(\[\{]*[0-9٠-٩]+[\s\)\]\}\.\-:]*/u',
            '',
            $text
        );

        return trim($text);
    }


    /*
    |--------------------------------------------------------------------------
    | Convert Word element to colored HTML
    |--------------------------------------------------------------------------
    */

    private function elementToColoredHtml(
        $element,
        string $removePrefix = ''
    ): string {

        $html = '';

        if ($element instanceof Text) {

            $html = $this->textToColoredSpan($element);

        } elseif ($element instanceof TextRun) {

            foreach ($element->getElements() as $child) {

                if ($child instanceof Text) {

                    $html .=
                        $this->textToColoredSpan($child);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Remove (1), (2), etc.
        |--------------------------------------------------------------------------
        */

        if ($removePrefix !== '') {

            $html = $this->removePrefixFromColoredHtml(
                $html,
                $removePrefix
            );
        }

        return $html;
    }


    /*
    |--------------------------------------------------------------------------
    | Text + Word color
    |--------------------------------------------------------------------------
    */

    private function textToColoredSpan(Text $element): string
    {
        $text = $element->getText();

        if ($text === '') {
            return '';
        }

        $color = null;

        /*
        | PHPWord uses getFontStyle()
        */

        $font = $element->getFontStyle();

        if ($font instanceof Font) {
            $color = $font->getColor();
        }

        $class = $this->getColorClass($color);

        return '<span class="' . $class . '">' .
            nl2br(e($text)) .
            '</span>';
    }


    /*
    |--------------------------------------------------------------------------
    | Word color → CSS class
    |--------------------------------------------------------------------------
    */

    private function getColorClass(?string $color): string
    {
        if (!$color) {
            return 'color-black';
        }

        $color = strtoupper(
            ltrim(trim($color), '#')
        );

        switch ($color) {

            case '000000':
                return 'color-black';

            case 'C00000':
            case 'FF0000':
                return 'color-red';

            case 'FF6600':
                return 'color-orange';

            case '008000':
                return 'color-green';

            case '0066FF':
                return 'color-blue';

            default:
                return 'color-black';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Remove "(1)" while preserving color
    |--------------------------------------------------------------------------
    */

    private function removePrefixFromColoredHtml(
        string $html,
        string $prefix
    ): string {

        $remaining = mb_strlen($prefix);

        return preg_replace_callback(

            '/(<span class="[^"]+">)(.*?)(<\/span>)/us',

            function ($match) use (&$remaining) {

                $open = $match[1];
                $text = $match[2];
                $close = $match[3];

                if ($remaining <= 0) {
                    return $match[0];
                }

                $length = mb_strlen(
                    strip_tags($text)
                );

                if ($length <= $remaining) {

                    $remaining -= $length;

                    return '';
                }

                $text = mb_substr(
                    strip_tags($text),
                    $remaining
                );

                $remaining = 0;

                return $open . $text . $close;
            },

            $html
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Save tafsir + verse
    |--------------------------------------------------------------------------
    */

    private function saveCurrentTafsir(
        array &$blocks,
        ?string $surah,
        ?string &$verse,
        ?int $number,
        string &$html
    ): void {

        if (
            $surah === null ||
            $number === null ||
            trim(strip_tags($html)) === ''
        ) {
            return;
        }

        $blocks[] = [

            'type' => 'tafsir',

            'surah' => $surah,

            'number' => $number,

            'verse' => $verse ?? '',

            'html' => $html,

        ];

        /*
        | Clear current verse after pairing it
        */

        $verse = null;

        $html = '';
    }
}