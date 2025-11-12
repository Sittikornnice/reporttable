<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานสรุปผลการดำเนินงาน</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
</head>

<style>
   #loadingIndicator {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(243, 243, 243, 0.887); 
    padding: 25px 40px;
    border-radius: 15px; 
    text-align: center;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1); 
    display: none;
    z-index: 1050;
    font-size: 16px; 
    color: #000000; 
    border: 2px solid rgba(220, 220, 220, 0.5); 
}
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow-x: hidden;   /* ไม่ให้สไลด์แนวนอน */
    overflow-y: auto;     /* ให้สไลด์แนวตั้งได้ปกติ */
}
</style>

<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center text-primary">สรุปรายงาน - ด้านคะแนน PA</h2>
        <div class="card shadow-sm p-3 mb-3 bg-white rounded">
            <form method="GET" action="{{ url()->current() }}" class="row g-3" id="filterForm">    
                @csrf
                <div class="col-md-3">
                    <label for="yearSelect" class="form-label">ปี:</label>
                    <select name="year" id="yearSelect">
                        <option value="">เลือกปี...</option>
                        @foreach(range(2019, 2025) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y + 543 }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4">
                    @include('components.unit_dropdown', ['units' => $units, 'unit_name' => $unit_name])
                </div>
<!-- Spinner -->
<div id="loadingSpinner" style="
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    ">
    <div style="
        background: #ebebeb;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.18);
        padding: 28px 0 18px 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 160px;
        min-height: 160px;
        width: 180px;
        margin: auto;
    ">
        <div class="spinner-border text-primary" role="status" style="width: 2.5rem; height: 2.5rem; margin-bottom: 12px;">
        </div>
        <div style="font-size: 1.3rem; color: #222; font-weight: 500; text-align: center;">
            กำลังโหลด...
        </div>
    </div>
</div>
                <form method="GET" action="{{ route('report.pa') }}" id="filterForm">
  <div class="col-md-12 mt-3">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="1" id="unitCheckbox" name="unitCheckbox"
            {{ request()->has('unitCheckbox') || !request()->has('unitCheckbox') && !request()->has('subUnitCheckbox') ? 'checked' : '' }}
            onchange="document.getElementById('filterForm').submit();">
        <label class="form-check-label" for="unitCheckbox">หน่วยงานนั้น</label>
    </div> 
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="1" id="subUnitCheckbox" name="subUnitCheckbox"
            {{ request()->has('subUnitCheckbox') || !request()->has('unitCheckbox') && !request()->has('subUnitCheckbox') ? 'checked' : '' }}
            onchange="document.getElementById('filterForm').submit();">
        <label class="form-check-label" for="subUnitCheckbox">หน่วยงานใต้สังกัด</label>
    </div>
</div>
</form>
       <div class="d-flex justify-content-end gap-2" style="margin-top: -110px;">
    <button type="button" class="btn btn-primary" onclick="resetFilterForm()" style="min-width: 140px;">
        ล้างข้อมูล
    </button>

        <form method="GET" action="{{ route('export.pdf') }}" id="pdfExportForm" class="d-inline">
            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="unit_name" value="{{ $unit_name }}">
            <input type="hidden" name="department" value="{{ $department }}">
            @if(request('unitCheckbox'))
                <input type="hidden" name="unitCheckbox" value="1">
            @endif
            @if(request('subUnitCheckbox'))
                <input type="hidden" name="subUnitCheckbox" value="1">
            @endif

            <button type="button" class="btn btn-danger" id="exportBtn" onclick="startDownload()" style="min-width: 140px;">
                <i class="fa fa-file-pdf"></i> Export PDF
            </button>

            <div id="loadingIndicator" style="display: none;">
                กำลังดาวน์โหลด กรุณารอสักครู่...
            </div>
        </form>
    </div>
    </form>
</div>
        <!-- หัวข้อ -->
        <div class="text-center my-4">
            <h4>
                @if($year) 
                    บันทึกข้อตกลงการประเมินผลการดำเนินงานระดับสายงาน ประจำปี {{ $year + 543 }}
                @else
                    กรุณาเลือกข้อมูลที่ต้องการดู
                @endif
            </h4>
            <p class="text-muted">
                @if($year)
                    งวด 6 เดือน (1 มกราคม - 30 มิถุนายน {{ $year + 543 }}) และงวดสิ้นปี (1 กรกฎาคม - 31 ธันวาคม {{ $year + 543 }})
                @else
                    กรุณาเลือกข้อมูล
                @endif
            </p>
        </div>

@if(
    $year &&
    count($units) > 0 &&
    (
        $department
        || (
            $year >= 2019 && $year <= 2022 &&
            collect($units)->every(fn($u) => is_null($u->name_s))
        )
    )
)        
<div class="table-responsive">
    <table id="myTable" class="table table-bordered text-center table-hover">
        <thead class="table-dark">
            <tr>
                <th rowspan="2" class="align-middle text-center">เกณฑ์วัดการดำเนินงาน</th>
                <th rowspan="2" class="align-middle text-center">งวดประเมิน</th>
                <th rowspan="2" class="align-middle text-center">หน่วยวัด</th>
                <th rowspan="2" class="align-middle text-center">น้ำหนัก <br>(ร้อยละ)</th>
                <th colspan="5" class="text-center align-middle">ค่าเกณฑ์วัดปีบัญชี {{ $year + 543 }}</th>
                <th rowspan="2" class="align-middle text-center">ผู้รับผิดชอบ</th>
            </tr>
            <tr>
                <th class="text-center align-middle">1</th>
                <th class="text-center align-middle">2</th>
                <th class="text-center align-middle">3</th>
                <th class="text-center align-middle">4</th>
                <th class="text-center align-middle">5</th>
            </tr>
        </thead>
        <tbody>
            @php $hasData = false; @endphp
            @foreach ($units as $unit)
                @if (
                    ($year >= 2019 && $year <= 2022)
                    || (is_null($unit->name_s) || is_null($unit->unit_name))
                    || (!is_null($unit->name_s) && !is_null($unit->unit_name))
                )
                @php $hasData = true; @endphp
                <tr>
                    <td>{{ $unit->score_name ?: '-' }}</td>
                    <td>{{ $unit->score_type ?: '-' }}</td>
                    <td>{{ $unit->unit ?: '-' }}</td>
                    <td class="text-center align-middle">
                        @php
                            $weightParts = explode('.', $unit->weight);
                            $weightDisplay = (count($weightParts) > 1 && (int) $weightParts[1] > 0) 
                                ? number_format($unit->weight, 2) 
                                : (int) $unit->weight;
                        @endphp
                        {{ $weightDisplay }}
                    </td>
                    <td>{{ $unit->score1 ?: '-' }}</td>
                    <td>{{ $unit->score2 ?: '-' }}</td>
                    <td>{{ $unit->score3 ?: '-' }}</td>
                    <td>{{ $unit->score4 ?: '-' }}</td>
                    <td>{{ $unit->score5 ?: '-' }}</td>
                    <td>{{ $unit->name_s ?: 'ทุกสายงาน' }}</td>
                </tr>
                @endif
            @endforeach
</tbody>
            </table>
        </div>
     @elseif(request('subUnitCheckbox') && $department && $year && count($units) == 0)
    <div class="alert alert-warning text-center">
        <strong>ไม่มีข้อมูลหน่วยงานใต้สังกัด กรุณาเลือกหน่วยงานใหม่</strong>
    </div>
@else
    <div class="alert alert-warning text-center">
        <strong>กรุณาเลือกหน่วยงานและปีที่ต้องการดูข้อมูล</strong>
    </div>
@endif
    </div>
    <script>
        console.log(@json($units));
    </script>
    <!-- Spinner -->
    {{-- <div id="loadingIndicator">
        <div class="spinner-border text-danger" role="status"></div>
        <p class="mt-2 text-danger">กำลังดาวน์โหลด กรุณารอสักครู่...</p>
    </div> --}}

    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <!-- เรียกใช้ DataTables -->
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                language: {
                    decimal: "ไม่พบข้อมูล",
                    emptyTable: "ไม่พบข้อมูล",
                    info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                    infoEmpty: "แสดง 0 ถึง 0 จาก 0 รายการ",
                    infoFiltered: "(กรองจากทั้งหมด _MAX_ รายการ)",
                    lengthMenu: "แสดง _MENU_ รายการ",
                    loadingRecords: "กำลังโหลด...",
                    processing: "กำลังประมวลผล...",
                    search: "ค้นหา:",
                    zeroRecords: "ไม่พบข้อมูลที่ค้นหา",
                    paginate: {
                        first: "หน้าแรก",
                        last: "หน้าสุดท้าย",
                        next: "ถัดไป",
                        previous: "ก่อนหน้า"
                    }
                },
                pageLength: 10,
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "ทั้งหมด"] ],
                responsive: true,
                order: [] // ไม่เรียงข้อมูลโดยอัตโนมัติ
            });
        });
    </script>

    <!-- Export PDF -->
    <script>
        async function startDownload() {
            let exportBtn = document.getElementById('exportBtn');
            let loadingIndicator = document.getElementById('loadingIndicator');
            let form = document.getElementById('pdfExportForm');
    
            exportBtn.style.display = 'none';
            loadingIndicator.style.display = 'block';
    
            try {
                // สร้าง query string จาก form data
                let formData = new FormData(form);
                let queryString = new URLSearchParams(formData).toString();
                let url = form.action + '?' + queryString;
    
                // ดาวน์โหลด PDF
                let response = await fetch(url);
                if (!response.ok) throw new Error("ดาวน์โหลดล้มเหลว");
    
                let blob = await response.blob();
                let pdfUrl = window.URL.createObjectURL(blob);
                let a = document.createElement('a');
                a.href = pdfUrl;
                a.download = 'report.pdf';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
    
            } catch (error) {
                console.error("เกิดข้อผิดพลาด:", error);
                alert("เกิดข้อผิดพลาดในการดาวน์โหลด กรุณาลองใหม่");
            } finally {
                loadingIndicator.style.display = 'none';
                exportBtn.style.display = 'block';
            }
        }
    </script>

<script>
    const unitCheckbox = document.getElementById('unitCheckbox');
if (unitCheckbox) {
    unitCheckbox.addEventListener('change', showLoadingSpinner);
}
const subUnitCheckbox = document.getElementById('subUnitCheckbox');
if (subUnitCheckbox) {
    subUnitCheckbox.addEventListener('change', function() {
        showLoadingSpinner();
        if (this.checked) {
            // lockDepartmentDropdown();
        } else {
            // unlockDepartmentDropdown();
        }
    });
}
</script>


<script>
function resetFilterForm() {
    if (window.$) {
        // 1. ล้างข้อมูลหน่วยงาน
        if ($('#department')[0]) {
            const deptSelect = $('#department')[0].selectize;
            if (deptSelect.getValue()) {
                deptSelect.clear();
                $('#filterForm')[0].submit();
                return;
            }
        }
        // 2. ล้างข้อมูลระดับ
        if ($('#unit_name')[0]) {
            const unitSelect = $('#unit_name')[0].selectize;
            if (unitSelect.getValue()) {
                unitSelect.clear();
                $('#filterForm')[0].submit();
                return;
            }
        }
        // 3. ล้างข้อมูลปี
        if ($('#yearSelect')[0]) {
            const yearSelect = $('#yearSelect')[0].selectize;
            if (yearSelect.getValue()) {
                yearSelect.clear();
                $('#filterForm')[0].submit();
                return;
            }
        }
    }
    // ถ้าล้างหมดแล้ว รีเซ็ต checkbox และฟอร์ม
    document.getElementById('unitCheckbox').checked = false;
    document.getElementById('subUnitCheckbox').checked = false;
    document.getElementById('filterForm').reset();
    window.location = "{{ url()->current() }}";
}
</script>

<!-- Submit Form -->
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const submitButton = document.querySelector('.btn-primary');
        if (submitButton) {
            submitButton.addEventListener('click', function () {
                document.getElementById('filterForm').submit();
            });
        }
    });
</script> --}}
    <!-- Selectize CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/css/selectize.bootstrap3.css" />
<!-- Selectize JS -->
<script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>

    <script>
         $(document).ready(function () {
       @php
         if ($unit_name == 'ฝ่าย'){
            $jsUnits = $units->where('name_s', '!=', '')->where('level', '=', '3')->values()->all();
         }else if ($unit_name == 'ผู้ช่วยผู้ว่าการ'){
            $jsUnits = $units->where('name_s', '!=', '')->where('level', '=', '2')->values()->all();  
         }else if ($unit_name == 'สายงาน yay'){
            $jsUnits = $units->where('name_s', '!=', '')->where('level', '=', '1')->values()->all();  
         }else if ($unit_name == 'กฟผ. yay'){
            $jsUnits = $units->where('name_s', '!=', '')->where('level', '=', '0')->values()->all();
            }else if ($unit_name == 'ผวก.'){
            $jsUnits = $units->where('name_s', '!=', '')->where('level', '=', '1')->values()->all();
        } else {  
                $jsUnits = $units->all();
         }
         @endphp
    const units = @json($jsUnits);
    const unitOptions = [];
    const departmentOptions = [];
    const unitGroups = new Set();
    const departmentGroups = new Set();
    units.forEach(u => {
        if (u.unit_name && u.name_s) {
            // ระดับ
            unitOptions.push({
                // group: u.name_s,
                value: u.unit_name,
                name: u.unit_name
            });
            unitGroups.add(u.name_s);

            // หน่วยงาน
            departmentOptions.push({
                // group: u.unit_name,
                value: u.name_s,
                name: u.name_s
            });
            departmentGroups.add(u.unit_name);
        }
    });
    <?php
if ($subUnitCheckbox==1){ 
    ?>
     // หน่วยงาน
            departmentOptions.push({
                // group:  "{{ request('department', $department) }}", 
                value:             "{{ request('department', $department) }}",
                name:             "{{ request('department', $department) }}",
            });
            departmentGroups.add("{{ request('department', $department) }}",);
<?php
    }
    ?>
    // สร้าง Selectize สำหรับหน่วย
 // กำหนดค่าที่เลือกไว้ก่อนหน้า
    const selectedUnit = "{{ request('unit_name', $unit_name) }}";
    const selectedDept = "{{ request('department', $department) }}";
    const selectedYear = "{{ request('year', $year) }}";

    // เพิ่มค่าที่เลือกไว้ใน unitOptions ถ้ายังไม่มี
    if (selectedUnit && !unitOptions.some(opt => opt.value === selectedUnit)) {
        unitOptions.push({
            value: selectedUnit,
            name: selectedUnit
        });
        unitGroups.add('เลือกไว้');
    }
    // สร้าง Selectize สำหรับหน่วย
    const unitSelect = $('#unit_name').selectize({
        options: unitOptions,
        optgroups: Array.from(unitGroups).map(g => ({
            value: g,
            label: 'หน่วยงาน: ' + g
        })),
        optgroupField: 'group',
        labelField: 'name',
        searchField: ['name'],
        sortField: 'name',
        placeholder: 'เลือกระดับ...',
        onChange: function(value) {
            const prevValue = "{{ request('unit_name', $unit_name) }}";
            if (value && value !== prevValue) {
                $('#filterForm')[0].submit();
            }
        }
    })[0].selectize;

    // สร้าง Selectize สำหรับหน่วยงาน
    const deptSelect = $('#department').selectize({
        options: departmentOptions,
        optgroups: Array.from(departmentGroups).map(g => ({
            value: g,
            label: 'ระดับ: ' + g
        })),
        optgroupField: 'group',
        labelField: 'name',
        searchField: ['name'],
        sortField: 'name',
        placeholder: 'เลือกหน่วยงาน...',
        onChange: function(value) {
            const prevValue = "{{ request('department', $department) }}";
            if (value && value !== prevValue) {
                $('#filterForm')[0].submit();
            }
        }
    })[0].selectize;

    // สร้าง Selectize สำหรับปี
    const yearOptions = [];
    @foreach(range(2019, 2025) as $y)
        yearOptions.push({
            value: "{{ $y }}",
            name: "{{ $y + 543 }}"
        });
    @endforeach

    const yearSelect = $('#yearSelect').selectize({
        options: yearOptions,
        labelField: 'name',
        valueField: 'value',
        searchField: 'name',
        sortField: 'name',
        placeholder: 'เลือกปี...',
        onChange: function(value) {
            const prevValue = "{{ request('year', $year) }}";
            if (value && value !== prevValue) {
                $('#filterForm')[0].submit();
            }
        }
    })[0].selectize;

    // set ค่าเดิมที่เลือกไว้
    if (selectedUnit) unitSelect.setValue(selectedUnit);
    if (selectedDept) deptSelect.setValue(selectedDept);
    if (selectedYear) yearSelect.setValue(selectedYear);
});

</script>
</body>
</html>
