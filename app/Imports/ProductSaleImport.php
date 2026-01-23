<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductSale;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProductSaleImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // 1. Lấy sản phẩm theo tên
            $productName = $row['product_name'] ?? null; // Excel cột: product_name
            if (!$productName) {
                continue; // bỏ qua nếu không có tên sản phẩm
            }

            $product = Product::where('name', $productName)->first();

            if (!$product) {
                // Log hoặc bỏ qua nếu không tìm thấy sản phẩm
                \Log::warning("Không tìm thấy sản phẩm: " . $productName);
                continue;
            }

            // 2. Chuyển đổi ngày tháng
            $dateBegin = $this->transformDate($row['date_begin']);
            $dateEnd   = $this->transformDate($row['date_end']);

            // 3. Tạo khuyến mãi
            ProductSale::create([
                'name'       => $row['name'] ?? 'Khuyến mãi',
                'product_id' => $product->id,
                'price_sale' => $row['price_sale'] ?? 0,
                'date_begin' => $dateBegin,
                'date_end'   => $dateEnd,
                'status'     => $row['status'] ?? 1,
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }
    }

    /**
     * Chuyển đổi serial number hoặc string thành định dạng ngày hợp lệ
     */
    private function transformDate($value)
    {
        try {
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            } else {
                return date('Y-m-d', strtotime($value));
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
