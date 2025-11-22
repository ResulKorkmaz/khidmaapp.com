<?php

/**
 * Lead Export Service
 * Arapça formatında lead export işlemleri
 */

require_once __DIR__ . '/../config/config.php';

class LeadExportService
{
    /**
     * Tarihi Arapça formata çevir
     */
    private static function formatDateArabic($date)
    {
        $timestamp = strtotime($date);
        $day = date('d', $timestamp);
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);
        $hour = date('H', $timestamp);
        $minute = date('i', $timestamp);
        
        // Arapça ay isimleri
        $arabicMonths = [
            '01' => 'يناير',
            '02' => 'فبراير', 
            '03' => 'مارس',
            '04' => 'أبريل',
            '05' => 'مايو',
            '06' => 'يونيو',
            '07' => 'يوليو',
            '08' => 'أغسطس',
            '09' => 'سبتمبر',
            '10' => 'أكتوبر',
            '11' => 'نوفمبر',
            '12' => 'ديسمبر'
        ];
        
        return $day . ' ' . $arabicMonths[$month] . ' ' . $year . ' - ' . $hour . ':' . $minute;
    }
    
    /**
     * Sayıları Arapça rakam formatına çevir (opsiyonel)
     */
    private static function formatNumberArabic($number)
    {
        // Latin rakamları Arapça'ya çevir (0-9 → ٠-٩)
        $arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $westernNumerals = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($westernNumerals, $arabicNumerals, $number);
    }

    /**
     * Export leads as PDF (Arabic format)
     */
    public static function exportPDF($leads)
    {
        require_once __DIR__ . '/../../vendor/autoload.php';
        
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 11,
            'default_font' => 'dejavusans',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'margin_header' => 10,
            'margin_footer' => 10,
            'orientation' => 'L' // Landscape for better table view
        ]);
        
        $mpdf->SetDirectionality('rtl');
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        
        $html = self::generatePDFHTML($leads);
        
        $mpdf->WriteHTML($html);
        
        $filename = 'تقرير_الطلبات_' . date('Y-m-d_H-i-s') . '.pdf';
        $mpdf->Output($filename, 'D'); // D = Download
    }
    
    /**
     * Generate PDF HTML content (Arabic)
     */
    private static function generatePDFHTML($leads)
    {
        $serviceTypes = getServiceTypes();
        $cities = getCities();
        
        $html = '
        <style>
            body { font-family: dejavusans; direction: rtl; }
            h1 { color: #2563eb; text-align: center; margin-bottom: 20px; border-bottom: 3px solid #2563eb; padding-bottom: 10px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #2563eb; color: white; padding: 10px; text-align: right; font-weight: bold; }
            td { border: 1px solid #ddd; padding: 8px; text-align: right; }
            tr:nth-child(even) { background-color: #f9fafb; }
            .urgent { color: #dc2626; font-weight: bold; }
            .within-24h { color: #2563eb; font-weight: bold; }
            .scheduled { color: #16a34a; font-weight: bold; }
            .header-info { text-align: center; margin-bottom: 20px; color: #6b7280; font-size: 14px; }
            .footer { text-align: center; margin-top: 30px; color: #9ca3af; font-size: 12px; border-top: 2px solid #e5e7eb; padding-top: 10px; }
        </style>
        
        <h1>📋 تقرير الطلبات - خدمة</h1>
        <div class="header-info">
            تاريخ التقرير: ' . self::formatDateArabic(date('Y-m-d H:i:s')) . ' | عدد الطلبات: ' . count($leads) . '
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>الخدمة</th>
                    <th>المدينة</th>
                    <th>الهاتف</th>
                    <th>واتساب</th>
                    <th>وقت الخدمة</th>
                    <th>الوصف</th>
                    <th>الحالة</th>
                    <th>تاريخ الطلب</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($leads as $lead) {
            // Get Arabic names - direct key access
            $serviceNameAr = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
            $cityNameAr = $cities[$lead['city']]['ar'] ?? $lead['city'];
            
            // Service Time in Arabic
            $serviceTimeAr = '-';
            if ($lead['service_time_type'] === 'urgent') {
                $serviceTimeAr = '<span class="urgent">⚡ عاجل - في أقرب وقت</span>';
            } elseif ($lead['service_time_type'] === 'within_24h') {
                $serviceTimeAr = '<span class="within-24h">⏰ خلال 24 ساعة</span>';
            } elseif ($lead['service_time_type'] === 'scheduled' && !empty($lead['scheduled_date'])) {
                $dateAr = self::formatDateArabic($lead['scheduled_date']);
                $serviceTimeAr = '<span class="scheduled">📅 ' . $dateAr . '</span>';
            }
            
            // Status in Arabic
            $statusMap = [
                'new' => '🆕 جديد',
                'verified' => '✅ موثق',
                'pending' => '⏰ معلق',
                'sold' => '💰 مباع',
                'invalid' => '❌ غير صالح'
            ];
            $statusAr = $statusMap[$lead['status']] ?? $lead['status'];
            
            // Format date in Arabic
            $createdAtAr = self::formatDateArabic($lead['created_at']);
            
            $html .= '
                <tr>
                    <td>' . htmlspecialchars($serviceNameAr) . '</td>
                    <td>' . htmlspecialchars($cityNameAr) . '</td>
                    <td>' . htmlspecialchars($lead['phone']) . '</td>
                    <td>' . htmlspecialchars($lead['whatsapp_phone'] ?? $lead['phone']) . '</td>
                    <td>' . $serviceTimeAr . '</td>
                    <td>' . htmlspecialchars(mb_substr($lead['description'] ?? 'لا يوجد وصف', 0, 150)) . '</td>
                    <td>' . $statusAr . '</td>
                    <td>' . $createdAtAr . '</td>
                </tr>';
        }
        
        $html .= '
            </tbody>
        </table>
        
        <div class="footer">
            KhidmaApp - منصة خدماتية متخصصة في المملكة العربية السعودية
        </div>';
        
        return $html;
    }
    
    /**
     * Export leads as Excel (XLSX)
     */
    public static function exportExcel($leads)
    {
        require_once __DIR__ . '/../../vendor/autoload.php';
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setRightToLeft(true);
        
        $serviceTypes = getServiceTypes();
        $cities = getCities();
        
        // Headers (Arabic)
        $headers = ['الخدمة', 'المدينة', 'الهاتف', 'واتساب', 'وقت الخدمة', 'التاريخ المحدد', 'الوصف', 'الحالة', 'تاريخ الطلب'];
        $sheet->fromArray($headers, null, 'A1');
        
        // Style headers
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);
        
        // Data
        $row = 2;
        foreach ($leads as $lead) {
            // Get Arabic names - direct key access
            $serviceNameAr = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
            $cityNameAr = $cities[$lead['city']]['ar'] ?? $lead['city'];
            
            // Service Time in Arabic
            $serviceTimeAr = '-';
            $scheduledDateStr = '-';
            if ($lead['service_time_type'] === 'urgent') {
                $serviceTimeAr = '⚡ عاجل - في أقرب وقت';
            } elseif ($lead['service_time_type'] === 'within_24h') {
                $serviceTimeAr = '⏰ خلال 24 ساعة';
            } elseif ($lead['service_time_type'] === 'scheduled' && !empty($lead['scheduled_date'])) {
                $serviceTimeAr = '📅 تاريخ محدد';
                $scheduledDateStr = self::formatDateArabic($lead['scheduled_date']);
            }
            
            // Status in Arabic
            $statusMap = [
                'new' => '🆕 جديد',
                'verified' => '✅ موثق',
                'pending' => '⏰ معلق',
                'sold' => '💰 مباع',
                'invalid' => '❌ غير صالح'
            ];
            $statusAr = $statusMap[$lead['status']] ?? $lead['status'];
            
            // Format date in Arabic
            $createdAtAr = self::formatDateArabic($lead['created_at']);
            
            $sheet->fromArray([
                $serviceNameAr,
                $cityNameAr,
                $lead['phone'],
                $lead['whatsapp_phone'] ?? $lead['phone'],
                $serviceTimeAr,
                $scheduledDateStr,
                $lead['description'] ?? 'لا يوجد وصف',
                $statusAr,
                $createdAtAr
            ], null, 'A' . $row);
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $filename = 'تقرير_الطلبات_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Export leads as CSV
     */
    public static function exportCSV($leads)
    {
        $serviceTypes = getServiceTypes();
        $cities = getCities();
        
        $filename = 'تقرير_الطلبات_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // BOM for UTF-8
        echo "\xEF\xBB\xBF";
        
        $output = fopen('php://output', 'w');
        
        // Headers (Arabic)
        fputcsv($output, ['الخدمة', 'المدينة', 'الهاتف', 'واتساب', 'وقت الخدمة', 'التاريخ المحدد', 'الوصف', 'الحالة', 'تاريخ الطلب']);
        
        // Data
        foreach ($leads as $lead) {
            // Get Arabic names - direct key access
            $serviceNameAr = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
            $cityNameAr = $cities[$lead['city']]['ar'] ?? $lead['city'];
            
            // Service Time in Arabic
            $serviceTimeAr = '-';
            $scheduledDateStr = '-';
            if ($lead['service_time_type'] === 'urgent') {
                $serviceTimeAr = 'عاجل - في أقرب وقت';
            } elseif ($lead['service_time_type'] === 'within_24h') {
                $serviceTimeAr = 'خلال 24 ساعة';
            } elseif ($lead['service_time_type'] === 'scheduled' && !empty($lead['scheduled_date'])) {
                $serviceTimeAr = 'تاريخ محدد';
                $scheduledDateStr = self::formatDateArabic($lead['scheduled_date']);
            }
            
            // Status in Arabic
            $statusMap = [
                'new' => 'جديد',
                'verified' => 'موثق',
                'pending' => 'معلق',
                'sold' => 'مباع',
                'invalid' => 'غير صالح'
            ];
            $statusAr = $statusMap[$lead['status']] ?? $lead['status'];
            
            // Format date in Arabic
            $createdAtAr = self::formatDateArabic($lead['created_at']);
            
            fputcsv($output, [
                $serviceNameAr,
                $cityNameAr,
                $lead['phone'],
                $lead['whatsapp_phone'] ?? $lead['phone'],
                $serviceTimeAr,
                $scheduledDateStr,
                $lead['description'] ?? 'لا يوجد وصف',
                $statusAr,
                $createdAtAr
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Export leads as Word (DOCX)
     */
    public static function exportDOCX($leads)
    {
        require_once __DIR__ . '/../../vendor/autoload.php';
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);
        
        $section = $phpWord->addSection([
            'marginLeft' => 1000,
            'marginRight' => 1000,
            'marginTop' => 1000,
            'marginBottom' => 1000,
            'orientation' => 'landscape'
        ]);
        
        $section->addText('تقرير الطلبات - خدمة', [
            'bold' => true,
            'size' => 18,
            'color' => '2563EB',
            'rtl' => true
        ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $section->addText('تاريخ التقرير: ' . self::formatDateArabic(date('Y-m-d H:i:s')) . ' | عدد الطلبات: ' . count($leads), [
            'size' => 10,
            'color' => '6B7280',
            'rtl' => true
        ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 300]);
        
        $serviceTypes = getServiceTypes();
        $cities = getCities();
        
        // Table
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'width' => 100 * 50
        ]);
        
        // Header row
        $table->addRow(500);
        $headerStyle = ['bold' => true, 'color' => 'FFFFFF', 'rtl' => true];
        $headerCellStyle = ['bgColor' => '2563EB', 'valign' => 'center'];
        
        $table->addCell(2500, $headerCellStyle)->addText('الخدمة', $headerStyle);
        $table->addCell(2000, $headerCellStyle)->addText('المدينة', $headerStyle);
        $table->addCell(2000, $headerCellStyle)->addText('الهاتف', $headerStyle);
        $table->addCell(2000, $headerCellStyle)->addText('وقت الخدمة', $headerStyle);
        $table->addCell(3500, $headerCellStyle)->addText('الوصف', $headerStyle);
        $table->addCell(1500, $headerCellStyle)->addText('الحالة', $headerStyle);
        $table->addCell(2000, $headerCellStyle)->addText('تاريخ الطلب', $headerStyle);
        
        // Data rows
        foreach ($leads as $lead) {
            // Get Arabic names - direct key access
            $serviceNameAr = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
            $cityNameAr = $cities[$lead['city']]['ar'] ?? $lead['city'];
            
            // Service Time in Arabic
            $serviceTimeAr = '-';
            if ($lead['service_time_type'] === 'urgent') {
                $serviceTimeAr = '⚡ عاجل - في أقرب وقت';
            } elseif ($lead['service_time_type'] === 'within_24h') {
                $serviceTimeAr = '⏰ خلال 24 ساعة';
            } elseif ($lead['service_time_type'] === 'scheduled' && !empty($lead['scheduled_date'])) {
                $serviceTimeAr = '📅 ' . self::formatDateArabic($lead['scheduled_date']);
            }
            
            // Status in Arabic
            $statusMap = [
                'new' => '🆕 جديد',
                'verified' => '✅ موثق',
                'pending' => '⏰ معلق',
                'sold' => '💰 مباع',
                'invalid' => '❌ غير صالح'
            ];
            $statusAr = $statusMap[$lead['status']] ?? $lead['status'];
            
            // Format date in Arabic
            $createdAtAr = self::formatDateArabic($lead['created_at']);
            
            $table->addRow();
            $cellStyle = ['valign' => 'center'];
            $textStyle = ['rtl' => true];
            
            $table->addCell(2500, $cellStyle)->addText($serviceNameAr, $textStyle);
            $table->addCell(2000, $cellStyle)->addText($cityNameAr, $textStyle);
            $table->addCell(2000, $cellStyle)->addText($lead['phone'], $textStyle);
            $table->addCell(2000, $cellStyle)->addText($serviceTimeAr, $textStyle);
            $table->addCell(3500, $cellStyle)->addText(mb_substr($lead['description'] ?? 'لا يوجد وصف', 0, 100), $textStyle);
            $table->addCell(1500, $cellStyle)->addText($statusAr, $textStyle);
            $table->addCell(2000, $cellStyle)->addText($createdAtAr, $textStyle);
        }
        
        $filename = 'تقرير_الطلبات_' . date('Y-m-d_H-i-s') . '.docx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save('php://output');
        exit;
    }
}


/**
 * Lead Export Service
 * Arapça formatında lead export işlemleri
 */

require_once __DIR__ . '/../config/config.php';

class LeadExportService
{
    /**
     * Tarihi Arapça formata çevir
     */
    private static function formatDateArabic($date)
    {
        $timestamp = strtotime($date);
        $day = date('d', $timestamp);
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);
        $hour = date('H', $timestamp);
        $minute = date('i', $timestamp);
        
        // Arapça ay isimleri
        $arabicMonths = [
            '01' => 'يناير',
            '02' => 'فبراير', 
            '03' => 'مارس',
            '04' => 'أبريل',
            '05' => 'مايو',
            '06' => 'يونيو',
            '07' => 'يوليو',
            '08' => 'أغسطس',
            '09' => 'سبتمبر',
            '10' => 'أكتوبر',
            '11' => 'نوفمبر',
            '12' => 'ديسمبر'
        ];
        
        return $day . ' ' . $arabicMonths[$month] . ' ' . $year . ' - ' . $hour . ':' . $minute;
    }
    
    /**
     * Sayıları Arapça rakam formatına çevir (opsiyonel)
     */
    private static function formatNumberArabic($number)
    {
        // Latin rakamları Arapça'ya çevir (0-9 → ٠-٩)
        $arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $westernNumerals = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($westernNumerals, $arabicNumerals, $number);
    }

    /**
     * Export leads as PDF (Arabic format)
     */
    public static function exportPDF($leads)
    {
        require_once __DIR__ . '/../../vendor/autoload.php';
        
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 11,
            'default_font' => 'dejavusans',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'margin_header' => 10,
            'margin_footer' => 10,
            'orientation' => 'L' // Landscape for better table view
        ]);
        
        $mpdf->SetDirectionality('rtl');
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        
        $html = self::generatePDFHTML($leads);
        
        $mpdf->WriteHTML($html);
        
        $filename = 'تقرير_الطلبات_' . date('Y-m-d_H-i-s') . '.pdf';
        $mpdf->Output($filename, 'D'); // D = Download
    }
    
    /**
     * Generate PDF HTML content (Arabic)
     */
    private static function generatePDFHTML($leads)
    {
        $serviceTypes = getServiceTypes();
        $cities = getCities();
        
        $html = '
        <style>
            body { font-family: dejavusans; direction: rtl; }
            h1 { color: #2563eb; text-align: center; margin-bottom: 20px; border-bottom: 3px solid #2563eb; padding-bottom: 10px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #2563eb; color: white; padding: 10px; text-align: right; font-weight: bold; }
            td { border: 1px solid #ddd; padding: 8px; text-align: right; }
            tr:nth-child(even) { background-color: #f9fafb; }
            .urgent { color: #dc2626; font-weight: bold; }
            .within-24h { color: #2563eb; font-weight: bold; }
            .scheduled { color: #16a34a; font-weight: bold; }
            .header-info { text-align: center; margin-bottom: 20px; color: #6b7280; font-size: 14px; }
            .footer { text-align: center; margin-top: 30px; color: #9ca3af; font-size: 12px; border-top: 2px solid #e5e7eb; padding-top: 10px; }
        </style>
        
        <h1>📋 تقرير الطلبات - خدمة</h1>
        <div class="header-info">
            تاريخ التقرير: ' . self::formatDateArabic(date('Y-m-d H:i:s')) . ' | عدد الطلبات: ' . count($leads) . '
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>الخدمة</th>
                    <th>المدينة</th>
                    <th>الهاتف</th>
                    <th>واتساب</th>
                    <th>وقت الخدمة</th>
                    <th>الوصف</th>
                    <th>الحالة</th>
                    <th>تاريخ الطلب</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($leads as $lead) {
            // Get Arabic names - direct key access
            $serviceNameAr = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
            $cityNameAr = $cities[$lead['city']]['ar'] ?? $lead['city'];
            
            // Service Time in Arabic
            $serviceTimeAr = '-';
            if ($lead['service_time_type'] === 'urgent') {
                $serviceTimeAr = '<span class="urgent">⚡ عاجل - في أقرب وقت</span>';
            } elseif ($lead['service_time_type'] === 'within_24h') {
                $serviceTimeAr = '<span class="within-24h">⏰ خلال 24 ساعة</span>';
            } elseif ($lead['service_time_type'] === 'scheduled' && !empty($lead['scheduled_date'])) {
                $dateAr = self::formatDateArabic($lead['scheduled_date']);
                $serviceTimeAr = '<span class="scheduled">📅 ' . $dateAr . '</span>';
            }
            
            // Status in Arabic
            $statusMap = [
                'new' => '🆕 جديد',
                'verified' => '✅ موثق',
                'pending' => '⏰ معلق',
                'sold' => '💰 مباع',
                'invalid' => '❌ غير صالح'
            ];
            $statusAr = $statusMap[$lead['status']] ?? $lead['status'];
            
            // Format date in Arabic
            $createdAtAr = self::formatDateArabic($lead['created_at']);
            
            $html .= '
                <tr>
                    <td>' . htmlspecialchars($serviceNameAr) . '</td>
                    <td>' . htmlspecialchars($cityNameAr) . '</td>
                    <td>' . htmlspecialchars($lead['phone']) . '</td>
                    <td>' . htmlspecialchars($lead['whatsapp_phone'] ?? $lead['phone']) . '</td>
                    <td>' . $serviceTimeAr . '</td>
                    <td>' . htmlspecialchars(mb_substr($lead['description'] ?? 'لا يوجد وصف', 0, 150)) . '</td>
                    <td>' . $statusAr . '</td>
                    <td>' . $createdAtAr . '</td>
                </tr>';
        }
        
        $html .= '
            </tbody>
        </table>
        
        <div class="footer">
            KhidmaApp - منصة خدماتية متخصصة في المملكة العربية السعودية
        </div>';
        
        return $html;
    }
    
    /**
     * Export leads as Excel (XLSX)
     */
    public static function exportExcel($leads)
    {
        require_once __DIR__ . '/../../vendor/autoload.php';
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setRightToLeft(true);
        
        $serviceTypes = getServiceTypes();
        $cities = getCities();
        
        // Headers (Arabic)
        $headers = ['الخدمة', 'المدينة', 'الهاتف', 'واتساب', 'وقت الخدمة', 'التاريخ المحدد', 'الوصف', 'الحالة', 'تاريخ الطلب'];
        $sheet->fromArray($headers, null, 'A1');
        
        // Style headers
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);
        
        // Data
        $row = 2;
        foreach ($leads as $lead) {
            // Get Arabic names - direct key access
            $serviceNameAr = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
            $cityNameAr = $cities[$lead['city']]['ar'] ?? $lead['city'];
            
            // Service Time in Arabic
            $serviceTimeAr = '-';
            $scheduledDateStr = '-';
            if ($lead['service_time_type'] === 'urgent') {
                $serviceTimeAr = '⚡ عاجل - في أقرب وقت';
            } elseif ($lead['service_time_type'] === 'within_24h') {
                $serviceTimeAr = '⏰ خلال 24 ساعة';
            } elseif ($lead['service_time_type'] === 'scheduled' && !empty($lead['scheduled_date'])) {
                $serviceTimeAr = '📅 تاريخ محدد';
                $scheduledDateStr = self::formatDateArabic($lead['scheduled_date']);
            }
            
            // Status in Arabic
            $statusMap = [
                'new' => '🆕 جديد',
                'verified' => '✅ موثق',
                'pending' => '⏰ معلق',
                'sold' => '💰 مباع',
                'invalid' => '❌ غير صالح'
            ];
            $statusAr = $statusMap[$lead['status']] ?? $lead['status'];
            
            // Format date in Arabic
            $createdAtAr = self::formatDateArabic($lead['created_at']);
            
            $sheet->fromArray([
                $serviceNameAr,
                $cityNameAr,
                $lead['phone'],
                $lead['whatsapp_phone'] ?? $lead['phone'],
                $serviceTimeAr,
                $scheduledDateStr,
                $lead['description'] ?? 'لا يوجد وصف',
                $statusAr,
                $createdAtAr
            ], null, 'A' . $row);
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $filename = 'تقرير_الطلبات_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Export leads as CSV
     */
    public static function exportCSV($leads)
    {
        $serviceTypes = getServiceTypes();
        $cities = getCities();
        
        $filename = 'تقرير_الطلبات_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // BOM for UTF-8
        echo "\xEF\xBB\xBF";
        
        $output = fopen('php://output', 'w');
        
        // Headers (Arabic)
        fputcsv($output, ['الخدمة', 'المدينة', 'الهاتف', 'واتساب', 'وقت الخدمة', 'التاريخ المحدد', 'الوصف', 'الحالة', 'تاريخ الطلب']);
        
        // Data
        foreach ($leads as $lead) {
            // Get Arabic names - direct key access
            $serviceNameAr = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
            $cityNameAr = $cities[$lead['city']]['ar'] ?? $lead['city'];
            
            // Service Time in Arabic
            $serviceTimeAr = '-';
            $scheduledDateStr = '-';
            if ($lead['service_time_type'] === 'urgent') {
                $serviceTimeAr = 'عاجل - في أقرب وقت';
            } elseif ($lead['service_time_type'] === 'within_24h') {
                $serviceTimeAr = 'خلال 24 ساعة';
            } elseif ($lead['service_time_type'] === 'scheduled' && !empty($lead['scheduled_date'])) {
                $serviceTimeAr = 'تاريخ محدد';
                $scheduledDateStr = self::formatDateArabic($lead['scheduled_date']);
            }
            
            // Status in Arabic
            $statusMap = [
                'new' => 'جديد',
                'verified' => 'موثق',
                'pending' => 'معلق',
                'sold' => 'مباع',
                'invalid' => 'غير صالح'
            ];
            $statusAr = $statusMap[$lead['status']] ?? $lead['status'];
            
            // Format date in Arabic
            $createdAtAr = self::formatDateArabic($lead['created_at']);
            
            fputcsv($output, [
                $serviceNameAr,
                $cityNameAr,
                $lead['phone'],
                $lead['whatsapp_phone'] ?? $lead['phone'],
                $serviceTimeAr,
                $scheduledDateStr,
                $lead['description'] ?? 'لا يوجد وصف',
                $statusAr,
                $createdAtAr
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Export leads as Word (DOCX)
     */
    public static function exportDOCX($leads)
    {
        require_once __DIR__ . '/../../vendor/autoload.php';
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);
        
        $section = $phpWord->addSection([
            'marginLeft' => 1000,
            'marginRight' => 1000,
            'marginTop' => 1000,
            'marginBottom' => 1000,
            'orientation' => 'landscape'
        ]);
        
        $section->addText('تقرير الطلبات - خدمة', [
            'bold' => true,
            'size' => 18,
            'color' => '2563EB',
            'rtl' => true
        ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $section->addText('تاريخ التقرير: ' . self::formatDateArabic(date('Y-m-d H:i:s')) . ' | عدد الطلبات: ' . count($leads), [
            'size' => 10,
            'color' => '6B7280',
            'rtl' => true
        ], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 300]);
        
        $serviceTypes = getServiceTypes();
        $cities = getCities();
        
        // Table
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'width' => 100 * 50
        ]);
        
        // Header row
        $table->addRow(500);
        $headerStyle = ['bold' => true, 'color' => 'FFFFFF', 'rtl' => true];
        $headerCellStyle = ['bgColor' => '2563EB', 'valign' => 'center'];
        
        $table->addCell(2500, $headerCellStyle)->addText('الخدمة', $headerStyle);
        $table->addCell(2000, $headerCellStyle)->addText('المدينة', $headerStyle);
        $table->addCell(2000, $headerCellStyle)->addText('الهاتف', $headerStyle);
        $table->addCell(2000, $headerCellStyle)->addText('وقت الخدمة', $headerStyle);
        $table->addCell(3500, $headerCellStyle)->addText('الوصف', $headerStyle);
        $table->addCell(1500, $headerCellStyle)->addText('الحالة', $headerStyle);
        $table->addCell(2000, $headerCellStyle)->addText('تاريخ الطلب', $headerStyle);
        
        // Data rows
        foreach ($leads as $lead) {
            // Get Arabic names - direct key access
            $serviceNameAr = $serviceTypes[$lead['service_type']]['ar'] ?? $lead['service_type'];
            $cityNameAr = $cities[$lead['city']]['ar'] ?? $lead['city'];
            
            // Service Time in Arabic
            $serviceTimeAr = '-';
            if ($lead['service_time_type'] === 'urgent') {
                $serviceTimeAr = '⚡ عاجل - في أقرب وقت';
            } elseif ($lead['service_time_type'] === 'within_24h') {
                $serviceTimeAr = '⏰ خلال 24 ساعة';
            } elseif ($lead['service_time_type'] === 'scheduled' && !empty($lead['scheduled_date'])) {
                $serviceTimeAr = '📅 ' . self::formatDateArabic($lead['scheduled_date']);
            }
            
            // Status in Arabic
            $statusMap = [
                'new' => '🆕 جديد',
                'verified' => '✅ موثق',
                'pending' => '⏰ معلق',
                'sold' => '💰 مباع',
                'invalid' => '❌ غير صالح'
            ];
            $statusAr = $statusMap[$lead['status']] ?? $lead['status'];
            
            // Format date in Arabic
            $createdAtAr = self::formatDateArabic($lead['created_at']);
            
            $table->addRow();
            $cellStyle = ['valign' => 'center'];
            $textStyle = ['rtl' => true];
            
            $table->addCell(2500, $cellStyle)->addText($serviceNameAr, $textStyle);
            $table->addCell(2000, $cellStyle)->addText($cityNameAr, $textStyle);
            $table->addCell(2000, $cellStyle)->addText($lead['phone'], $textStyle);
            $table->addCell(2000, $cellStyle)->addText($serviceTimeAr, $textStyle);
            $table->addCell(3500, $cellStyle)->addText(mb_substr($lead['description'] ?? 'لا يوجد وصف', 0, 100), $textStyle);
            $table->addCell(1500, $cellStyle)->addText($statusAr, $textStyle);
            $table->addCell(2000, $cellStyle)->addText($createdAtAr, $textStyle);
        }
        
        $filename = 'تقرير_الطلبات_' . date('Y-m-d_H-i-s') . '.docx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save('php://output');
        exit;
    }
}



