<?php

namespace App\Services;

class LocaleService
{
    private $flags = [
        'en' => 'us',
        'da' => 'dk',
        'el' => 'gr',
        'cs' => 'cz',
        'sv' => 'se',
        'sl' => 'si',
        'et' => 'ee',
        'zh' => 'cn',
        'ko' => 'kr',
        'ja' => 'jp',
    ];

    private $names = [
        'en' => 'English',
        'de' => 'Deutsch',
        'es' => 'Español',
        'fr' => 'Français',
        'pt' => 'Português',
        'nl' => 'Nederlands',
        'ru' => 'Русский',
        'cs' => 'Česky',
        'it' => 'Italiano',
        'fi' => 'Suomi',
        'sv' => 'Svenska',
        'hu' => 'Magyar',
        'el' => 'Ελληνικά',
        'da' => 'Dansk',
        'lv' => 'Latviešu',
        'lt' => 'Lietuvių',
        'et' => 'Eesti',
        'sk' => 'Slovenčina',
        'sl' => 'Slovenščina',
        'ko' => '한국어',
        'zh' => '中文',
        'ja' => '日本語',
    ];

    private $locales;
    private $locale; // current user locale

    public function __construct()
    {
        $this->locale = app()->getLocale();

        // English locale is available by default
        $this->locales = new \stdClass();
        $this->locales->en = new \stdClass();
        $this->locales->en->flag = $this->flags['en'];
        $this->locales->en->name = $this->names['en'];

        // loop through language files
        foreach (glob(resource_path('lang/*.json')) as $filePath) {
            $languageCode = substr($filePath, strrpos($filePath, '/') + 1, 2);
            $this->locales->$languageCode = new \stdClass();
            $this->locales->$languageCode->flag = array_key_exists($languageCode, $this->flags) ? $this->flags[$languageCode] : $languageCode;
            $this->locales->$languageCode->name = isset($this->names[$languageCode]) ? $this->names[$languageCode] : $languageCode;
        }
    }


    /**
     * Get currenct language code
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    public function locale()
    {
        return $this->locales->{$this->locale};
    }

    /**
     * Get all locales
     *
     * @return \stdClass
     */
    public function locales()
    {
        return $this->locales;
    }

    /**
     * Get all locales codes, i.e. en, de, fr etc
     * @return array
     */
    public function codes()
    {
        return array_keys(get_object_vars($this->locales));
    }

    /**
     * Get current locale code
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    public function code()
    {
        return $this->locale;
    }
}
