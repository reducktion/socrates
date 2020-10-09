<?php

namespace Reducktion\Socrates\Config;

abstract class Countries
{
    public static $all = [
        'AF',
        'AX',
        'AL',
        'DZ',
        'AS',
        'AD',
        'AO',
        'AI',
        'AQ',
        'AG',
        'AR',
        'AM',
        'AW',
        'AU',
        'AT',
        'AZ',
        'BS',
        'BH',
        'BD',
        'BB',
        'BY',
        'BE',
        'BZ',
        'BJ',
        'BM',
        'BT',
        'BO',
        'BQ',
        'BA',
        'BW',
        'BV',
        'BR',
        'IO',
        'BN',
        'BG',
        'BF',
        'BI',
        'KH',
        'CM',
        'CA',
        'CV',
        'KY',
        'CF',
        'TD',
        'CL',
        'CN',
        'CX',
        'CC',
        'CO',
        'KM',
        'CG',
        'CD',
        'CK',
        'CR',
        'CI',
        'HR',
        'CU',
        'CW',
        'CY',
        'CZ',
        'DK',
        'DJ',
        'DM',
        'DO',
        'EC',
        'EG',
        'SV',
        'GQ',
        'ER',
        'EE',
        'ET',
        'FK',
        'FO',
        'FJ',
        'FI',
        'FR',
        'GF',
        'PF',
        'TF',
        'GA',
        'GM',
        'GE',
        'DE',
        'GH',
        'GI',
        'GR',
        'GL',
        'GD',
        'GP',
        'GU',
        'GT',
        'GG',
        'GN',
        'GW',
        'GY',
        'HT',
        'HM',
        'VA',
        'HN',
        'HK',
        'HU',
        'IS',
        'IN',
        'ID',
        'IR',
        'IQ',
        'IE',
        'IM',
        'IL',
        'IT',
        'JM',
        'JP',
        'JE',
        'JO',
        'KZ',
        'KE',
        'KI',
        'KP',
        'KR',
        'KW',
        'KG',
        'LA',
        'LV',
        'LB',
        'LS',
        'LR',
        'LY',
        'LI',
        'LT',
        'LU',
        'MO',
        'MK',
        'MG',
        'MW',
        'MY',
        'MV',
        'ML',
        'MT',
        'MH',
        'MQ',
        'MR',
        'MU',
        'YT',
        'MX',
        'FM',
        'MD',
        'MC',
        'MN',
        'ME',
        'MS',
        'MA',
        'MZ',
        'MM',
        'NA',
        'NR',
        'NP',
        'NL',
        'NC',
        'NZ',
        'NI',
        'NE',
        'NG',
        'NU',
        'NF',
        'MP',
        'NO',
        'OM',
        'PK',
        'PW',
        'PS',
        'PA',
        'PG',
        'PY',
        'PE',
        'PH',
        'PN',
        'PL',
        'PT',
        'PR',
        'QA',
        'RE',
        'RO',
        'RU',
        'RW',
        'BL',
        'SH',
        'KN',
        'LC',
        'MF',
        'PM',
        'VC',
        'WS',
        'SM',
        'ST',
        'SA',
        'SN',
        'RS',
        'SC',
        'SL',
        'SG',
        'SX',
        'SK',
        'SI',
        'SB',
        'SO',
        'ZA',
        'GS',
        'SS',
        'ES',
        'LK',
        'SD',
        'SR',
        'SJ',
        'SZ',
        'SE',
        'CH',
        'SY',
        'TW',
        'TJ',
        'TZ',
        'TH',
        'TL',
        'TG',
        'TK',
        'TO',
        'TT',
        'TN',
        'TR',
        'TM',
        'TC',
        'TV',
        'UG',
        'UA',
        'AE',
        'GB',
        'US',
        'UM',
        'UY',
        'UZ',
        'VU',
        'VE',
        'VN',
        'VG',
        'VI',
        'WF',
        'EH',
        'XK',
        'YE',
        'ZM',
        'ZW',
    ];

    public static $validators = [
        /**
         * Validators for european countries.
         */
        'AL' => \Reducktion\Socrates\Core\Europe\Albania\AlbaniaIdValidator::class,
        'BA' => \Reducktion\Socrates\Core\Europe\BosniaAndHerzegovina\BosniaAndHerzegovinaIdValidator::class,
        'BE' => \Reducktion\Socrates\Core\Europe\Belgium\BelgiumIdValidator::class,
        'BG' => \Reducktion\Socrates\Core\Europe\Bulgaria\BulgariaIdValidator::class,
        'CH' => \Reducktion\Socrates\Core\Europe\Switzerland\SwitzerlandIdValidator::class,
        'CZ' => \Reducktion\Socrates\Core\Europe\CzechRepublic\CzechRepublicIdValidator::class,
        'DK' => \Reducktion\Socrates\Core\Europe\Denmark\DenmarkIdValidator::class,
        'EE' => \Reducktion\Socrates\Core\Europe\Estonia\EstoniaIdValidator::class,
        'ES' => \Reducktion\Socrates\Core\Europe\Spain\SpainIdValidator::class,
        'FI' => \Reducktion\Socrates\Core\Europe\Finland\FinlandIdValidator::class,
        'FR' => \Reducktion\Socrates\Core\Europe\France\FranceIdValidator::class,
        'GB' => \Reducktion\Socrates\Core\Europe\UnitedKingdom\UnitedKingdomIdValidator::class,
        'GR' => \Reducktion\Socrates\Core\Europe\Greece\GreeceIdValidator::class,
        'HR' => \Reducktion\Socrates\Core\Europe\Croatia\CroatiaIdValidator::class,
        'HU' => \Reducktion\Socrates\Core\Europe\Hungary\HungaryIdValidator::class,
        'IE' => \Reducktion\Socrates\Core\Europe\Ireland\IrelandIdValidator::class,
        'IS' => \Reducktion\Socrates\Core\Europe\Iceland\IcelandIdValidator::class,
        'IT' => \Reducktion\Socrates\Core\Europe\Italy\ItalyIdValidator::class,
        'LT' => \Reducktion\Socrates\Core\Europe\Lithuania\LithuaniaIdValidator::class,
        'LU' => \Reducktion\Socrates\Core\Europe\Luxembourg\LuxembourgIdValidator::class,
        'LV' => \Reducktion\Socrates\Core\Europe\Latvia\LatviaIdValidator::class,
        'MD' => \Reducktion\Socrates\Core\Europe\Moldova\MoldovaIdValidator::class,
        'ME' => \Reducktion\Socrates\Core\Europe\Montenegro\MontenegroIdValidator::class,
        'MK' => \Reducktion\Socrates\Core\Europe\NorthMacedonia\NorthMacedoniaIdValidator::class,
        'NL' => \Reducktion\Socrates\Core\Europe\Netherlands\NetherlandsIdValidator::class,
        'NO' => \Reducktion\Socrates\Core\Europe\Norway\NorwayIdValidator::class,
        'PL' => \Reducktion\Socrates\Core\Europe\Poland\PolandIdValidator::class,
        'PT' => \Reducktion\Socrates\Core\Europe\Portugal\PortugalIdValidator::class,
        'RO' => \Reducktion\Socrates\Core\Europe\Romania\RomaniaIdValidator::class,
        'RS' => \Reducktion\Socrates\Core\Europe\Serbia\SerbiaIdValidator::class,
        'SE' => \Reducktion\Socrates\Core\Europe\Sweden\SwedenIdValidator::class,
        'SI' => \Reducktion\Socrates\Core\Europe\Slovenia\SloveniaIdValidator::class,
        'SK' => \Reducktion\Socrates\Core\Europe\Slovakia\SlovakiaIdValidator::class,
        'TR' => \Reducktion\Socrates\Core\Europe\Turkey\TurkeyIdValidator::class,
        'UA' => \Reducktion\Socrates\Core\Europe\Ukraine\UkraineIdValidator::class,
        'XK' => \Reducktion\Socrates\Core\Europe\Kosovo\KosovoIdValidator::class,

        /**
         * Validators for north american countries.
         */
        'CA' => \Reducktion\Socrates\Core\NorthAmerica\Canada\CanadaIdValidator::class,
        'US' => \Reducktion\Socrates\Core\NorthAmerica\UnitedStates\UnitedStatesIdValidator::class,

        /**
         * Validators for south american countries.
         */
        'BR' => \Reducktion\Socrates\Core\SouthAmerica\Brazil\BrazilIdValidator::class
    ];

    public static $extractors = [
        /**
         * Extractors for european countries.
         */
        'AL' => \Reducktion\Socrates\Core\Europe\Albania\AlbaniaCitizenInformationExtractor::class,
        'BA' => \Reducktion\Socrates\Core\Europe\BosniaAndHerzegovina\BosniaAndHerzegovinaCitizenInformationExtractor::class,
        'BE' => \Reducktion\Socrates\Core\Europe\Belgium\BelgiumCitizenInformationExtractor::class,
        'BG' => \Reducktion\Socrates\Core\Europe\Bulgaria\BulgariaCitizenInformationExtractor::class,
        'DK' => \Reducktion\Socrates\Core\Europe\Denmark\DenmarkCitizenInformationExtractor::class,
        'CZ' => \Reducktion\Socrates\Core\Europe\CzechRepublic\CzechRepublicCitizenInformationExtractor::class,
        'EE' => \Reducktion\Socrates\Core\Europe\Estonia\EstoniaCitizenInformationExtractor::class,
        'FI' => \Reducktion\Socrates\Core\Europe\Finland\FinlandCitizenInformationExtractor::class,
        'FR' => \Reducktion\Socrates\Core\Europe\France\FranceCitizenInformationExtractor::class,
        'HR' => \Reducktion\Socrates\Core\Europe\Croatia\CroatiaCitizenInformationExtractor::class,
        'HU' => \Reducktion\Socrates\Core\Europe\Hungary\HungaryCitizenInformationExtractor::class,
        'IS' => \Reducktion\Socrates\Core\Europe\Iceland\IcelandCitizenInformationExtractor::class,
        'IT' => \Reducktion\Socrates\Core\Europe\Italy\ItalyCitizenInformationExtractor::class,
        'LT' => \Reducktion\Socrates\Core\Europe\Lithuania\LithuaniaCitizenInformationExtractor::class,
        'LU' => \Reducktion\Socrates\Core\Europe\Luxembourg\LuxembourgCitizenInformationExtractor::class,
        'LV' => \Reducktion\Socrates\Core\Europe\Latvia\LatviaCitizenInformationExtractor::class,
        'ME' => \Reducktion\Socrates\Core\Europe\Montenegro\MontenegroCitizenInformationExtractor::class,
        'MK' => \Reducktion\Socrates\Core\Europe\NorthMacedonia\NorthMacedoniaCitizenInformationExtractor::class,
        'NO' => \Reducktion\Socrates\Core\Europe\Norway\NorwayCitizenInformationExtractor::class,
        'PL' => \Reducktion\Socrates\Core\Europe\Poland\PolandCitizenInformationExtractor::class,
        'RO' => \Reducktion\Socrates\Core\Europe\Romania\RomaniaCitizenInformationExtractor::class,
        'RS' => \Reducktion\Socrates\Core\Europe\Serbia\SerbiaCitizenInformationExtractor::class,
        'SE' => \Reducktion\Socrates\Core\Europe\Sweden\SwedenCitizenInformationExtractor::class,
        'SI' => \Reducktion\Socrates\Core\Europe\Slovenia\SloveniaCitizenInformationExtractor::class,
        'SK' => \Reducktion\Socrates\Core\Europe\Slovakia\SlovakiaCitizenInformationExtractor::class,
        'UA' => \Reducktion\Socrates\Core\Europe\Ukraine\UkraineCitizenInformationExtractor::class,
        'XK' => \Reducktion\Socrates\Core\Europe\Kosovo\KosovoCitizenInformationExtractor::class,
    ];
}
