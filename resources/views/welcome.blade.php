<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานสรุปผลการดำเนินงาน</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<style>
    #loadingIndicator {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(255, 255, 255, 0.9);
    padding: 30px 50px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    display: none;
    z-index: 1050;
}
</style>
<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center text-primary">สรุปรายงาน - ด้านคะแนน PA</h2>

        <div class="card shadow-sm p-3 mb-4 bg-white rounded">
            <form method="GET" action="{{ url()->current() }}" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">ปี:</label>
                    <select name="year" class="form-select">
                        @foreach([2024, 2023, 2022] as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    @include('components.unit_dropdown', ['units' => $units, 'unit_name' => $unit_name])
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">แสดงข้อมูล</button>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <a href="{{ route('export.pdf', ['year' => $year, 'unit_name' => $unit_name]) }}"
                       id="exportBtn"
                       class="btn btn-danger w-100"
                       onclick="startDownload(event)">
                        Export PDF
                    </a>
                </div>
            </form>
        </div>

        <div class="text-center my-4">
            <h4>บันทึกข้อตกลงการประเมินผลการดำเนินงานระดับสายงาน ประจำปี {{ $year }}</h4>
            <p class="text-muted">
                งวด 6 เดือน (1 มกราคม - 30 มิถุนายน {{ $year }}) และงวดสิ้นปี (1 กรกฎาคม - 31 ธันวาคม {{ $year }})
            </p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center table-hover">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2">เกณฑ์วัดการดำเนินงาน</th>
                        <th rowspan="2">งวดประเมิน</th>
                        <th rowspan="2">หน่วยวัด</th>
                        <th rowspan="2">น้ำหนัก (ร้อยละ)</th>
                        <th colspan="5">ค่าเกณฑ์วัดปีบัญชี 2567 </th>
                        <th rowspan="2">ผู้รับผิดชอบ</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse ($units as $unit)
                        <tr>
                            <td>{{ $unit->score_name ?: '-' }}</td>
                            <td>{{ $unit->score_type ?: '-' }}</td>
                            <td>{{ $unit->unit ?: '-' }}</td>
                            <td>{{ $unit->weight ?: '-' }}</td>
                            <td>{{ $unit->score1 ?: '-' }}</td>
                            <td>{{ $unit->score2 ?: '-' }}</td>
                            <td>{{ $unit->score3 ?: '-' }}</td>
                            <td>{{ $unit->score4 ?: '-' }}</td>
                            <td>{{ $unit->score5 ?: '-' }}</td>
                            <td>
                                @if($unit->name_s)
                                    {{ $unit->name_s }}
                                @elseif($unit->common_flag)
                                    ทุกสายงาน
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-danger">ไม่มีข้อมูล</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- กล่อง Loading Spinner -->
    <div id="loadingIndicator">
        <div class="spinner-border text-danger" role="status"></div>
        <p class="mt-2 text-danger">กำลังดาวน์โหลด กรุณารอสักครู่...</p>
    </div>

    <script>
        async function startDownload(event) {
            event.preventDefault(); // ป้องกันไม่ให้เบราว์เซอร์เปิดลิงก์โดยตรง
    
            let exportBtn = document.getElementById('exportBtn');
            let loadingIndicator = document.getElementById('loadingIndicator');
    
            // ซ่อนปุ่ม Export PDF และแสดง Loading Spinner
            exportBtn.style.display = 'none';
            loadingIndicator.style.display = 'block';
    
            try {
                // ใช้ fetch() ดาวน์โหลดไฟล์ PDF จากเซิร์ฟเวอร์
                let response = await fetch(event.target.href);
    
                if (!response.ok) {
                    throw new Error("ดาวน์โหลดล้มเหลว");
                }
    
                // แปลงข้อมูลไฟล์เป็น Blob
                let blob = await response.blob();
                let url = window.URL.createObjectURL(blob);
    
                // สร้าง <a> สำหรับดาวน์โหลดไฟล์
                let a = document.createElement('a');
                a.href = url;
                a.download = 'report.pdf';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
    
                // ซ่อน Loading Spinner หลังดาวน์โหลดเสร็จ
                setTimeout(() => {
                    loadingIndicator.style.display = 'none';
                    exportBtn.style.display = 'block';
                }, 1000); // รอ 1 วินาทีเพื่อให้แน่ใจว่าการดาวน์โหลดเริ่มต้นแล้ว
    
            } catch (error) {
                console.error("เกิดข้อผิดพลาด:", error);
                alert("เกิดข้อผิดพลาดในการดาวน์โหลด กรุณาลองใหม่");
                
                // ซ่อน Loading Spinner และแสดงปุ่มอีกครั้ง
                loadingIndicator.style.display = 'none';
                exportBtn.style.display = 'block';
            }
        }
    </script>
</body>
</html>
