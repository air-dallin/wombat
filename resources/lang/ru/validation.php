<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    |  following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute должны быть приняты.',
    'active_url'           => 'Это :attribute  недопустимый URL-адрес',
    'after'                => ':attribute должна быть дата после :date.',
    'after_or_equal'       => ':attribute должна быть дата после или равная :date.',
    'alpha'                => ':attribute может содержать только буквы.',
    'alpha_dash'           => ':attribute может содержать только буквы, цифры, тире и подчеркивания.',
    'alpha_num'            => ':attribute может содержать только буквы и цифрыs.',
    'array'                => ':attribute должно быть массивом.',
    'before'               => ':attribute должно быть свидание до :date.',
    'before_or_equal'      => ':attribute должна быть дата, предшествующая или равная :date.',
    'between'              => [
        'numeric' => ':attribute должно быть между :min и :max.',
        'file'    => ':attribute должно быть от :min до :max килобайт.',
        'string'  => ':attribute должно быть между символами :min и :max.',
        'array'   => ':attribute должно быть от :min до :max элементов.',
    ],
    'boolean'              => ':attribute поле должно быть true или false.',
    'confirmed'            => ':attribute подтверждение не соответствует.',
    'date'                 => ':attribute не является действительной датой.',
    'date_equals'          => ':attribute должна быть дата, равная :date.',
    'date_format'          => ' :attribute не соответствует формату :format.',
    'different'            => ' :attribute и :other должны быть другими.',
    'digits'               => ' :attribute должно :digits цифры.',
    'digits_between'       => ' :attribute должно быть между цифрами :min и :max цифры.',
    'dimensions'           => ' :attribute имеет недопустимые размеры изображения.',
    'distinct'             => ' :attribute поле имеет повторяющееся значение.',
    'email'                => ' :attribute должен быть действительный адрес электронной почты.',
    'ends_with'            => ' :attribute должно заканчиваться одним из следующих значений:: :values.',
    'exists'               => ' :attribute является недействительным.',
    'file'                 => ' :attribute должен быть файл.',
    'filled'               => ' :attribute поле должно иметь значение.',
    'gt'                   => [
        'numeric' => ' :attribute должно быть больше, чем :value.',
        'file'    => ' :attribute должно быть больше :value килобайт.',
        'string'  => ' :attribute должно быть больше, чем :value значения.',
        'array'   => ' :attribute должно быть больше, чем :value ценности.',
    ],
    'gte'                  => [
        'numeric' => ' :attribute должно быть больше или равно :value.',
        'file'    => ' :attribute должно быть больше или равно :value килобайт.',
        'string'  => ' :attribute должно быть больше или равно :value значения.',
        'array'   => ' :attribute должно быть :value предметы или более.',
    ],
    'image'                => 'изображение :attribute должно быть.',
    'in'                   => '  :attribute является недействительным.',
    'in_array'             => ' :attribute поле не существует в :other.',
    'integer'              => ' :attribute должно быть целым числом.',
    'ip'                   => ' :attribute должен быть действительный IP-адрес.',
    'ipv4'                 => ' :attribute  должен быть действительный IPv4-адрес.',
    'ipv6'                 => ' :attribute должен быть действительный IPv6-адрес.',
    'json'                 => ' :attribute must be a valid JSON string.',
    'lt'                   => [
        'numeric' => ' :attribute должно быть меньше  :value.',
        'file'    => ' :attribute должно быть меньше :value килобайт.',
        'string'  => ' :attribute должно быть меньше символов :value значения.',
        'array'   => ' :attribute должно быть меньше, чем :value ценности.',
    ],
    'lte'                  => [
        'numeric' => ' :attribute должно быть меньше или равно :value.',
        'file'    => ' :attribute должно быть меньше или равно :value килобайт.',
        'string'  => ' :attribute должно быть меньше или равно :value значения.',
        'array'   => ' :attribute не должно быть более :value ценности.',
    ],
    'max'                  => [
        'numeric' => ' :attribute не может быть больше :max.',
        'file'    => ' :attribute не может превышать :max килобайт.',
        'string'  => ' :attribute не может превышать :max символов.',
        'array'   => ' :attribute может содержать не более :max элементов.',
    ],
    'mimes'                => ' :attribute должен быть файл типа type: :values.',
    'mimetypes'            => ' :attribute должен быть файл типа type: :values.',
    'min'                  => [
        'numeric' => ' :attribute должно быть, по крайней мере :min.',
        'file'    => ' :attribute должно быть не менее :min килобайт.',
        'string'  => ' :attribute должно быть не менее :min символов.',
        'array'   => ' :attribute должно быть не :min предметов.',
    ],
    'multiple_of'          => ' :attribute должно быть кратно :value значение',
    'not_in'               => ' selected :attribute является недействительным.',
    'not_regex'            => ' :attribute является недействительным.',
    'numeric'              => ' :attribute должно быть число.',
    'password'             => ' Неверный пароль.',
    'present'              => ' :attribute поле должно присутствовать.',
    'regex'                => ' :attribute формат недопустим.',
    'required'             => ' :attribute поле обязательно для заполнения.',
    'required_if'          => ' :attribute поле обязательно, когда :other равно :value.',
    'required_unless'      => ' :attribute поле является обязательным, если только :other не находится в :values.',
    'required_with'        => ' :attribute поле обязательно, если присутствует :values.',
    'required_with_all'    => ' :attribute поле является обязательным, если присутствуют :values.',
    'required_without'     => ' :attribute поле является обязательным, если :values отсутствует.',
    'required_without_all' => ' :attribute поле является обязательным, если не присутствует ни одно из значений :values',
    'same'                 => ' :attribute и :other другие должны соответствовать.',
    'size'                 => [
        'numeric' => ' :attribute должно быть :size.',
        'file'    => ' :attribute должно быть :size килобайт.',
        'string'  => ' :attribute должно быть :size символов.',
        'array'   => ' :attribute должно содержать :size размера.',
    ],
    'starts_with'          => ' :attribute должно начинаться с одного из следующих значений: :values.',
    'string'               => ' :attribute должно быть, строка.',
    'timezone'             => ' :attribute должна быть действительная зона.',
    'unique'               => ' :attribute уже принят.',
    'uploaded'             => ' :attribute не удалось загрузить.',
    'url'                  => ' :attribute формат недопустим.',
    'uuid'                 => ' :attribute должен быть действительный UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    |  following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'firstname'  => 'Имя',
        'middlename' => 'Фамилия',
        'title_ru' => 'Название на русском языке',
        'title_uz' => 'Название на узбекском языке',
        'title_en' => 'Название на английском языке',
    ],

];
