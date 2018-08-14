<?php 

return [
    'accepted' => 'The :attribute должен быть принят.',
    'active_url' => 'The :attribute не является допустимым URL-адресом.',
    'after' => 'The :attribute должна быть дата после :date.',
    'after_or_equal' => 'The :attribute должен быть датой после или равной :date.',
    'alpha' => 'The :attribute может содержать только буквы.',
    'alpha_dash' => 'The :attribute может содержать только буквы, цифры и тире.',
    'alpha_num' => 'The :attribute может содержать только буквы и цифры.',
    'array' => 'The :attribute должен быть массивом.',
    'before' => 'The :attribute должна быть дата до :date.',
    'before_or_equal' => 'The :attribute должна быть дата до или равна :date.',
    'between' => [
        'numeric' => 'The :attribute должен находиться между :min and :max.',
        'file' => 'The :attribute должен находиться между :min and :max килобайты.',
        'string' => 'The :attribute должен находиться между :min and :max символами.',
        'array' => 'The :characters :min and :max товарами.',
    ],
    'boolean' => 'The :поле атрибута должно быть истинным или ложным.',
    'confirmed' => 'The :подтверждение атрибута не соответствует.',
    'date' => 'The :attribute не является допустимой датой.',
    'date_format' => 'The :attribute не соответствует формату :format.',
    'different' => 'The :attribute и:другие должны быть разными.',
    'digits' => 'The :attribute должен быть :цифры цифр.',
    'digits_between' => 'The :attribute должен находиться между :min and :max цифрами.',
    'dimensions' => 'The :attribute имеет недопустимые размеры изображения.',
    'distinct' => 'The :поле атрибута имеет повторяющееся значение.',
    'email' => 'The :attribute должен быть действительным адресом электронной почты.',
    'exists' => 'Выбранный :attribute недействителен.',
    'file' => 'The :attribute должен быть файлом.',
    'filled' => 'The :поле атрибута должно иметь значение.',
    'image' => 'The :attribute должен быть изображением.',
    'in' => 'Выбранный :attribute недействителен.',
    'in_array' => 'The :поле атрибута не существует в :Другие.',
    'integer' => 'The :attribute должен быть целым числом.',
    'ip' => 'The :attribute должен быть действительным IP-адресом.',
    'json' => 'The :attribute должен быть действительной строкой JSON.',
    'max' => [
        'numeric' => 'The :attribute не может быть больше чем :max.',
        'file' => 'The :attribute не может быть больше чем :max kilobytes.',
        'string' => 'The :attribute не может быть больше чем :max characters.',
        'array' => 'The :attribute может не превышать:max Предметы.',
    ],
    'mimes' => 'The :attribute должен быть файлом типа: :значения.',
    'mimetypes' => 'The :attribute должен быть файлом типа: :значения.',
    'min' => [
        'numeric' => 'The :attribute должен быть не менее :min.',
        'file' => 'The :attribute должен быть не менее :min kilobytes.',
        'string' => 'The :attribute должен быть не менее :min символов.',
        'array' => 'The :attribute должен иметь не менее :min предметов.',
    ],
    'not_in' => 'Выбранное :attribute недействителен.',
    'numeric' => 'The :attribute должен быть числом.',
    'present' => 'The поле :attribute должно присутствовать.',
    'regex' => 'The :недопустимый формат атрибута.',
    'required' => 'The поле :attribute необходимо.',
    'required_if' => 'The поле attribute необходимо когда :другое :стоимость.',
    'required_unless' => 'The поле attribute требуется, если :другой находится в :стоимости.',
    'required_with' => 'The поле attribute требуется, когда :значения присутствуют.',
    'required_with_all' => 'The поле attribute требуется, когда :значения присутствуют.',
    'required_without' => 'The поле :attribute требуется :значений нет.',
    'required_without_all' => 'The поле :attribute требуется, если ни один из :значения присутствуют.',
    'same' => 'The :attribute и :другое должно совпадать.',
    'size' => [
        'numeric' => 'The :attribute должен быть :размер.',
        'file' => 'The :attribute должен быть :размер в килобайтах.',
        'string' => 'The :attribute должен быть :размер символов.',
        'array' => 'The :attribute должен содержать :элементы размера.',
    ],
    'string' => 'The :attribute должен быть строкой.',
    'timezone' => 'The :attribute должен быть действительной зоной.',
    'unique' => 'The :attribute уже принят.',
    'uploaded' => 'The :attribute не удалось загрузить.',
    'url' => 'The :недопустимый формат атрибута.',
    'whitelist_email' => 'Этот адрес электронной почты занесен в черный список.',
    'whitelist_domain' => 'Домен вашего адреса электронной почты занесен в черный список.',
    'whitelist_word' => 'The :attribute содержит запрещенные слова или фразы.',
    'whitelist_word_title' => 'The :attribute содержит запрещенные слова или фразы.',
    'mb_between' => 'The :attribute должен находиться между :min and :max characters.',
    'recaptcha' => 'The :Неправильное поле атрибута.',
    'phone' => 'The поле :attribute содержит недопустимое число.',
    'dumbpwd' => 'Этот пароль слишком распространен. Попробуйте другой!',
    'phone_number' => 'Ваш номер телефона недействителен.',
    'valid_username' => 'The поле :attribute должно быть буквенно-цифровой строкой.',
    'allowed_username' => 'The :attribute не допускается.',
    'custom' => [
        'database_connection' => [
            'required' => 'Не могу подключиться к серверу MySQL',
        ],
        'database_not_empty' => [
            'required' => 'База данных не пуста',
        ],
        'promo_code_not_valid' => [
            'required' => 'Промо-код недействителен',
        ],
        'smtp_valid' => [
            'required' => 'Не можете подключиться к SMTP-серверу',
        ],
        'yaml_parse_error' => [
            'required' => 'Невозможно разобрать yaml. Проверьте синтаксис',
        ],
        'file_not_found' => [
            'required' => 'Файл не найден.',
        ],
        'not_zip_archive' => [
            'required' => 'Файл не в формате zip.',
        ],
        'zip_archive_unvalid' => [
            'required' => 'Не удается прочитать пакет.',
        ],
        'custom_criteria_empty' => [
            'required' => 'Пользовательские критерии не могут быть пустыми.',
        ],
        'php_bin_path_invalid' => [
            'required' => 'Недействительный файл PHP. Пожалуйста, проверьте еще раз.',
        ],
        'can_not_empty_database' => [
            'required' => 'Невозможно DROP определенные таблицы, пожалуйста, очистите свою базу данных вручную и повторите попытку.',
        ],
        'recaptcha_invalid' => [
            'required' => 'Неверная проверка reCAPTCHA.',
        ],
        'payment_method_not_valid' => [
            'required' => 'Что-то пошло не так с настройкой метода оплаты. Пожалуйста, проверьте еще раз.',
        ],
    ],
    'attributes' => [
        'gender' => 'Пол',
        'gender_id' => 'Пол',
        'name' => 'имя',
        'first_name' => 'имя',
        'last_name' => 'фамилия',
        'user_type' => 'тип пользователя',
        'user_type_id' => 'тип пользователя',
        'country' => 'страна',
        'country_code' => 'страна',
        'phone' => 'телефон',
        'address' => 'адрес',
        'mobile' => 'мобильный номер',
        'sex' => 'пол',
        'year' => 'год',
        'month' => 'месяц',
        'day' => 'день',
        'hour' => 'час',
        'minute' => 'минута',
        'second' => 'секунда',
        'username' => 'имя пользователя',
        'email' => 'адрес электронной почты',
        'password' => 'парооль',
        'password_confirmation' => 'повтрорите пароль',
        'g-recaptcha-response' => 'captcha',
        'term' => 'сроки',
        'category' => 'категория',
        'category_id' => 'категория',
        'post_type' => 'тип объявления',
        'post_type_id' => 'тип объявления',
        'title' => 'заголовок',
        'body' => 'описание',
        'description' => 'описание',
        'excerpt' => 'отрывок',
        'date' => 'дата',
        'time' => 'время',
        'available' => 'доступно',
        'size' => 'размер',
        'price' => 'цена',
        'salary' => 'стоимость',
        'contact_name' => 'имя',
        'location' => 'месторасположение',
        'admin_code' => 'месторасположение',
        'city' => 'город',
        'city_id' => 'город',
        'package' => 'пакет',
        'package_id' => 'пакет',
        'payment_method' => 'Способ оплаты',
        'payment_method_id' => 'Способ оплаты',
        'sender_name' => 'имя',
        'subject' => 'тема',
        'message' => 'сообщение',
        'report_type' => 'тип отчета',
        'report_type_id' => 'тип отчета',
        'file' => 'файл',
        'filename' => 'имя файла',
        'picture' => 'фото',
        'resume' => 'резюме',
        'login' => 'войти',
        'code' => 'код',
        'token' => 'знак',
        'comment' => 'коментарий',
        'rating' => 'рейтинг',
    ],
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'not_regex' => 'The :attribute format is invalid.',
    'language_check_locale' => 'The :attribute field is not valid.',
    'country_check_locale' => 'The :attribute field is not valid.',
    'check_currencies' => 'The :attribute field is not valid.',
    'attributes.locale' => 'locale',
    'attributes.currencies' => 'currencies',
];
