<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Helper\Media;
use App\Models\Company;
use App\Models\Dosage;
use Illuminate\Support\Str;

class ProductsImport implements ToCollection
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        //dd($rows);
        foreach ($rows as $key => $column) {
            if ($key != 0) {
                if ($column[0] != '' && $column[1] != '') {
                    $check_product_barcode = Product::where('product_barcode', $column[1])->get();
                    if (count($check_product_barcode) > 0) {
                        //product barcode already exist 
                    } else {
                        //New product insert
                        $n = Product::count();
                        $uqc_id = str_pad($n + 1, 5, 0, STR_PAD_LEFT);
                        //Start brand section.In this section find or add new brand.
                        $brand_slug    = Media::create_slug(trim($column[0]));
                        $brand_result = Brand::where('name', trim($column[0]))->first();
                        $brand_name = "";
                        $brand_id = "";
                        if ($brand_result) {
                            $brand_name = $brand_result->name;
                            $brand_id = $brand_result->id;
                        } else {
                            //Create new brand
                            $new_brand = Brand::create([
                                'name'          => trim($column[0]),
                                'slug'            => $brand_slug,
                                'created_at'    => date('Y-m-d')
                            ]);
                            $brand_name = $new_brand->name;
                            $brand_id = $new_brand->id;
                        }
                        //End brand section.
                        //Start Dosage section.In this section find or add new brand
                        $dosage_name = '';
                        $dosage_id = '';
                        if ($column[9] != '') {
                            $dosage_result = Dosage::where('name', trim($column[9]))->first();
                            if ($dosage_result) {
                                $dosage_name = $dosage_result->name;
                                $dosage_id = $dosage_result->id;
                            } else {
                                //$dosage_slug	= Media::create_slug(trim($column[9]));
                                $dosage_slug = Str::slug(trim($column[9]));
                                $dosage = Dosage::create([
                                    'name'  => trim($column[9]),
                                    'slug'            => $dosage_slug,
                                    'created_at'    => date('Y-m-d')
                                ]);
                                $dosage_name = $dosage->name;
                                $dosage_id = $dosage->id;
                            }
                        }
                        //End Dosage section
                        //Start company section.In this section find or add new company.
                        $company_name = '';
                        $company_id = '';
                        if ($column[10] != '') {
                            $company_result = Company::where('name', trim($column[10]))->first();
                            if ($company_result) {
                                $company_name = $company_result->name;
                                $company_id = $company_result->id;
                            } else {
                                $company_slug    = Str::slug(trim($column[10]));
                                $new_company = Company::create([
                                    'name'  => trim($column[10]),
                                    'slug'            => $company_slug,
                                    'created_at'    => date('Y-m-d')
                                ]);
                                $company_name = $new_company->name;
                                $company_id = $new_company->id;
                            }
                        }

                        //End company section.
                        //Insert product
                        Product::create([
                            'uqc_id'                  => $uqc_id,
                            'product_barcode'          => $column[1],
                            'brand'                  => $brand_name,
                            // 'product_name'  		=> $product_name,
                            'brand_id'              => $brand_id,
                            // 'slug'  				=> $product_slug,
                            'is_chronic'              => $column[5] ? $column[5] : 'no',
                            'dosage_name'              => $dosage_name,
                            'dosage_id'              => $dosage_id,
                            'company_name'          => $company_name,
                            'company_id'              => $company_id,
                            //'default_qty'            => $default_qty,
                            'days_before_product_expiry' => $column[4],
                            'alert_product_qty'        => $column[2],
                            'no_package'            => $column[7],
                            'selling_by'            => $column[7] == 'Pack' ? 1 : 2,
                            'selling_by_name'        => $column[7],
                            'common_items'        => $column[7],
                        ]);
                    }
                }
            }
        }
    }
}
