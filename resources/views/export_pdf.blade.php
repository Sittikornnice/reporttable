<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Export PDF</title>
    {{-- <link rel="stylesheet" href="{{ asset('css/export.css') }}"> <!-- ลิงก์ไปยังไฟล์ CSS --> --}}
</head>
<style>
/* กำหนดให้หน้าแรก (บทที่ 1) และหน้า 2 เป็นแนวตั้ง */
@page :first {
    size: A4 portrait; /* กำหนดให้เป็นแนวตั้ง */
    margin: 10mm;
}

@page :nth-child(2) {
    size: A4 portrait; /* กำหนดให้เป็นแนวตั้ง */
    margin: 10mm;
}

/* กำหนดให้หลังจากหน้า 2 (หน้า 3 เป็นต้นไป) เป็นแนวนอน */
@page {
    size: A4 portrait; /* กำหนดให้เป็นแนวตั้ง */
    margin: 10mm;
}

/* การตั้งค่าหน้ากระดาษให้เป็นแนวนอนหลังจากหน้า 2 */
@page :nth-child(3),
@page :nth-child(4),
@page :nth-child(5),
@page :nth-child(6),
@page :nth-child(7) {
    size: A4 landscape; /* กำหนดให้เป็นแนวนอน */
    margin: 10mm;
}

        h1 {
            font-family: 'THSarabunNew' !important;
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
        }

        hr.big {
            border: 2px solid black;
            width: 100%;
            margin-bottom: 1px;
        }

        hr.small {
            border: 1px solid rgb(78, 78, 78);
            width: 100%;
            margin-top: 1px;
        }

        ul {
            font-size: 16px;
            margin: 2px 10px; /* ลดช่องว่างระหว่างรายการ */
            padding-left: 15px;
            font-family: 'THSarabunNew' !important;
        }

        p {
            text-indent: 15px;
            margin: 2px 0; /* ลดช่องว่างระหว่างพารากราฟ */
            font-family: 'THSarabunNew' !important;
            line-height: 1.3; /* ปรับระยะห่างระหว่างบรรทัด */
        }

        .container {
            display: flex;
            flex-direction: column;
            gap: 3px; /* ลดช่องว่างระหว่างแถว */
        }

        .row {
            display: grid;
            grid-template-columns: 140px auto;
            gap: 5px;
            align-items: center;
        }
   /* หน้า 3 */
        .wrapper {
            width: 90%;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            position: relative;
            overflow: hidden;
            font-family: 'THSarabunNew' !important;
        }

        .caption {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-family: 'THSarabunNew' !important;
        }

        .table-container {
            display: flex;
            justify-content: space-between;
            position: relative; /* ใช้เป็นตำแหน่งอ้างอิงให้ .right-column */
        }

        .left-column {
            width: 50%;
        }

        .right-column {
            width: 50%;
            position: absolute;
            top: 0;
            right: 0;
        }

        .header-group {
            display: grid;
            grid-template-columns: 1fr;
            background-color: #76bbdd;
            color: black;
            font-weight: bold;
        }

        .data-group {
            display: grid;
            grid-template-columns: 1fr;
        }

        .item {
            padding: 5px 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 15px;
        }
        .item.center{
            text-align: center;
            font-weight: bold;
        }

        .data-group:nth-child(even) {
            background-color: #f2f2f2;
        }
/*ตาราง*/
@font-face {
    font-family: 'THSarabunNew';
    font-style: normal;
    font-weight: normal;
    src: url("{{ base_path('public/fonts/THSarabunNew.ttf') }}") format('truetype');
}

@font-face {
    font-family: 'THSarabunNew';
    font-style: bold;
    font-weight: bold;
    src: url("{{ base_path('public/fonts/THSarabunNew-Bold.ttf') }}") format('truetype');
}

body {
    font-family: 'THSarabunNew', sans-serif !important;
    font-size: 9pt;
    line-height: 1.3; /* ลดระยะห่างระหว่างบรรทัด */
    margin: 0px;
    background: none;
}

.page {
    width: 267mm; /* 297mm (A4 แนวนอน) - (15mm * 2) */
    height: 177mm; /* 210mm (A4 แนวนอน) - (15mm * 2) */
    margin: auto;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    box-sizing: border-box;
}

.header {
    font-family: 'THSarabunNew' !important;
    font-size: 20px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 0px;
    border: 1px solid black;
    padding: 0px;
    border-bottom: none;
}

.sub-text {
    font-size: 10pt;
    text-align: center;
    color: rgb(0, 0, 0);
    border: 1px solid black;
    border-top: none;
    border-bottom: none;
    padding: 2px;
}

.filters {
    margin-bottom: 10px;
    text-align: center;
}

.filters form {
    display: flex;
    justify-content: center;
    gap: 5px;
}
.landscape-table {
    width: 100%;
    overflow-x: auto;
    page-break-before: always;
}

.table-container {
    width: 100%;
    overflow-x: auto;

}

table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    font-family: 'THSarabunNew' !important;
    font-size: 8pt;
    background: none;
    margin: auto;
}

th, td {
    border: 1px solid black;
    text-align: center;
    vertical-align: top;
    padding: 2px;
    word-wrap: break-word;
    font-family: 'THSarabunNew' !important;
}

th {
    background-color: #f1f8ff;
    font-weight: bold;
    font-size: 10.5px;
}

/* ปรับขนาดคอลัมน์ให้พอดีกับหน้ากระดาษแนวนอน */
td:nth-child(1) { width: 10%; }
td:nth-child(2) { width: 10%; }
td:nth-child(3) { width: 10%; }
td:nth-child(4) { width: 10%; }
td:nth-child(5) { width: 10%; }
td:nth-child(6) { width: 10%; }
td:nth-child(7) { width: 10%; }
td:nth-child(8) { width: 10%; }
td:nth-child(9) { width: 10%; }
td:nth-child(10) { width: 10%; }

@media print {
    body {
        margin: 0;
    }

    .page {
        width: 267mm;
        height: 177mm;
        margin: auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .page:nth-child(n+3) {
        page-break-before: always;
    }

    table {
        margin: auto;
    }
}


    </style>
{{-- บทนำ1 --}}
    <h1>1. บทนำ</h1>
    <hr class="big">
    <hr class="small">
    <p style="font-family: 'TH Sarabun New', sans-serif; font-size: 16px;">
    การประเมินผลการดำเนินงานระดับสายงานรองผู้ว่าการจะมีการประเมินปีละ 2 ครั้ง คือ งวด 6 เดือน
        (ระหว่างวันที่ 1 มกราคม - 30 มิถุนายน) และงวดสิ้นปี (ระหว่างวันที่ 1 กรกฎาคม - 31 ธันวาคม)<br> เพื่อประเมิน
        1) บันทึกข้อตกลงประเมินผลการดำเนินงานระหว่าง กฟผ. กับ สำนักงานคณะกรรมการนโยบายรัฐวิสาหกิจ
        (สคร.) กระทรวงการคลัง 2) นโยบาย/แผนปฏิบัติการของผู้ว่าการ ประจำปี 2567 3) ผลการดำเนินงานตาม
        ยุทธศาสตร์องค์การตามแผนวิสาหกิจ กฟผ. ปี 2567-2571 และ 4) ภารกิจของสายงาน โดยผลการประเมินฯ จะ
        ใช้ประกอบการพิจารณากำหนดค่าตอบแทนผันแปรในการขึ้นเงินเดือนประจำปีของแต่ละสายงาน
    </p>

    <p style="font-family: 'TH Sarabun New', sans-serif; font-size: 16px;">
        บันทึกข้อตกลงการประเมินผลการดำเนินงานระดับสายงาน ประจำปี 2567 ได้ผ่านความเห็นชอบจาก
        คณะกรรมการบริหาร กฟผ. (คบ.กฟผ.) ในการประชุมครั้งที่ 3-1/2567 เมื่อวันที่ 12 มีนาคม 2567 และลงนาม
        ร่วมกันระหว่าง
        ผู้ว่าการ และ รองผู้ว่าการทุกสายงาน เมื่อวันที่ 8 พฤษภาคม 2567</p>

        <ul>
       บันทึกข้อตกลงการประเมินผลการดำเนินงานระดับสายงาน ประจำปี 2567 สรุปได้ดังนี้ <br>
      1) <u>แนวทางการกำหนด PA สายงาน ปี 2567</u> <br>
        ใช้หลักการการดำเนินงานตามภารกิจหลักของสายงานที่กำหนดจากเป้าหมายขององค์การ เพื่อ
        ผลักดันองค์การให้ถึงเป้าหมายที่กำหนดในปี 2567 <br>
    2) <u>โครงสร้างของตัวชี้วัดผลการดำเนินงานระดับสายงาน</u>
        <ul>
            <li> <b>ตัวชี้วัด KPI</b> ได้แก่ <b>ภารกิจหลักของสายงาน</b> เป็นงานที่รับผิดชอบโดยตรง เพื่อให้การ
                ดำเนินงานเป็นไปตามเป้าหมาย น้ำหนักร้อยละ 80 (งวด 6 เดือน) และร้อยละ 75 (งวดสิ้นปี)  </li>
            <li> <strong> Business Enablers</strong> ตาม ระบบประเมินผลรัฐวิสาหกิจ (State Enterprise
                Assessment Model : SE-AM) เป็นส่วนหนึ่งของการคำนวณคะแนน PA สายงาน น้ำหนัก
                ร้อยละ 20 (งวด 6 เดือน) และร้อยละ 25 (งวดสิ้นปี)</li>
        </ul>
        3) <u>ตัวชี้วัด PA สายงาน ปี 2567 พิจารณาจาก 4 กรอบการดำเนินงาน ได้แก่ </li></u>
        <ul>
            <li>1. ตัวชี้วัดที่ กฟผ. ได้จัดทำบันทึกข้อตกลงกับสำนักงานคณะกรรมการนโยบายรัฐวิสาหกิจ (สคร.) </li>
            <li>2. ตัวชี้วัดผลการดำเนินงานตามแผนปฏิบัติงานของผู้ว่าการ </li>
            <li>3. ตัวชี้วัดยุทธศาสตร์ตามแผนวิสาหกิจของ กฟผ. </li>
            <li>4. ตัวชี้วัดภารกิจหลักของสายงาน (Core Business)</li>
        </ul>
    </ul>
    <div style="page-break-before: always;"></div>
            {{-- บทนำ2 --}}
            <h1>2. บันทึกข้อตกลงการประเมินผลการดำเนินงานระดับสายงาน ประจำปี 2567</h1>
            <hr class="big">
            <hr class="small">
            <p style="font-family: 'TH Sarabun New', sans-serif; font-size: 16px;">
            1. คู่สัญญา : ข้อตกลงการประเมินผลการดำเนินงานระหว่าง ผู้ว่าการการไฟฟ้าฝ่ายผลิตแห่งประเทศไทยโดย นายเทพรัตน์ เทพพิทักษ์ กับ</p>

            <ul>
                - รองผู้ว่าการบริหาร โดย นางสาวพนา สุภาวกุล <br>
                - รองผู้ว่าการยุทธศาสตร์โดย นายธวัชชัย สำราญวานิช <br>
                - รองผู้ว่าการการเงินและบัญชี (CFO) โดย นางพัชรินทร์ รพีพรพงศ์ <br>
                - รองผู้ว่าการผลิตไฟฟ้า โดย นายจรัญ คำเงิน <br>
                - รองผู้ว่าการเชื้อเพลิง โดย นายนรินทร์ เผ่าวณิช <br>
                - รองผู้ว่าการระบบส่ง โดย นายณัฐวุฒิ ผลประเสริฐ <br>
                - รองผู้ว่าการธุรกิจเกี่ยวเนื่อง โดย นายเมธาวัจน์ พงศ์รดาภิรมย์ <br>
                - รองผู้ว่าการพัฒนาโรงไฟฟ้าและพลังงานหมุนเวียน โดย นายทิเดช เอี่ยมสาย
            </ul>

            <p style="font-family: 'TH Sarabun New', sans-serif; font-size: 16px;">
            2. ข้อตกลงนี้สำหรับระยะเวลา 1 ปี เริ่มตั้งแต่วันที่ 1 มกราคม 2567 ถึงวันที่ 31 ธันวาคม 2567</p>

            <p style="font-family: 'TH Sarabun New', sans-serif; font-size: 16px;">
            3. วิสัยทัศน์ (Vision)</p>
            <ul>
                “นวัตกรรมพลังงานไฟฟ้าเพื่อชีวิตที่ดีกว่า” <br>
                “INNOVATE POWER SOLUTIONS FOR A BETTER LIFE”
            </ul>

            <p style="font-family: 'TH Sarabun New', sans-serif; font-size: 16px;">
            4. พันธกิจ (Mission)</p>
            <ul>
                “เป็นองค์การหลักเพื่อรักษาความมั่นคงด้านพลังงานไฟฟ้า และเพิ่มขีดความสามารถในการแข่งขันของประเทศด้วยนวัตกรรม เพื่อความสุขของคนไทย” <br>
                “BE THE COUNTRY’S MAIN ORGANIZATION TO SECURE THE POWER RELIABILITY AND ENHANCE COMPETITIVENESS OF THE NATION THROUGH INNOVATION FOR THAI HAPPINESS”
            </ul>

            <p style="font-family: 'TH Sarabun New', sans-serif; font-size: 16px;">
            5. ค่านิยมและวัฒนธรรมองค์การ กฟผ.</p>
            <ul>
                “SPEED” <br>
                <div class="container">
                    <div class="row"><span>S : Synergy</span> <span>รวมพลังประสาน</span></div>
                    <div class="row"><span>P : Proactive Approach</span> <span>รุกงานก้าวไกล</span></div>
                    <div class="row"><span>E : Empathy</span> <span>ใส่ใจสร้างมิตร</span></div>
                    <div class="row"><span>E : Entrepreneurship</span> <span>คิดแบบผู้ประกอบการ</span></div>
                    <div class="row"><span>D : Digitalization</span> <span>ขับเคลื่อนงานด้วยดิจิทัล</span></div>
                </div>
            </ul>

            <p style="font-family: 'TH Sarabun New', sans-serif; font-size: 16px;">
            6. ข้อกำหนดอื่น</p>
            <ul>
                6.1 การประเมินผลการดำเนินงานจะดำเนินการเป็น 2 งวด คืองวด 6 เดือน (มกราคม - มิถุนายน 2567) และงวดสิ้นปี (กรกฎาคม - ธันวาคม 2567) <br>
                6.2 ผู้บังคับบัญชา/หัวหน้าหน่วยงานควรถ่ายทอดตัวชี้วัดและจัดทำข้อตกลงการประเมินผลกับใต้บังคับบัญชาโดยตรงต่อไป <br>
                6.3 ระบบแรงจูงใจและค่าตอบแทน จะเป็นไปตามมติคณะกรรมการบริหารการไฟฟ้าฝ่ายผลิตแห่งประเทศไทย (คบ.กฟผ.)
            </ul>
            <p style="font-family: 'TH Sarabun New', sans-serif; font-size: 16px;">
            7. เป้าหมายของผลการดำเนินงาน (Performance Obligations) ซึ่งจะต้องบรรลุผลในระหว่างปี 2567 ปรากฏตามตารางการบูรณาการ Core Business Enablers ตามระบบประเมินผลรัฐวิสาหกิจ (State)</p>

            <div style="page-break-before: always;"></div> <!-- การแบ่งหน้า -->
            <div class="wrapper">
                <div class="caption">
                  Enterprise Assessment Model : SE-AM และตารางข้อตกลงการประเมินผลการดำเนินงาน
                  <br>ระดับสายงาน สำหรับปี 2567 ดังนี้
                </div>
                <div class="table-container">
                  <div class="left-column">
                    <div class="header-group">
                      <div class="item center">Core Business Enablers</div>
                    </div>
                    <div class="data-group">
                      <div class="item">1. การกำกับดูแลกิจการที่ดีและการนำองค์กร</div>
                    </div>
                    <div class="data-group">
                      <div class="item">2. การวางแผนเชิงกลยุทธ์</div>
                    </div>
                    <div class="data-group">
                      <div class="item">3. การบริหารความเสี่ยงและควบคุมภายใน</div>
                    </div>
                    <div class="data-group">
                      <div class="item">4.1 การมุ่งเน้นผู้มีส่วนได้ส่วนเสีย</div>
                    </div>
                    <div class="data-group">
                      <div class="item">4.2 การมุ่งเน้นลูกค้า</div>
                    </div>
                    <div class="data-group">
                      <div class="item">5. การพัฒนาเทคโนโลยีดิจิทัล</div>
                    </div>
                    <div class="data-group">
                      <div class="item">6. การบริหารทุนมนุษย์</div>
                    </div>
                    <div class="data-group">
                      <div class="item">7.1 การจัดการความรู้</div>
                    </div>
                    <div class="data-group">
                      <div class="item">7.2 การจัดการนวัตกรรม</div>
                    </div>
                    <div class="data-group">
                      <div class="item">8. การตรวจสอบภายใน</div>
                    </div>
                  </div>

                  <div class="right-column">
                    <div class="header-group">
                        <div class="item center">สายงานที่เกี่ยวข้อง</div>
                    </div>
                    <div class="data-group">
                        <div class="item center">ทุกสายงาน</div>
                    </div>
                    <div class="data-group">
                        <div class="item center">ทุกสายงาน
                        </div>
                    </div>
                    <div class="data-group">
                        <div class="item center">ทุกสายงาน</div>
                    </div>
                    <div class="data-group">
                        <div class="item center">ทุกสายงาน</div>
                    </div>
                    <div class="data-group">
                        <div class="item center">รธ. รวส. รวย. รวซ. รวพ.</div>
                    </div>
                    <div class="data-group">
                        <div class="item center">รวท. รวฟ. รวส. รวธ.</div>
                    </div>
                    <div class="data-group">
                        <div class="item center">ทุกสายงาน</div>
                    </div>
                    <div class="data-group">
                        <div class="item center">ทุกสายงาน</div>
                    </div>
                    <div class="data-group">
                        <div class="item center">ทุกสายงาน</div>
                    </div>
                    <div class="data-group">
                        <div class="item center">ทุกสายงาน</div>
                    </div>
                </div>
            </div>
        </div>

    <div class="page landscape-table">
        <div class="header">
            บันทึกข้อตกลงการประเมินผลการดำเนินงานระดับสายงาน ประจำปี {{ $year+543 }}
        </div>
        <div class="sub-text">
            งวด 6 เดือน (1 มกราคม - 30 มิถุนายน {{ $year+543 }}) และงวดสิ้นปี (1 กรกฎาคม - 31 ธันวาคม {{ $year+543 }})
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2">เกณฑ์วัดการดำเนินงาน</th>
                        <th rowspan="2">งวดประเมิน</th>
                        <th rowspan="2">หน่วยวัด</th>
                        <th rowspan="2">น้ำหนัก <br>(ร้อยละ)</th>
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
                        <td>
                            @php
                                // แยกน้ำหนักโดยใช้ explode และตรวจสอบว่ามีจุดทศนิยม
                                $weightParts = explode('.', $unit->weight);
                                // ถ้ามีจุดทศนิยมแสดงเป็น float หากไม่มีก็แสดงเป็น i nt
                                $weightDisplay = (count($weightParts) > 1 && (int) $weightParts[1] > 0) ? number_format($unit->weight, 2) : (int) $unit->weight;
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
                @empty
                    <tr>
                        <td colspan="10" class="text-danger">ไม่มีข้อมูล</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>
