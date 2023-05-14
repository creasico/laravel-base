<?php

namespace Creasi\Laravel\Models\Account\Field;

enum Cast: string
{
    case Email = 'email';
    case Phone = 'phone';
    case Address = 'address';
    case Photo = 'Photo';

    case Text = 'text';
    case TextArea = 'textarea';
    case Select = 'select';
    case MultiSelect = 'multiselect';
    case CheckBox = 'checkbox';
    case RadioBox = 'radiobox';
    case Switch = 'switch';
}
