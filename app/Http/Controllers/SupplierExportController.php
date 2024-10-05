<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Exception;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class SupplierExportController extends Controller
{
    public function create()
    {
        // Lấy tất cả nhà cung cấp và sắp xếp theo tên
        $suppliers = Supplier::all()->sortBy('name');

        // Tạo mảng để lưu trữ dữ liệu xuất
        $supplier_array[] = array(
            'Name',
            'Phone',
            'Dorm Room', // Trường mới
            'Total Selling Price', // Trường mới
            'Total Buying Price',  // Trường mới
            'Profit', // Trường mới
        );

        // Khởi tạo tổng giá trị
        $totalSellingPrice = 0;
        $totalBuyingPrice = 0;

        // Lặp qua từng nhà cung cấp và thêm vào mảng
        foreach ($suppliers as $supplier) {
            $sellingPrice = 0;
            $buyingPrice = 0;

            $products = $supplier->products()->where('product_sold', '>', 0)->get();

            // Tính tổng giá bán và giá trả lại cho mỗi nhà cung cấp
            foreach ($products as $product) {
                $sellingPrice += $product->product_sold * $product->selling_price;
                $buyingPrice += $product->product_sold * $product->buying_price;
            }

            // Tổng cộng cho các nhà cung cấp
            $totalSellingPrice += $sellingPrice;
            $totalBuyingPrice += $buyingPrice;

            // Tính lợi nhuận
            $profit = $sellingPrice - $buyingPrice;

            // Thêm thông tin vào mảng
            $supplier_array[] = array(
                'Name' => $supplier->name,
                'Phone' => $supplier->phone,
                'Dorm Room' => $supplier->shopname ?? '--',
                'Total Selling Price' => number_format($sellingPrice, 2),
                'Total Buying Price' => number_format($buyingPrice, 2),
                'Profit' => number_format($profit, 2),
            );
        }

        // Thêm tổng giá trị cuối cùng
        $supplier_array[] = array(
            'Total', '', '', '',
            number_format($totalSellingPrice, 2),
            number_format($totalBuyingPrice, 2),
            number_format($totalSellingPrice - $totalBuyingPrice, 2)
        );

        // Gọi hàm để lưu và xuất file
        $this->store($supplier_array);
    }

    public function store($suppliers)
    {
        // Bắt đầu output buffer
        ob_start();

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        Log::info('Starting the export process for suppliers.');

        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($suppliers);
            $Excel_writer = new Xls($spreadSheet);

            // Log thông tin số lượng nhà cung cấp
            Log::info('Number of suppliers being exported: ' . count($suppliers));

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="suppliers.xls"');
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
