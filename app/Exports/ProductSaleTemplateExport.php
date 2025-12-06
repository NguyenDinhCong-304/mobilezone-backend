<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductSaleTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Dữ liệu mẫu gợi ý cho người dùng
        return collect([
            [
                'name' => 'Khuyến mãi tháng 10',
                'product_id' => '1',  // ID sản phẩm
                'price_sale' => '1000000',
                'date_begin' => '2025-10-10',
                'date_end' => '2025-10-31',
                'status' => '1',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'name',        // Tên khuyến mãi
            'product_id',  // ID sản phẩm
            'price_sale',  // Giá khuyến mãi
            'date_begin',  // Ngày bắt đầu (YYYY-MM-DD)
            'date_end',    // Ngày kết thúc (YYYY-MM-DD)
            'status',      // 1: hoạt động, 0: ngừng
        ];
    }
}
