<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class CategoryExportController extends Controller
{
    public function exportByCategory(Category $category)
    {
        // Lấy các sản phẩm theo danh mục
        $products = $category->products;

        // Tạo mảng để chứa dữ liệu sản phẩm
        $product_array = [
            ['Product Name', 'Code', 'Quantity', 'Selling Price'], // Dòng tiêu đề
        ];

        // Lặp qua các sản phẩm để thêm dữ liệu vào mảng
        foreach ($products as $product) {
            $product_array[] = [
                $product->name,
                $product->code,
                $product->quantity,
                $product->selling_price,
            ];
        }

        // Gọi hàm để xuất file Excel
        return $this->exportToExcel($category->name, $product_array);
    }

    private function exportToExcel($categoryName, $product_array)
    {
        try {
            // Tạo đối tượng Spreadsheet
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->fromArray($product_array, null, 'A1');
            $spreadSheet->getActiveSheet()->setTitle($categoryName);

            // Khởi tạo writer cho tệp Excel
            $writer = new Xls($spreadSheet);

            // Kiểm tra và thiết lập tên tệp
            $filename = !empty($categoryName) ? $categoryName . '_products.xls' : 'export.xls';

            // Thiết lập header cho file Excel
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            // Xuất tệp
            $writer->save('php://output');
            exit(); // Ngừng thực thi script sau khi xuất tệp
        } catch (\Exception $e) {
            // Ghi log lỗi
            Log::error('Error exporting products: ' . $e->getMessage());
            return back()->with('error', 'Error occurred while exporting products.');
        }
    }
}
