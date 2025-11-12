<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private function getUnitsData($year, $unit_name, $department)
    {
        return DB::table('pas as a')
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
                'og.name_s',  // ดึงค่า name_s จาก m_orgs
                'd.weight',
                'a.common_flag'
            )
            ->where('a.year', $year)
            ->when(!empty($unit_name), function ($query) use ($unit_name) {
                return $query->where('e.name', 'LIKE', "%$unit_name%");
            })
            ->when(!empty($department), function ($query) use ($department) {
                return $query->where('og.hierachy_path', 'LIKE', "%$department%");
            })
            ->whereNotNull('b.id')
            ->whereNull('a.deleted_at')
            ->distinct()
            ->get();
    }

    public function show(Request $request)
    {
        $year      = $request->input('year', date('Y'));
        $unit_name = $request->input('unit_name', '');
        $department = $request->input('department', ''); // เพิ่มรับค่าหน่วยงาน

        $units = $this->getUnitsData($year, $unit_name, $department);

        return view('report_pa', compact('units', 'year', 'unit_name', 'department'));
    }

    public function exportPdf(Request $request)
    {
        ini_set('memory_limit', '53248M');
        ini_set('max_execution_time', 300);

        $year      = $request->input('year', date('Y'));
        $unit_name = $request->input('unit_name', '');
        $department = $request->input('department', ''); // เพิ่มรับค่าหน่วยงาน

        // ดึงข้อมูลที่กรองตามปี, หน่วยงาน และหน่วยงานภายใน
        $units = $this->getUnitsData($year, $unit_name, $department);

        // สร้างไฟล์ PDF
        $pdf = Pdf::loadView('export_pdf', compact('units', 'year', 'unit_name', 'department'))
            ->setPaper('A4', 'landscape');

        // ดาวน์โหลดไฟล์ PDF
        return $pdf->download('report_' . $year . '.pdf');
    }
}
