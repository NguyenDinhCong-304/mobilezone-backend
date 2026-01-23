<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Product;

class ProductSaleTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Lấy 1 vài sản phẩm để gợi ý trong file mẫu
        $products = Product::take(5)->get();

        $sample = [];
        foreach ($products as $p) {
            $sample[] = [
                'name' => 'Khuyến mãi cho ' . $p->name,
                'product_name' => $p->name,  // dùng tên sản phẩm thay vì ID
                'price_sale' => '1000000',
                'date_begin' => date('Y-m-d'),
                'date_end' => date('Y-m-d', strtotime('+10 days')),
                'status' => '1',
            ];
        }

        return collect($sample);
    }

    public function headings(): array
    {
        return [
            'name',          // Tên khuyến mãi
            'product_name',  // Tên sản phẩm (dùng để lookup product_id khi import)
            'price_sale',    // Giá khuyến mãi
            'date_begin',    // Ngày bắt đầu (YYYY-MM-DD)
            'date_end',      // Ngày kết thúc (YYYY-MM-DD)
            'status',        // 1: hoạt động, 0: ngừng
        ];
    }
}
