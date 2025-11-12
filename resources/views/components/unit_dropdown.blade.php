<div class="row">
    <!-- Dropdown ระดับ -->
    <div class="col-md-6">
        <label for="unit_name" class="form-label">ระดับ:</label>
        <select name="unit_name" id="unit_name" disabled>
            <option value="" {{ request('unit_name', $unit_name) == '' ? 'selected' : '' }}>เลือกระดับ...</option>
            @foreach ($units->unique('unit_name')->where('unit_name', '!=', '') as $unit)
                <option value="{{ $unit->unit_name }}" {{ request('unit_name', $unit_name) == $unit->unit_name ? 'selected' : '' }}>
                    {{ $unit->unit_name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Dropdown หน่วยงาน -->
    <div class="col-md-6">
        <label for="department" class="form-label">หน่วยงาน:</label>
        <select name="department" id="department" disabled>
    <option value="" {{ request('department', $department) == '' ? 'selected' : '' }}>เลือกหน่วยงาน...</option>
<?php
if ($subUnitCheckbox==1){ 
    ?>
     <option value="{{ request('department', $department) }}" selected>
            {{ request('department', $department) }}
        </option>
<?php
    }
    ?>
    @if ($unit_name=='ฝ่าย')
     @foreach ($units->unique('name_s')->where('name_s', '!=', '')->where('level', '=', '3') as $unit)
        {{-- <option value="{{ $unit->name_s }}" {{ request('department', $department) == $unit->name_s ? 'selected' : '' }}>
            {{ $unit->name_s .'ก'}} 
        </option> --}}
    @endforeach
    @else 
    @endif
</select>
    </div>
</div>





<script>
    let lastSelectedDepartment = document.getElementById('department').value;
    const departmentDropdown = document.getElementById('department');
    const allDepartmentOptions = Array.from(departmentDropdown.options).map(option => option.cloneNode(true));

    function showLoadingSpinner() {
        const spinner = document.getElementById('loadingSpinner');
        spinner.style.display = 'block';
        setTimeout(() => {
            spinner.style.display = 'none';
        }, 5000); // ปรับเวลาได้ตามต้องการ
    }

    // อัปเดตค่าที่เลือกไว้ล่าสุดทุกครั้งที่เปลี่ยน dropdown
    departmentDropdown.addEventListener('change', function() {
        lastSelectedDepartment = this.value;
    });

    function lockDepartmentDropdown() {
        // ลบ option ทั้งหมดก่อน
    //     departmentDropdown.innerHTML = '';
    //     // เพิ่ม option ที่เลือกไว้ล่าสุดเท่านั้น (ถ้าไม่ใช่ค่าว่าง)
    //     allDepartmentOptions.forEach(option => {
    //         if (option.value === lastSelectedDepartment && lastSelectedDepartment !== "") {
    //             departmentDropdown.appendChild(option.cloneNode(true));
    //         }
    //     });
    //     // ถ้าเลือกค่าว่างไว้ ให้เพิ่ม option ว่าง
    //     if (lastSelectedDepartment === "") {
    //         allDepartmentOptions.forEach(option => {
    //             if (option.value === "") {
    //                 departmentDropdown.appendChild(option.cloneNode(true));
    //             }
    //         });
    //     }
    //     departmentDropdown.value = lastSelectedDepartment;
    }

    function unlockDepartmentDropdown() {
        departmentDropdown.innerHTML = '';
        allDepartmentOptions.forEach(option => {
            departmentDropdown.appendChild(option.cloneNode(true));
        });
        departmentDropdown.value = lastSelectedDepartment;
    }

    // document.getElementById('unitCheckbox').addEventListener('change', showLoadingSpinner);
    // document.getElementById('subUnitCheckbox').addEventListener('change', function() {
    //     showLoadingSpinner();
    //     if (this.checked) {
    //         // lockDepartmentDropdown();
    //     } else {
    //         // unlockDepartmentDropdown();
    //     }
    // });

    window.addEventListener('DOMContentLoaded', function() {
        const subUnitCheckbox = document.getElementById('subUnitCheckbox');
        if (subUnitCheckbox.checked) { 
            lockDepartmentDropdown();
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const yearSelect = document.getElementById('yearSelect');
    const unitNameSelect = document.getElementById('unit_name');
    const departmentSelect = document.getElementById('department');

    // เริ่มต้น: disable ทั้งระดับและหน่วยงาน
    unitNameSelect.disabled = true;
    departmentSelect.disabled = true;

    // ถ้าเลือกปีแล้ว ให้ enable dropdown ระดับ
    if (yearSelect.value) {
        unitNameSelect.disabled = false;
    }

    // ถ้าเลือกระดับแล้ว ให้ enable dropdown หน่วยงาน
    if (unitNameSelect.value) {
        departmentSelect.disabled = false;
    }

    // เมื่อเปลี่ยนปี ให้ enable/disable ระดับและหน่วยงานใหม่
    yearSelect.addEventListener('change', function () {
        if (this.value) {
            unitNameSelect.disabled = false;
        } else {
            unitNameSelect.disabled = true;
            departmentSelect.disabled = true;
            unitNameSelect.value = '';
            departmentSelect.value = '';
        }
    });

    // เมื่อเปลี่ยนระดับ ให้ enable/disable หน่วยงานใหม่
    unitNameSelect.addEventListener('change', function () {
        if (this.value) {
            departmentSelect.disabled = false;
        } else {
            departmentSelect.disabled = true;
            departmentSelect.value = '';
        }
    });
});
</script>