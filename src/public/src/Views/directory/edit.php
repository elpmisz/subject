<?php
$menu = "service";
$page = "service-directory";
include_once(__DIR__ . "/../layout/header.php");

$param = (isset($params) ? explode("/", $params) : die(header("Location: /error")));
$uuid = (isset($param[0]) ? $param[0] : die(header("Location: /error")));

use App\Classes\Directory;

$DIRECTORY = new Directory();
$row = $DIRECTORY->view([$uuid]);
$abilitys = $DIRECTORY->ability_view([$uuid]);
$subjects = $DIRECTORY->subject_view([$uuid]);
?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">รายละเอียด</h4>
      </div>
      <div class="card-body">
        <form action="/directory/update" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">

          <div class="row mb-2" style="display: none;">
            <label class="col-xl-2 offset-xl-2 col-form-label">ID</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="id" value="<?php echo $row['id'] ?>" readonly>
            </div>
          </div>
          <div class="row mb-2" style="display: none;">
            <label class="col-xl-2 offset-xl-2 col-form-label">UUID</label>
            <div class="col-xl-4">
              <input type="text" class="form-control form-control-sm" name="uuid" value="<?php echo $row['uuid'] ?>" readonly>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">อีเมล</label>
            <div class="col-xl-4">
              <input type="email" class="form-control form-control-sm" name="email" value="<?php echo $row['email'] ?>" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">ตำแหน่ง</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm position-select" name="position" required>
                <?php echo '<option value="' . $row['position'] . '">' . $row['position'] . '</option>'; ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">หน่วย/สาขา</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm department-select" name="department" required>
                <?php echo '<option value="' . $row['department'] . '">' . $row['department'] . '</option>'; ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">ส่วน/เขต</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm zone-select" name="zone" required>
                <?php echo '<option value="' . $row['zone'] . '">' . $row['zone'] . '</option>'; ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">ฝ่าย/ภาค</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm area-select" name="area" required>
                <?php echo '<option value="' . $row['area'] . '">' . $row['area'] . '</option>'; ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">สายงาน</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm field-select" name="field" required>
                <?php echo '<option value="' . $row['field'] . '">' . $row['field'] . '</option>'; ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">กลุ่มงาน</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm group-select" name="group" required>
                <?php echo '<option value="' . $row['group'] . '">' . $row['group'] . '</option>'; ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">ทักษะ</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm ability-select" name="ability[]" multiple>
                <?php
                foreach ($abilitys as $ability) {
                  echo '<option value="' . $ability['text'] . '" selected>' . $ability['text'] . '</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-2 col-form-label">รายวิชา</label>
            <div class="col-xl-6">
              <select class="form-control form-control-sm subject-select" name="subject[]" multiple>
                <?php
                foreach ($subjects as $subject) {
                  echo '<option value="' . $subject['text'] . '" selected>' . $subject['text'] . '</option>';
                }
                ?>
              </select>
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
              <a href="/directory" class="btn btn-sm btn-danger btn-block">
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
  $(".position-select").select2({
    placeholder: "-- ตำแหน่ง --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/position-select",
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

  $(".department-select").select2({
    placeholder: "-- หน่วย/สาขา --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/department-select",
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

  $(".zone-select").select2({
    placeholder: "-- ส่วน/เขต --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/zone-select",
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

  $(".area-select").select2({
    placeholder: "-- ฝ่าย/ภาค --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/area-select",
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

  $(".field-select").select2({
    placeholder: "-- สายงาน --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/field-select",
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

  $(".group-select").select2({
    placeholder: "-- กลุ่มงาน --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/group-select",
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

  $(".ability-select").select2({
    placeholder: "-- ทักษะ --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/ability-select",
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

  $(".subject-select").select2({
    placeholder: "-- รายวิชา --",
    allowClear: true,
    width: "100%",
    ajax: {
      url: "/directory/subject-select",
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