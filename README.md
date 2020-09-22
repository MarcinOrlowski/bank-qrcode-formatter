
# Bank QR Code formatter #

 Biblioteka formatująca dane dot. przelewu bankowego pod kątem generowania 
 kodów QR rozpoznawanych przez np. aplikacje mobilne banków operujących na rynku
 polskim. Wynikiem działania biblioteki jest ciąg znaków (string) sformatowany
 zgodnie z rekomendacją [Związku Banków Polskich](https://zbp.pl/public/repozytorium/dla_bankow/rady_i_komitety/bankowosc_elektroczniczna/rada_bankowosc_elektr/zadania/2013.12.03_-_Rekomendacja_-_Standard_2D.pdf
), który następnie należy użyć do wygenerowania kodu QR (używając dowolnej
 biblioteki do tego przeznaczonej).

## Wymagania ##

 1. PHP v5.3 lub nowszy.
 1. Rozszerzenie `mbstring`
 
## Instalacja ##

```bash
composer require marcin-orlowski/bank-qrcode-formatter
```

# Użycie #

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

## Licencja ##

 * Written and copyrighted &copy;2020 by Marcin Orlowski <mail (#) marcinorlowski (.) com>
 * Bank-Qrcode-Formatter is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
