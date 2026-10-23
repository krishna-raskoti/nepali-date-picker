<?php

declare(strict_types=1);

namespace Raskoti\NepaliDatePicker\Formatting;

/**
 * Centralized localization data: month names (BS and AD, kept separate —
 * they are different calendars with different names) and weekday names,
 * for both supported locales, in full/short/extra-short forms.
 */
final class LocaleData
{
    public const SUPPORTED_LOCALES = ['en', 'np'];

    // Bikram Sambat month names. No officially standardized short form
    // exists, so the 'M' token falls back to the full name for BS dates.
    public const BS_MONTHS_EN_FULL = [
        'Baishakh',
        'Jestha',
        'Asar',
        'Shrawan',
        'Bhadra',
        'Asoj',
        'Kartik',
        'Mangsir',
        'Poush',
        'Magh',
        'Falgun',
        'Chait',
    ];

    public const BS_MONTHS_NP_FULL = [
        'बैशाख',
        'जेठ',
        'असार',
        'साउन',
        'भदौ',
        'असोज',
        'कार्तिक',
        'मंसिर',
        'पुष',
        'माघ',
        'फागुन',
        'चैत',
    ];

    // Gregorian (AD) month names.
    public const AD_MONTHS_EN_FULL = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
    ];

    public const AD_MONTHS_EN_SHORT = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec',
    ];

    public const AD_MONTHS_NP_FULL = [
        'जनवरी',
        'फेब्रुअरी',
        'मार्च',
        'अप्रिल',
        'मे',
        'जुन',
        'जुलाई',
        'अगस्ट',
        'सेप्टेम्बर',
        'अक्टोबर',
        'नोभेम्बर',
        'डिसेम्बर',
    ];

    // No standard short form in Nepali for Gregorian months; falls back to full.
    public const AD_MONTHS_NP_SHORT = self::AD_MONTHS_NP_FULL;

    // Index 0 = Sunday ... 6 = Saturday.
    public const WEEKDAYS_EN_FULL = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
    ];

    public const WEEKDAYS_EN_SHORT = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    public const WEEKDAYS_EN_EXTRA = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];

    public const WEEKDAYS_NP_FULL = [
        'आइतबार',
        'सोमबार',
        'मंगलबार',
        'बुधबार',
        'बिहीबार',
        'शुक्रबार',
        'शनिबार',
    ];

    public const WEEKDAYS_NP_SHORT = ['आइत', 'सोम', 'मंगल', 'बुध', 'बिही', 'शुक्र', 'शनि'];

    public const WEEKDAYS_NP_EXTRA = ['आ', 'सो', 'मं', 'बु', 'बि', 'शु', 'श'];
}
