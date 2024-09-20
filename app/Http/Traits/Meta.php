<?php


namespace App\Http\Traits;


trait Meta
{

    public function getMeta($title = '', $description = '', $keywords = [])
    {
        $favicon =  asset('favicon.ico');
        \Butschster\Head\Facades\Meta::setTitleSeparator('|')
            ->prependTitle($title)
            ->setDescription($description, 255)
            ->setKeywords($keywords, 255)
            ->setFavicon($favicon);
    }

}
