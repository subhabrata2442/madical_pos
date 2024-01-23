<?php

namespace App\Imports;

use App\Models\PurchaseProduct;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Imports\HeadingRowFormatter;

use Maatwebsite\Excel\Concerns\FromCollection;

//HeadingRowFormatter::default('none');

// class PurchaseProductImport implements FromCollection
// {
//     /**
//     * @param array $row
//     *
//     * @return \Illuminate\Database\Eloquent\Model|null
//     */
//     public function model(array $row)
//     {
//         // return new PurchaseProduct([
//         //     'barcode'=> $row[0],
//         // ]);

//         return PurchaseProduct::all();
//     }
// }

class PurchaseProductImport implements FromCollection
{
    public function collection()
    {
        return SalesOrder::all();
    }
}
