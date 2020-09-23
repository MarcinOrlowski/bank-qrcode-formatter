# Bank QR Code formatter #

Biblioteka formatująca dane dot. przelewu bankowego pod kątem generowania
kodów QR rozpoznawanych przez np. aplikacje mobilne banków operujących na rynku
polskim (np. mBank, Inteligo). Wynikiem działania biblioteki jest ciąg znaków
(string) sformatowany zgodnie z rekomendacją dot. kodów 2D opublikowaną przez
[Związek Banków Polskich](https://zbp.pl/public/repozytorium/dla_bankow/rady_i_komitety/bankowosc_elektroczniczna/rada_bankowosc_elektr/zadania/2013.12.03_-_Rekomendacja_-_Standard_2D.pdf
). Otrzymany ciąg znaków należy użyć do wygenerowania kodu QR, używając
do tego dowolnej biblioteki do tego przeznaczonej.

[![Latest Stable Version](https://poser.pugx.org/marcin-orlowski/bank-qrcode-formatter/v/stable)](https://packagist.org/packages/marcin-orlowski/bank-qrcode-formatter)
[![Build Status](https://travis-ci.org/MarcinOrlowski/bank-qrcode-formatter.svg?branch=master)](https://travis-ci.org/MarcinOrlowski/bank-qrcode-formatter)
[![Code Quality](https://scrutinizer-ci.com/g/MarcinOrlowski/bank-qrcode-formatter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/MarcinOrlowski/bank-qrcode-formatter/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/MarcinOrlowski/bank-qrcode-formatter/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/MarcinOrlowski/bank-qrcode-formatter/?branch=master)
[![Codacy Grade Badge](https://api.codacy.com/project/badge/Grade/2cb056aba92b417981bd1f99a38352f3)](https://www.codacy.com/app/MarcinOrlowski/bank-qrcode-formatter)
[![License](https://poser.pugx.org/marcin-orlowski/bank-qrcode-formatter/license)](https://packagist.org/packages/marcin-orlowski/bank-qrcode-formatter)

[![DEV Build Status](https://travis-ci.org/MarcinOrlowski/bank-qrcode-formatter.svg?branch=dev)](https://travis-ci.org/MarcinOrlowski/bank-qrcode-formatter)
[![Code Quality](https://scrutinizer-ci.com/g/MarcinOrlowski/bank-qrcode-formatter/badges/quality-score.png?b=dev)](https://scrutinizer-ci.com/g/MarcinOrlowski/bank-qrcode-formatter/?branch=dev)
[![Code Coverage](https://scrutinizer-ci.com/g/MarcinOrlowski/bank-qrcode-formatter/badges/coverage.png?b=dev)](https://scrutinizer-ci.com/g/MarcinOrlowski/bank-qrcode-formatter/?branch=dev)
[![Codacy Grade Badge](https://api.codacy.com/project/badge/Grade/2cb056aba92b417981bd1f99a38352f3)](https://www.codacy.com/app/MarcinOrlowski/bank-qrcode-formatter)

## Wymagania ##

1. PHP v5.6+
1. Rozszerzenie `mbstring`

## Instalacja ##

```bash
composer require marcin-orlowski/bank-qrcode-formatter
```

## Użycie ##

```
<?php

$qr = new \MarcinOrlowski\QrcodeFormatter\Builder();

$str = $qr->name('Marcin sp. z o.o.')
          ->vatId('0123456789')
          ->bankAccount('01234567890123456789012345')
          ->country('PL')
          ->title('FV 1234/2020')
          ->amount(140.50)
          ->build();

// zwrócony ciąg znaków ($str) należy następnie użyć z dowolną biblioteką
// do generowania kodów QR
createQrcode($str, '/tmp/qrcode.png');
```

**UWAGA:** Bankowe aplikacje mobilne przeprowadzają weryfikacje danych  
odczytanych z kodu QR, zatem testując niniejszą bibliotekę, należy użyć
prawidłowych danych (tj. numer NIP czy numer rachunku bankowego), w
przeciwnym razie wygenerowany kod QR zostanie odrzucony przez
większość (jeśli nie wszystkie) aplikacje.

# API #

## Utworzenie instancji ##

 * `public function __construct($type, $strict_mode)`
    * `$type` (`int`): typ odbiorcy płatności. Dozwolone wartości to `Builder::TYPE_PERSON` (jeśli odbiorcą jest osoba fizyczna)
    lub `Builder::TYPE_COMPANY` jeśli odbiorcą jest firma. Argument opcjonalny (domyślna wartość `Builder::TYPE_PERSON`).
    * `$strict_mode` (`bool`): kontroluje try `strict_mode` (patrz `strict_mode()`). Domyślnie `false`.

## Ustawianie parametrów dot. płatności ##

Metody oznaczone **(wymagane)** dotyczą ustawiania wymaganych parametrów płatności i muszą zostać wywołane przed wywołaniem
`build()`. Wszystkie metody zwracają `$this`, co pozwala łączyć ich wywołania łańcuchowo.

 * `public function vatId($vat_id)`: numer podatkowy (NIP) odbiorcy płatności. Podanie NIP-u jest wymagane dla odbiorcy
   korporacyjnego (`TYPE_COMPANY`). dla odbiorców będących osobami fizycznymi podanie NIP nie jest wymagane.
   * `$vat_id` (`string`|`int`|`null`): numer podatkowy odbiorcy (8 cyfr). Podanie `null` kasuje wprowadzoną wcześniej wartość.
 * `public function bankAccount($account)` **(wymagane)**: docelowy numer rachunku bankowego odbiorcy płatności.
   * `$account` (`string`) - numer rachunku bankowego (26 cyfr). Dozwolone jest także używanie znaków spacji oddzielających
   poszczególne cyfry numer lub ich grupy (zostaną one usunięte).
 * `public function name($name)` **(wymagane)**: nazwa odbiorcy płatności.
   * `$name` (`string`): maksymalna długość to 20 znaków. Widące i zamykające spacje są automatycznie usuwane (`trim()`).
   Jeśli wynikowy ciąg jest dłuższy niż dozwolony, zostanie automatycznie skrócony o ile tryb `strict_mode` nie jest aktywny,
   w przeciwnym razie wystąpi `InvalidArgumentException`.
 * `public function country($code)`: dwuliterowy kod kraju odbiorcy płatności.
   * `$code` (`string`|`null`): dwuliterowy kod kraju odbiorcy płatności (np. `PL`). Podanie `null` kasuje wprowadzoną wcześniej wartość.
 * `public function title($title)` **(wymagane)**: tytuł/opis płatności.
   * `$title` (`string`): maksymalna długość to 32 znaki. Wiodące i zamykające spacje są automatycznie usuwane.
   Jeśli wynikowy ciąg jest dłuższy niż dozwolony, zostanie automatycznie skrócony o ile tryb `strict_mode` nie jest aktywny,
   w przeciwnym razie wystąpi `InvalidArgumentException`.
 * `public function amount($amount)` **(wymagana)**: kwota płatności wyrażona w groszach (np `1000` to `10,00 PLN`)
   * `$amount` (`int`|`float`): jeśli podana wartość jest typu `int`, uznana jest za wartość wyrażoną w groszach. Gdy podana wartość
   jest typu `float`, zostanie uznana za wyrażoną w złotych (grosze w części ułamkowej). Przykładowo: `(int) 1012` oraz `float 10.12`
   są tożsame. Minimalna dozwolona wartość to `0` która oznacza, iż kwota przelewu musi zostać wprowadzona przez użytkownika
   w aplikacji bankowej po zeskanowaniu kodu QR. Maksymalna dozwolona wartość to `999999` dla kwoty podanej jako `int` oraz
   `9999.99` dla typu `float`. Podanie wartości ujemnej lub przekraczającej maksymalną dozwoloną wartość skutkuje wyjątkiem
   `OutOfRangeException`.
 * `public function reserved1($id)` lub `public function refId($id)`: zarezerwowane opcjonalne pole, przeznaczone np. na numer referencyjny
   płatności etc.
   * `$id` (`string`): ciąg o długości do 20 znaków. Podanie dłuższego ciągu zawsze skutkuje wyjątkiem `InvalidArgumentException`.
 * `public function reserved2($id)` lub `public function invobill($id)`: zarezerwowane opcjonalne pole, przeznaczone np. na numer
   referencyjny Invobill.
   * `$id` (`string`): ciąg o długości do 12 znaków. Podanie dłuższego ciągu zawsze skutkuje wyjątkiem `InvalidArgumentException`.
 * `public function reserved3($id)`: zarezerwowane opcjonalne pole
   * `$id` (`string`): ciąg o długości do 24 znaków. Podanie dłuższego ciągu zawsze skutkuje wyjątkiem `InvalidArgumentException`.

## Wygenerowanie sformatowanego ciągu ##

 * `public function build()`: generuje sformatowany ciąg znaków odpowiadający ustawionym parametrom płatności. Zwracana
    wartość jest typu `string` i nie przekracza `160` znaków.

## Metody dodatkowe ##

 * `public function strictMode($mode)`: kontroluje działanie trybu `strict_mode`. Metody, które automatycznie akceptują
   i skracają argumenty typu (`string`) przekraczające maksymalną dozwoloną długość (np. `title()`), w trybie `strict_mode`
   będą rzucały wyjątek `InvalidArgumentException`.

# Licencja #

 * Written and copyrighted &copy;2020 by Marcin Orlowski <mail (#) marcinorlowski (.) com>
 * Bank-Qrcode-Formatter is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
