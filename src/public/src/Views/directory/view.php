<?php
$menu = "service";
$page = "service-directory";
include_once(__DIR__ . "/../layout/header.php");

$param = (isset($params) ? explode("/", $params) : die(header("Location: /error")));
$uuid = (isset($param[0]) ? $param[0] : die(header("Location: /error")));

use App\Classes\Directory;

$DIRECTORY = new Directory();
$row = $DIRECTORY->view([$uuid]);
$abilitys = $DIRECTORY->view_ability([$row['id']]);
$subjects = $DIRECTORY->view_subject([$row['id']]);
?>

<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">รายละเอียด</h4>
      </div>
      <div class="card-body">
        <form action="#" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-1 col-form-label">E-MAIL</label>
            <div class="col-xl-4 text-underline">
              <?php echo $row['email'] ?>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-1 col-form-label">ตำแหน่ง</label>
            <div class="col-xl-6 text-underline">
              <?php echo $row['position'] ?>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-1 col-form-label">หน่วย/สาขา</label>
            <div class="col-xl-6 text-underline">
              <?php echo $row['department'] ?>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-1 col-form-label">ส่วน/เขต</label>
            <div class="col-xl-6 text-underline">
              <?php echo $row['zone'] ?>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-1 col-form-label">ฝ่าย/ภาค</label>
            <div class="col-xl-6 text-underline">
              <?php echo $row['area'] ?>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-1 col-form-label">สาย</label>
            <div class="col-xl-6 text-underline">
              <?php echo $row['field'] ?>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-1 col-form-label">กลุ่ม</label>
            <div class="col-xl-6 text-underline">
              <?php echo $row['group'] ?>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-1 col-form-label">ทักษะ</label>
            <div class="col-xl-9">
              <div class="table-responsive">
                <table class="table table-sm table-striped">
                  <?php foreach ($abilitys as $key => $ability) : $key++ ?>
                    <tr>
                      <td><?php echo $key ?></td>
                      <td><?php echo $ability['text'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                </table>
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label class="col-xl-2 offset-xl-1 col-form-label">รายวิชา</label>
            <div class="col-xl-9">
              <div class="table-responsive">
                <table class="table table-sm table-striped">
                  <?php foreach ($subjects as $key => $subject) : $key++ ?>
                    <tr>
                      <td><?php echo $key ?></td>
                      <td><?php echo $subject['text'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                </table>
              </div>
            </div>
          </div>


          <div class="row justify-content-center mb-2">
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
    placeholder: "-- POSITION --",
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
    placeholder: "-- DEPARTMENT --",
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
    placeholder: "-- ZONE --",
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
    placeholder: "-- AREA --",
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
    placeholder: "-- FIELD --",
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
    placeholder: "-- GROUP --",
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
    placeholder: "-- ABILITY --",
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
    placeholder: "-- SUBJECT --",
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