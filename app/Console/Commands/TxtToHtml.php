<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TxtToHtml extends Command
{
    protected $signature = 'tafsir:txt-to-html';

    protected $description = 'Convert tafsir preview.txt to HTML';

    public function handle()
    {
        $input = storage_path('app/tafsir/preview.txt');
        $output = storage_path('app/tafsir/preview.html');

        if (!file_exists($input)) {
            $this->error("File not found: {$input}");
            return self::FAILURE;
        }

        $text = file_get_contents($input);

        $lines = preg_split("/\r\n|\n|\r/", $text);

        $content = '';

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line === '') {
                continue;
            }

            /*
             * Detect Tafsir number:
             * (1) ...
             * (2) ...
             */
            if (preg_match('/^\((\d+)\)\s*(.*)$/u', $line, $match)) {

                $number = $match[1];
                $textContent = $match[2];

                $content .= '
                <div class="tafsir">
                    <div class="tafsir-number">Tafsir ' . $number . '</div>
                    <div class="tafsir-text">
                        <span class="color-black">(</span>
                        <span class="color-black">' . $number . '</span>
                        <span class="color-black">)</span>
                        <span class="color-black">
                            ' . e($textContent) . '
                        </span>
                    </div>
                </div>';
            }

            /*
             * Surah heading
             */
            elseif (preg_match('/Sūrah|Surah/i', $line)) {

                $content .= '
                <h2 class="surah-title">
                    ' . e($line) . '
                </h2>';
            }

            /*
             * Normal continuation text
             */
            else {

                $content .= '
                <div class="tafsir">
                    <div class="tafsir-text">
                        <span class="color-black">
                            ' . e($line) . '
                        </span>
                    </div>
                </div>';
            }
        }

        $html = '<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tafsir Preview</title>

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

.surah-title {
    margin-top: 50px;
    margin-bottom: 30px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ddd;
}

.tafsir {
    margin-bottom: 35px;
}

.tafsir-number {
    font-weight: bold;
    font-size: 22px;
    margin-bottom: 10px;
    color: #333;
}

.tafsir-text {
    font-size: 19px;
}

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


/* Layer controls */

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


/* Beginner hides green and blue */

body.beginner-mode .color-green,
body.beginner-mode .color-blue {
    display: none;
}

</style>

</head>

<body class="beginner-mode">

<div class="container">

<h1>Tafsir Preview</h1>

<div class="layer-controls">

    <button type="button"
            class="layer-btn active"
            data-layer="beginner">
        Beginner
    </button>

    <button type="button"
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

        file_put_contents($output, $html);

        $this->info("HTML created successfully!");
        $this->info("Output: {$output}");

        return self::SUCCESS;
    }
}