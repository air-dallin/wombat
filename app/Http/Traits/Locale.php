<?php


namespace App\Http\Traits;


trait Locale
{
    public function checkLang(){
        return app()->getLocale()=='oz'?'en': app()->getLocale();
    }

    public function getTitle(){
        $title = 'title_'.$this->checkLang();
        return $this->$title;
    }
    public function getText($list = false){
        $text = 'text_'.$this->checkLang();
        $text = $this->$text;

        if($list){
            $text = preg_replace('[\n]','<br>',$text);
        }

        return html_entity_decode($text);
    }
    public function getAddress(){
        $title = 'address_'.$this->checkLang();
        return $this->$title;
    }
    public function getMetaTitle() {
        $title = 'meta_title_'.$this->checkLang();
        return $this->$title;
    }
    public function getMetaDesc() {
        $title = 'meta_description_'.$this->checkLang();
        return $this->$title;
    }
    public function getMetaKeys() {
        $title = 'meta_keywords_'.$this->checkLang();
        return $this->$title;
    }

    public function setTextRuAttribute($value) {
        $this->attributes['text_ru'] = trim(htmlspecialchars($value));
    }
    public function setTextUzAttribute($value) {
        $this->attributes['text_uz'] = trim(htmlspecialchars($value));
    }
    public function setTextEnAttribute($value) {
        $this->attributes['text_en'] = trim(htmlspecialchars($value));
    }
    public function setTextOzAttribute($value) {
        $this->attributes['text_en'] = trim(htmlspecialchars($value));
    }

}
