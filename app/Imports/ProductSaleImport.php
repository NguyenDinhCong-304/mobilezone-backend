<?php

namespace App\Imports;

use App\Models\ProductSale;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date; // 👈 thêm dòng này

class ProductSaleImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Xử lý ngày tháng Excel -> DateTime
            $dateBegin = $this->transformDate($row['date_begin']);
            $dateEnd   = $this->transformDate($row['date_end']);

            ProductSale::create([
                'name'       => $row['name'],
                'product_id' => $row['product_id'],
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
                // Excel serial -> DateTime
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            } else {
                // Chuỗi ngày bình thường
                return date('Y-m-d', strtotime($value));
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
