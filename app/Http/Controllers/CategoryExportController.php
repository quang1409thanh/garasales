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
        $product_array[] = [
            'Product Name', 'Slug', 'Code', 'Quantity', 'Buying Price',
            'Selling Price', 'Notes', 'Product Image', 'Thumbnail URL',
            'Category ID', 'Unit ID', 'Created At', 'Updated At', 'User ID',
            'UUID', 'Supplier ID', 'Product Sold', 'Fee'
        ];

        // Lặp qua các sản phẩm để thêm dữ liệu vào mảng
        foreach ($products as $product) {
            $product_array[] = [
                $product->name,
                $product->slug,
                $product->code,
                $product->quantity,
                $product->buying_price,
                $product->selling_price,
                $product->notes,
                $product->product_image,
                $product->thumbnail_url,
                $product->category_id,
                $product->unit_id,
                $product->created_at,
                $product->updated_at,
                $product->user_id,
                $product->uuid,
                $product->supplier_id,
                $product->product_sold,
                $product->fee,
            ];
        }

        // Gọi hàm để xuất file Excel
        return $this->exportToExcel($category->name, $product_array);
    }

    private function exportToExcel($categoryName, $product_array)
    {
        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->fromArray($product_array, null, 'A1');
            $spreadSheet->getActiveSheet()->setTitle($categoryName);

            $writer = new Xls($spreadSheet);

            // Thiết lập header cho file Excel
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $categoryName . '_products.xls"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit();
        } catch (\Exception $e) {
            Log::error('Error exporting products: ' . $e->getMessage());
            return back()->with('error', 'Error occurred while exporting products.');
        }
    }
}
