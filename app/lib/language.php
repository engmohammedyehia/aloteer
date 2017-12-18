<?php
namespace PHPMVC\LIB;

class Language 
{
    private $dictionary = [];
    private $baseDictioinary = [];

    public function load($path)
    {
        $defaultLanguage = APP_DEFAULT_LANGUAGE;
        if(isset($_SESSION['lang'])) {
            $defaultLanguage = $_SESSION['lang'];
        }
        $defaultLanguageFileToLoad = LANGUAGES_PATH . APP_DEFAULT_LANGUAGE . DS . str_replace('.', DS , $path) . '.lang.php';
        $languageFileToLoad = LANGUAGES_PATH . $defaultLanguage . DS . str_replace('.', DS , $path) . '.lang.php';
        if(file_exists($languageFileToLoad)) {
            require $languageFileToLoad;
            if(is_array($_) && !empty($_)) {
                foreach ($_ as $key => $value) {
                    $this->dictionary[$key] = $value;
                }
            }
            require $defaultLanguageFileToLoad;
            if(is_array($_) && !empty($_)) {
                foreach ($_ as $key => $value) {
                    $this->baseDictioinary[$key] = $value;
                }
            }
            $missingKeys = array_values(array_diff(array_keys($this->baseDictioinary), array_keys($this->dictionary)));
            foreach ($missingKeys as $missingKey) {
                $this->dictionary[$missingKey] = $this->baseDictioinary[$missingKey];
            }
            $this->baseDictioinary = [];
        } else {
            $defaultLanguageFileToLoad = LANGUAGES_PATH . APP_DEFAULT_LANGUAGE . DS . str_replace('.', DS , $path) . '.lang.php';
            require $defaultLanguageFileToLoad;
            if(is_array($_) && !empty($_)) {
                foreach ($_ as $key => $value) {
                    $this->dictionary[$key] = $value;
                }
            }
        }
    }

    public function get($key)
    {
        if(array_key_exists($key, $this->dictionary)) {
            return $this->dictionary[$key];
        }
    }

    public function feedKey ($key, $data)
    {
        if(array_key_exists($key, $this->dictionary)) {
            array_unshift($data, $this->dictionary[$key]);
            return call_user_func_array('sprintf', $data);
        }
    }

    public function swapKey ($key, $data)
    {
        if(array_key_exists($key, $this->dictionary)) {
            array_unshift($data, $this->dictionary[$key]);
            $this->dictionary[$key] = call_user_func_array('sprintf', $data);
        }
    }

    public function getDictionary()
    {
        return $this->dictionary;
    }
}