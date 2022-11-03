<?php

namespace Reducktion\Socrates\Constants;

enum Country: string
{
    /**
     * European countries
     */
    case Albania = 'AL';
    case Belgium = 'BE';
    case BosniaHerzegovina = 'BA';
    case Bulgaria = 'BG';
    case Croatia = 'HR';
    case CzechRepublic = 'CZ';
    case Denmark = 'DK';
    case Estonia = 'EE';
    case Finland = 'FI';
    case France = 'FR';
    case Germany = 'DE';
    case Greece = 'GR';
    case Hungary = 'HU';
    case Iceland = 'IS';
    case Ireland = 'IE';
    case Italy = 'IT';
    case Kosovo = 'XK';
    case Latvia = 'LV';
    case Lithuania = 'LT';
    case Luxembourg = 'LU';
    case Moldova = 'MD';
    case Montenegro = 'ME';
    case Netherlands = 'NL';
    case NorthMacedonia = 'MK';
    case Norway = 'NO';
    case Poland = 'PL';
    case Portugal = 'PT';
    case Romania = 'RO';
    case Serbia = 'RS';
    case Slovakia = 'SK';
    case Slovenia = 'SI';
    case Spain = 'ES';
    case Sweden = 'SE';
    case Switzerland = 'CH';
    case Turkey = 'TR';
    case Ukraine = 'UA';
    case UnitedKingdom = 'GB';

    /**
     * North american countries
     */
    case Canada = 'CA';
    case Mexico = 'MX';
    case UnitedStates = 'US';

    /**
     * South american countries
     */
    case Argentina = 'AR';
    case Brazil = 'BR';
    case Chile = 'CL';
    case Ecuador = 'EC';
    case Peru = 'PE';
    case Uruguay = 'UY';
}
