<?php
namespace PHPMVC\LIB\Template;


trait TemplateHelper
{
    public function matchUrl($urls) {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if(is_array($urls) && !empty($urls)) {
            foreach ($urls as $url) {
                if((bool) preg_match("/" . preg_quote($url, '/'). "$/i", $path) === true) {
                    return true;
                }
            }
        } else {
            return $path === $urls;
        }
    }

    public function labelFloat($fieldName, $object = null)
    {
        return ((isset($_POST[$fieldName]) && !empty($_POST[$fieldName])) || (null !== $object && $object->$fieldName !== null)) ? ' class="floated"' : '';
    }

    public function showValue($fieldName, $object = null)
    {
        return isset($_POST[$fieldName]) ? $_POST[$fieldName] : (is_null($object) ? '' : $object->$fieldName);
    }

    public function selectedIf($fieldName, $value, $object = null)
    {
        return ((isset($_POST[$fieldName]) && $_POST[$fieldName] == $value) || (!is_null($object) && $object->$fieldName == $value)) ? 'selected="selected"' : '';
    }

    public function radioCheckedIf($fieldName, $value, $object = null)
    {
        return ((isset($_POST[$fieldName]) && $_POST[$fieldName] == $value) || ($object !== null && $object->$fieldName == $value)) ? 'checked' : '';
    }

    public function boxCheckedIf($fieldName, $value, $object = null)
    {
        return ((isset($_POST[$fieldName]) && is_array($_POST[$fieldName]) && in_array($value, $_POST[$fieldName])) || ($object !== null && is_array($object->$fieldName) && in_array($value, $object->$fieldName))) ? 'checked' : '';
    }
}