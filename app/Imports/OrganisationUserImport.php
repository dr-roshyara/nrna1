<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Import;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrganisationUserImport implements Import, WithHeadingRow
{
    public function headingRow(): int
    {
        return 1;
    }
}
