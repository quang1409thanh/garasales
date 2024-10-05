<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProductExportController extends Controller
{
    public function create()
    {
        // Lấy tất cả sản phẩm và sắp xếp theo tên sản phẩm
        $products = Product::all()->sortBy('product_name');

        // Tạo mảng để lưu trữ dữ liệu xuất
        $product_array[] = array(
            'Product Name',
            'Product Slug',
            'Category Id',
            'Unit Id',
            'Product Code',
            'Stock',
            // 'Stock Alert',
            'Buying Price',
            'Selling Price',
            'Product Image',
            'Note'
        );

        // Lặp qua từng sản phẩm và thêm vào mảng
        foreach ($products as $product) {
            $product_array[] = array(
                'Product Name' => $product->name,
                'Product Slug' => $product->slug,
                'Category Id' => $product->category_id,
                'Unit Id' => $product->unit_id,
                'Product Code' => $product->code,
                'Stock' => (string)$product->quantity, // Chuyển đổi thành chuỗi
                // 'Stock Alert' => (string)$product->quantity_alert,
                'Buying Price' => (string)number_format($product->buying_price, 2), // Định dạng số
                'Selling Price' => (string)number_format($product->selling_price, 2), // Định dạng số
                'Product Image' => $product->product_image,
                'Note' => $product->note
            );
        }

        // Gọi hàm để lưu và xuất file
        $this->store($product_array);
    }

    public function store($products)
    {
        // Bắt đầu output buffer
        ob_start();

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        Log::info('Starting the export process for products.');

        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($products);

            // Đặt định dạng cho tất cả các ô trong bảng
            $spreadSheet->getActiveSheet()->getStyle('A1:J' . count($products))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

            $Excel_writer = new Xls($spreadSheet);

            // Log thông tin số lượng sản phẩm
            Log::info('Number of products being exported: ' . count($products));

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="products.xls"');
            header('Cache-Control: max-age=0');

            // Xóa output buffer trước khi xuất file
            ob_end_clean();
            $Excel_writer->save('php://output');
            Log::info('Export process completed successfully.');
            exit();
        } catch (Exception $e) {
            // Ghi log lỗi
            Log::error('Error occurred during the export process: ' . $e->getMessage());
            ob_end_clean(); // Đảm bảo buffer được xóa khi có lỗi
            return;
        }
    }
}
