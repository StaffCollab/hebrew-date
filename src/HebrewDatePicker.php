<?php

namespace StaffCollab\HebrewDate;

use Filament\Forms\Components\Field;

class HebrewDatePicker extends Field
{
    public $name = 'hebrew-date-picker';

    public $format = 'YYYY-MM-DD';

    public $type = 'text';

    public $view = 'staffcollab::hebrew-date-picker';

    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    public function type($type)
    {
        $this->type = $type;

        return $this;
    }
}
