<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Exception;
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
        );

        // Lặp qua từng nhà cung cấp và thêm vào mảng
        foreach ($suppliers as $supplier) {
            $supplier_array[] = array(
                'Name' => $supplier->name,
                'Phone' => $supplier->phone,
            );
        }

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
