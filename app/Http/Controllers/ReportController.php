<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private function getUnitsData($year, $unit_name, $department, $unitCheckbox, $subUnitCheckbox)
    {
        $query = DB::table('pas as a')
            ->joinSub(
                DB::table(DB::raw("(SELECT pa_id, m_action_id,
                (CASE
                    WHEN action_at = MAX(action_at) OVER (PARTITION BY pa_id)
                    THEN action_at
                END) latest_action_at
                FROM trx_pa_logs) x"))
                    ->whereNotNull('latest_action_at')
                    ->where('m_action_id', 3),
                'y',
                'a.id',
                '=',
                'y.pa_id'
            )
            ->leftJoin('scores as b', 'a.id', '=', 'b.pa_id')
            ->leftJoin('score_levels as c', 'b.id', '=', 'c.score_id')
            ->leftJoin('score_level_orgs as d', 'c.id', '=', 'd.score_level_id')
            ->leftJoin('m_levels as e', 'c.m_level_id', '=', 'e.id')
            ->leftJoin('score_score_types as f', 'c.id', '=', 'f.score_level_id')
            ->leftJoin('score_types as g', 'f.score_type_id', '=', 'g.id')
            ->leftJoin('m_orgs as og', 'd.m_org_id', '=', 'og.id')
            ->select(
                'a.year',
                'e.name as unit_name',
                'a.name as score_name',
                'g.name as score_type',
                'b.unit',
                'b.score1',
                'b.score2',
                'b.score3',
                'b.score4',
                'b.score5',
                'c.m_level_id',
                'og.name_s',
                'd.weight',
                'a.common_flag',
                'og.*'
            )
            ->where('a.year', $year)
            ->when(!empty($unit_name), function ($query) use ($unit_name) {
                return $query->where('e.name', 'LIKE', "%$unit_name%");
            })
            ->when(!empty($department), function ($query) use ($department) {
                return $query->where('og.hierachy_reverse_path', 'LIKE', "%$department%");
            })
            ->whereNotNull('b.id')
            ->whereNull('a.deleted_at')
            ->distinct();
    
        // ✅ เงื่อนไขการกรองตาม checkbox
        if ($unitCheckbox && !$subUnitCheckbox) {
            // แสดงเฉพาะหน่วยงานที่เลือก (เช่น กฟผ.)
            $query->where('og.name_s', '=', $department);
        } elseif (!$unitCheckbox && $subUnitCheckbox) {
            // แสดงเฉพาะหน่วยงานที่อยู่ใต้สังกัด (ไม่รวมหน่วยงานหลัก)
            $query->where('og.hierachy_reverse_path', 'LIKE', "%$department%")
            ->where('og.name_s', '!=', $department);
        }
        // ถ้าเลือกทั้งสอง checkbox แล้ว ไม่ต้องกรองอะไรเพิ่มเติม
        return $query->get();
    }
    
public function show(Request $request)
{
    $year = $request->input('year'); // ไม่ใส่ค่า default
    $unit_name = $request->input('unit_name', ''); // ค่าที่รับจากฟอร์ม
    $department = $request->input('department', ''); // ค่าที่รับจากฟอร์ม
    $unitCheckbox = $request->has('unitCheckbox'); // เช็คว่าเลือก "หน่วยงานนั้น" หรือไม่
    $subUnitCheckbox = $request->has('subUnitCheckbox'); // เช็คว่าเลือก "หน่วยงานใต้สังกัด" หรือไม่
    // dd( $subUnitCheckbox);
    // ดึงข้อมูลที่กรองตามปี, หน่วยงาน และหน่วยงานภายใน
    $units = $this->getUnitsData($year, $unit_name, $department, $unitCheckbox, $subUnitCheckbox);
    // ส่งข้อมูลไปยัง View
    return view('report_pa', compact('units', 'year', 'unit_name', 'department','subUnitCheckbox'));
}
public function exportPdf(Request $request)
{
    ini_set('memory_limit', '53248M');
    ini_set('max_execution_time', 300);

    $year = $request->input('year', date('Y'));
    $unit_name = $request->input('unit_name', ''); // ค่าที่รับจากฟอร์ม
    $department = $request->input('department', ''); // ค่าที่รับจากฟอร์ม
    $unitCheckbox = $request->has('unitCheckbox'); // เช็คว่าหน่วยงานนั้นถูกเลือกไหม
    $subUnitCheckbox = $request->has('subUnitCheckbox'); // เช็คว่าหน่วยงานใต้สังกัดถูกเลือกไหม

    // ดึงข้อมูลที่กรองตามปี, หน่วยงาน และหน่วยงานภายใน
    $units = $this->getUnitsData($year, $unit_name, $department, $unitCheckbox, $subUnitCheckbox);

    // สร้างไฟล์ PDF โดยใช้ข้อมูลที่กรองมา
    $pdf = Pdf::loadView('export_pdf', compact('units', 'year', 'unit_name', 'department'))
        ->setPaper('A4', 'landscape'); // ตั้งค่ากระดาษเป็น A4 แบบ landscape

    // ดาวน์โหลดไฟล์ PDF
    return $pdf->download('report_' . $year . '.pdf');
}
}
