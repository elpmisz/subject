<?php
$menu = "setting";
$page = "setting-subject";
include_once(__DIR__ . "/../layout/header.php");
?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">เพิ่ม</h4>
      </div>
      <div class="card-body">
        <form action="/subject/create" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">

          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">รายชื่อ</label>
            <div class="col-xl-4">
              <textarea class="form-control form-control-sm" name="text" rows="3" required></textarea>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2">ประเภท</label>
            <div class="col-xl-8">
              <div class="row pb-2">
                <div class="col-xl-3">
                  <label class="form-check-label px-3">
                    <input class="form-check-input" type="radio" name="status" value="1" required>
                    <span class="text-success">หลัก</span>
                  </label>
                </div>
                <div class="col-xl-3">
                  <label class="form-check-label px-3">
                    <input class="form-check-input" type="radio" name="status" value="2" required>
                    <span class="text-danger">รอง</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="row mb-2 primary-div">
            <label class="col-xl-2 offset-xl-2 col-form-label">วิชาหลัก</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm primary-select" name="reference"></select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>

          <div class="row justify-content-center mb-2">
            <div class="col-xl-3 mb-2">
              <button type="submit" class="btn btn-sm btn-success btn-block">
                <i class="fas fa-check pr-2"></i>ยืนยัน
              </button>
            </div>
            <div class="col-xl-3 mb-2">
              <a href="/subject" class="btn btn-sm btn-danger btn-block">
                <i class="fa fa-arrow-left pr-2"></i>กลับ
              </a>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once(__DIR__ . "/../layout/footer.php"); ?>
<script>
  $(".primary-select").select2({
    placeholder: "-- วิชาหลัก --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/subject/primary-select",
      method: "POST",
      dataType: "json",
      delay: 100,
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });
</script>