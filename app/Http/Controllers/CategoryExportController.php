<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class CategoryExportController extends Controller
{
    public function exportInvoices()
    {
        // Lấy tất cả các hóa đơn từ cơ sở dữ liệu
        $invoices = Order::all();

        // Tạo mảng để chứa dữ liệu hóa đơn
        $invoice_array = [
            ['Invoice No', 'Customer ID', 'Order Date', 'Order Status', 'Total Products', 'Sub Total', 'VAT', 'Total', 'Payment Type', 'Pay', 'Due', 'User ID', 'UUID', 'Payment Image URL'], // Dòng tiêu đề
        ];

        // Lặp qua các hóa đơn để thêm dữ liệu vào mảng
        foreach ($invoices as $invoice) {
            $invoice_array[] = [
                $invoice->invoice_no,
                $invoice->customer_id,
                $invoice->order_date,
                $invoice->order_status instanceof \App\Enums\OrderStatus ? $invoice->order_status->value : $invoice->order_status, // Chuyển đổi enum thành chuỗi
                $invoice->total_products !== null ? (string)$invoice->total_products : '0', // Hiển thị "0" nếu giá trị là null
                $invoice->sub_total !== null ? (string)$invoice->sub_total : '0', // Hiển thị "0" nếu giá trị là null
                $invoice->vat !== null ? (string)$invoice->vat : '0', // Hiển thị "0" nếu giá trị là null
                $invoice->total !== null ? (string)$invoice->total : '0', // Hiển thị "0" nếu giá trị là null
                $invoice->payment_type ?? 'N/A', // Hiển thị giá trị mặc định nếu giá trị là null
                $invoice->pay !== null ? (string)$invoice->pay : '0', // Hiển thị "0" nếu giá trị là null
                $invoice->due !== null ? (string)$invoice->due : '0', // Hiển thị "0" nếu giá trị là null
                $invoice->user_id !== null ? (string)$invoice->user_id : '0', // Hiển thị "0" nếu giá trị là null
                $invoice->uuid ?? 'N/A', // Hiển thị giá trị mặc định nếu giá trị là null
                $invoice->payment_image_url ?? 'N/A', // Hiển thị giá trị mặc định nếu giá trị là null
            ];
        }

        return $this->exportToExcel('invoices', $invoice_array);
    }

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
                $product->quantity !== null ? (string)$product->quantity : '0', // Chuyển đổi giá trị 0 thành chuỗi "0"
                $product->selling_price !== null ? (string)$product->selling_price : '0', // Chuyển đổi giá trị 0 thành chuỗi "0"
            ];
        }

        // Gọi hàm để xuất file Excel
        return $this->exportToExcel($category->slug, $product_array);
    }

    private function exportToExcel($categoryName, $product_array)
    {
        // Bắt đầu output buffer
        ob_start();

        // Thiết lập thời gian và bộ nhớ tối đa
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        Log::info('Starting the export process for category: ' . $categoryName);

        try {
            // Tạo đối tượng Spreadsheet
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->fromArray($product_array, null, 'A1');
            $spreadSheet->getActiveSheet()->setTitle($categoryName);

            // Khởi tạo writer cho tệp Excel
            $writer = new Xls($spreadSheet);


            // Kiểm tra và thiết lập tên tệp
            $filename = !empty($categoryName) ? $categoryName . '-products.xls' : 'export.xls';

            // Thiết lập header cho file Excel
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            // Xóa output buffer trước khi xuất file
            ob_end_clean();

            // Xuất tệp
            $writer->save('php://output');

            Log::info('Export process completed successfully for category: ' . $categoryName);
            exit(); // Ngừng thực thi script sau khi xuất tệp
        } catch (\Exception $e) {
            // Ghi log lỗi
            Log::error('Error exporting products: ' . $e->getMessage());
            ob_end_clean(); // Đảm bảo buffer được xóa khi có lỗi
            return response()->json(['error' => 'Error occurred while exporting products.'], 500);
        }
    }

}
