<?php

namespace App\Exports;

use App\Models\Utilities\TempImage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Storage;
use URL;

class TempImageExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return TempImage::all();
    }

    public function headings(): array
    {
        return [
            'Product Code',
            'Main Image',
        ];
    }

    public function map($tempimage): array
    {
        return [
            $tempimage->name,
            URL::to($tempimage->path)
        ];
    }
}
