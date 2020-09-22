
# Bank QR Code formatter #

 Biblioteka formatująca dane dot. przelewu bankowego pod kątem generowania 
 kodów QR rozpoznawanych przez np. aplikacje mobilne banków operujących na rynku
 polskim. Wynikiem działania biblioteki jest ciąg znaków (string) sformatowany
 zgodnie z rekomendacją [Związku Banków Polskich](https://zbp.pl/public/repozytorium/dla_bankow/rady_i_komitety/bankowosc_elektroczniczna/rada_bankowosc_elektr/zadania/2013.12.03_-_Rekomendacja_-_Standard_2D.pdf
), który następnie należy użyć do wygenerowania kodu QR (używając dowolnej
 biblioteki do tego przeznaczonej).

## Wymagania ##

 PHP v5.3 lub nowszy.
 
## Instalacja ##

```bash
composer require marcin-orlowski/bank-qrcode-formatter
```

# Użycie #

```
<?php

$qr = new \MarcinOrlowski\QrcodeFormatter\Builder();

$str = $qr->name('Marcin sp. z o.o.')
          ->vatId('0000000000')
          ->bankAccount('01234567890123456789012345')
          ->country('PL')
          ->paymentTitle('FV 1234/2020')
          ->amount(140.50)
          ->build();
```