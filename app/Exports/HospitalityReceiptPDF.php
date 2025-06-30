<?php

namespace App\Exports;

use Carbon\Carbon;
use tFPDF;

define('FPDF_FONTPATH', $_SERVER['DOCUMENT_ROOT'] . '/fonts/');
define('_SYSTEM_TTFONTS', $_SERVER['DOCUMENT_ROOT'] . '/fonts/');

class HospitalityReceiptPDF extends tFPDF {

    public $height = 297; // A4 Boyutu (Yükseklik)(Milimetre)
    public $width = 210; // A4 Boyutu (Genişlik)(Milimetre)
    public $main_title_height = 22; // Ana başlığın "Y" eksenindeki başlangıç pozisyonunu belirlemek için kullanıyoruz.
    public $main_title_width = 85; // Ana başlığın "X" eksenindeki başlangıç pozisyonunu belirlemek için kullanıyoruz.
    public $title_cell_width = 42; // "Başlık" sütunun sabit genişliğini tanımlıyoruz.
    public $content_cell_width = 108; // "İçerik" sütunun sabit genişliğini tanımlıyoruz.
    public $page_break_mm = 20;
    public $thresh;
    public $indent = 30; // Sayfanın kenarlarından bırakılan sabit girintiyi belirtiyoruz.
    public $hospitality;
    public $content_size = 9; // Standart İçerik Font Boyutu
    public $default_cell_height = 7; // Standart Hücre Yüksekliği
    public $currency_cell_weight = 86; // Fatura Tutarlarının Bulunduğu Hücrelerin Başlık Bölümünün Genişliği
    public $amount_cell_weight = 18; // Fiyatın Hücre Genişliği
    public $address_cell_weight = 65; // Adres ve Tarih Hücresinin Genişliği
    public $signature_cell_weight = 85; // İmza Hücresinin Genişliği
    public $multi_cell_line_height = 5; // Multi Cell İçin Satır Yüksekliği

    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4') {
        parent::__construct($orientation, $unit, $size);
        $this->AddFont('Helvetica','','Helvetica.ttf',true);
        $this->AddFont('Helvetica','B','Helvetica-Bold.ttf',true);
        $this->thresh = $this->height - $this->page_break_mm; // Sayfa ne zaman taşıyor? Eşiği burada kontrol ediyoruz.
    }

    function Header() {
        parent::Header();
        $this->SetFont("Helvetica","B",13);
        $this->SetXY($this->main_title_width, $this->main_title_height);
        $this->Cell(40,10,'Bewirtungsbeleg',0,0,'C'); // Ana Başlık
        $this->Line(86,29,124,29); // Başlığın Alt Çizgisi
        $this->Ln(8); // Alt Başlık ile Başlık Arasındaki Boşluk
        $this->SetFont("Helvetica", "", $this->content_size);
        $this->SetX($this->main_title_width);
        $this->Cell(40,10,'(nach § 4 Abs. 5 Nr. 2 EStG)',0,0,'C'); // Alt Başlık
        $this->Ln(17); // Header'ın Altındaki Boşluk
    }

    public function AcceptPageBreak() {
        return true;
    }

    public function day($day) {
        $text = $day == 1 ? " Tag" : " Tage";
        $this->SetFont("Helvetica","B",$this->content_size);
        $this->SetX($this->indent); // Sabit Girinti (30 Milimetre)
        $this->Cell($this->title_cell_width, $this->default_cell_height, 'Tag der Bewirtung', 1);
        $this->SetFont("Helvetica", "", $this->content_size);
        $this->Cell($this->content_cell_width, $this->default_cell_height, $day . $text,1);
        $this->Ln(15);
    }

    public function content($variable, $first_text, $second_text = null, $break = null) { // Kod tekrarı yapmamak için tablo tekrarlarını burada tanımlıyoruz.
        $first_y_position = $this->GetY(); // "Y" ekseninin ilk değerini alıyoruz.
        $this->SetFont("Helvetica","B",$this->content_size);
        $this->SetX($this->indent);
        $this->Cell($this->title_cell_width, 5, $first_text, "T");
        $this->Ln();

        if($second_text) {
            $this->SetX($this->indent);
            $this->SetFont("Helvetica", "", $this->content_size);
            $this->Cell($this->title_cell_width, 5, $second_text);
            $this->Ln();
        }

        $first = $this->GetY(); // "Title Cell" için Y ekseninin pozisyonunu alıyoruz.
        $this->SetXY($this->indent + $this->title_cell_width, $first_y_position); // Aldığımız "Y" ekseni pozisyonunu içerik sütununu eklemek için kullanıyoruz.
        $this->SetFont("Helvetica", "", $this->content_size);
        $this->MultiCell($this->content_cell_width, $this->multi_cell_line_height, self::convertEOL($variable),"T", "L");
        $second = $this->GetY(); // "Content Cell" için Y ekseninin pozisyonunu alıyoruz.
        $this->drawTable($first_y_position, max($first, $second)); // Hangisi daha büyükse onu gönderiyoruz, ona göre dikey çizginin uzunluğunu belirliyoruz.

        if(isset($break)) { // Bir sonraki içeriğin "Y" konumunu burada belirliyoruz.
            $this->SetY(max($first, $second) + $break);
        }
    }

    public function placeOfStay($address) {
        $first_text = "Ort der Bewirtung";
        $second_text = "(genauer Name & Adresse)";
        $variable = $address;
        $this->content($variable, $first_text, $second_text, 8);
    }

    public function host($host) {
        $first_text = "Bewirtende Person";
        $second_text = "(Gastgeber)";
        $variable = $host;
        $this->content($variable, $first_text, $second_text, 0);
    }

    public function visitors($visitors) {
        $count = count($visitors);
        $y_position = $y_start_position = $this->GetY();
        $this->SetFont("Helvetica", "B", $this->content_size);
        $this->SetXY($this->indent, $y_start_position);
        $this->Cell($this->title_cell_width, $this->default_cell_height, "Bewirtete Personen");
        $this->SetFont("Helvetica", "", $this->content_size);
        $row = max($count, 8);

        while($row > 0) {
            foreach($visitors as $visitor) {
                $this->SetXY($this->indent + $this->title_cell_width, $y_position);
                $this->MultiCell($this->content_cell_width, $this->multi_cell_line_height, $visitor, 1);
                $row--;
                $y_position = $this->GetY();

                if($y_position >= 267) { // Sayfa taşması durumunda verilecek tepkileri burada kontrol ediyoruz.
                    $this->Line($this->indent, $y_start_position, $this->indent, $y_position);
                    $this->Line($this->indent, $y_position, $this->indent + $this->title_cell_width, $y_position);
                    $this->AddPage();
                    $y_start_position = $this->GetY();
                    $this->SetX($this->indent);
                    $this->SetFont("Helvetica", "B", $this->content_size);
                    $this->Cell($this->title_cell_width, $this->default_cell_height, "Bewirtete Personen");
                    $this->SetFont("Helvetica", "", $this->content_size);
                    $this->Line($this->indent, $y_start_position, $this->indent + $this->title_cell_width, $y_start_position);
                    $y_position = $this->GetY();
                }
            }

            $visitors = [];

            if($count < 8) { // 8 veya daha fazla misafir varsa boş sütun çizdirmiyoruz.
                $this->SetXY($this->indent + $this->title_cell_width, $y_position);
                $this->MultiCell($this->content_cell_width, $this->multi_cell_line_height, null, 1);
            }

            $row--;
            $y_position = $this->GetY();
        }

        $this->Line($this->indent, $y_start_position, $this->indent, $y_position);
        $this->Line($this->indent, $y_position, $this->indent + $this->title_cell_width, $y_position);
        $this->Ln(8);
    }

    public function reason($reason) {
        $y_start_position = $this->GetY();
        $total_lines = 0;

        foreach(preg_split("/\r\n/", self::convertEOL($reason)) as $line) { // MultiCell'in yaklaşık satır sayısını burada hesaplıyoruz.
            $line_number = $this->GetStringWidth($line) / $this->content_cell_width; // Metnin uzunluğunu içerik satırının genişliğine bölüyoruz.
            $total_lines += intval(round($line_number, 1)) + 1; // Metin, satır sınırına yakınsa önlem olsun diye "Line Break" yapıyoruz.
        }

        if($this->GetY() >= $this->height - $this->page_break_mm - (max($this->default_cell_height * 2, $this->multi_cell_line_height * $total_lines))) { // Taşma durumu burada kontrol ediliyor.
            $this->AddPage();
            $y_start_position = $this->GetY();
        }

        $this->SetFont("Helvetica", "B", $this->content_size);
        $this->SetX($this->indent);
        $this->Cell($this->title_cell_width, $this->default_cell_height, "Anlass der Bewirtung", "T");
        $this->SetFont("Helvetica", "", $this->content_size);
        $this->SetXY($this->indent + $this->title_cell_width, $y_start_position);
        $this->MultiCell($this->content_cell_width, $this->multi_cell_line_height, self::convertEOL($reason), "T");
        $y_content_position = $this->GetY();
        $this->drawTable($y_start_position, max($y_start_position + ($this->default_cell_height * 2), $y_content_position));
        $this->SetY(max($y_start_position + ($this->default_cell_height * 2), $y_content_position) + 8);
    }

    public function currencyContent($text, $price, $currency) {
        $this->SetX($this->indent);
        $this->SetFont("Helvetica", "B", $this->content_size);
        $this->Cell($this->currency_cell_weight, $this->default_cell_height, $text);
        $this->SetX($this->indent + $this->currency_cell_weight + 20);
        $this->Cell($this->amount_cell_weight, $this->default_cell_height, number_format($price, 2, ",", "."), "B", 0, "C");
        $this->SetX($this->GetX() + 1);
        $this->Cell(10, $this->default_cell_height, $currency);

        if($text == "Höhe der Aufwendungen gemäß beigefügter Rechnung:") {
            $this->SetX($this->GetX() - 3);
            $this->SetFont("Helvetica", "", $this->content_size);
            $this->Cell(20, $this->default_cell_height, "(inkl. MwSt.)");
        }

        $this->Ln(8);
    }

    public function signature($receipt_amount, $tip, $total_amount, $currency, $date, $address) {
        $total_lines = 0;

        foreach(preg_split("/\r\n/", self::convertEOL($address)) as $line) { // MultiCell'in yaklaşık satır sayısını burada hesaplıyoruz.
            $line_number = $this->GetStringWidth($line) / $this->address_cell_weight; // Metnin uzunluğunu içerik satırının genişliğine bölüyoruz.
            $total_lines += intval(round($line_number, 1)) + 1; // Metin, satır sınırına yakınsa önlem olsun diye "Line Break" yapıyoruz.
        }

        if($this->GetY() >= $this->height - $this->page_break_mm - ($this->default_cell_height + ($this->multi_cell_line_height * ($total_lines + 1))) - 39) {
            $this->AddPage();
        }

        $this->currencyContent("Höhe der Aufwendungen gemäß beigefügter Rechnung:", $receipt_amount, $currency);
        $this->currencyContent("Trinkgeld:", $tip, $currency);
        $this->currencyContent("Gesamtbetrag:", $total_amount, $currency);
        $this->Ln(15);
        $this->SetFont("Helvetica", "B", $this->content_size);
        $this->SetX($this->indent);
        $this->Cell($this->address_cell_weight, $this->default_cell_height, "Ort, Datum", 1);
        $this->SetX($this->indent + $this->address_cell_weight);
        $this->Cell($this->signature_cell_weight, $this->default_cell_height, "Unterschrift des Bewirtenden", 1);
        $this->Ln();
        $first_y_position = $this->GetY();
        $this->SetFont("Helvetica", "", $this->content_size);
        $this->SetX($this->indent);
        $this->MultiCell($this->address_cell_weight, $this->multi_cell_line_height, self::convertEOL($address));
        $this->SetX($this->indent);
        $this->SetFont("Helvetica", "B", $this->content_size);
        $this->MultiCell($this->address_cell_weight, $this->default_cell_height, Carbon::parse($date)->format("d.m.Y"));
        $last_y_position = $this->GetY();
        $this->Line($this->indent, $first_y_position, $this->indent, $last_y_position);
        $this->Line($this->indent + $this->address_cell_weight, $first_y_position, $this->indent + $this->address_cell_weight, $last_y_position);
        $this->Line($this->width - $this->indent, $first_y_position, $this->width - $this->indent, $last_y_position);
        $this->Line($this->indent, $last_y_position, $this->width - $this->indent, $last_y_position);
    }

    public function drawTable($first, $second) {
        $this->Line($this->indent, $first, $this->indent, $second); // İlk Dikey Çizgi
        $this->Line($this->indent + $this->title_cell_width, $first, $this->indent + $this->title_cell_width, $second); // İkinci Dikey Çizgi
        $this->Line($this->width - $this->indent, $first, $this->width - $this->indent, $second); // Üçüncü Dikey Çizgi
        $this->Line($this->indent, $second, $this->width - $this->indent, $second); // Kapatma Çizgisi
    }

    public function createPDF($hospitality, $visitors) {
        $this->SetAutoPageBreak(true, $this->page_break_mm);
        $this->AddPage(); // İlk sayfayı ekliyoruz.
        $this->day($hospitality->day); // Günü işledik.
        $this->placeOfStay($hospitality->place_of_stay); // Konaklama yerini işledik.
        $this->host($hospitality->host); // Ev sahibini işledik.
        $this->visitors($visitors); // Misafirleri işledik.
        $this->reason($hospitality->reason); // Ziyaret Sebebini işledik.
        $this->signature($hospitality->receipt_amount, $hospitality->tip, $hospitality->total_amount, $hospitality->currency, $hospitality->date, $hospitality->address);

        return $this->Output("S");
    }

    public static function convertEOL($text) {
        $text = htmlspecialchars_decode($text);
        $before = ["<p><br></p><p></p>", "</p><p></p>", "<br></p><p>", "</p><p>", "<p>", "</p>", "<br>", "&nbsp;"];
        $after = ["<br>", "<br>", "<br>", "<br>", "", "", PHP_EOL, ""];

        return str_replace($before, $after, $text);
    }
}
