<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrganisationUserImport implements WithHeadingRow
{
    public function headingRow(): int
    {
        return 1;
    }
}
