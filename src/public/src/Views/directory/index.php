<?php
$menu = "service";
$page = "service-directory";
include_once(__DIR__ . "/../layout/header.php");
?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">สมุดรายชื่อ</h4>
      </div>
      <div class="card-body">
        <div class="row justify-content-end mb-2">
          <div class="col-xl-3 mb-2">
            <button class="btn btn-success btn-sm btn-block export-btn">
              <i class="fas fa-download pr-2"></i>นำข้อมูลออก
            </button>
          </div>
          <?php if (intval($user['level']) === 9) : ?>
            <div class="col-xl-3 mb-2">
              <button class="btn btn-info btn-sm btn-block import-btn" data-toggle="modal" data-target="#import-form">
                <i class="fas fa-upload pr-2"></i>นำข้อมูลเข้า
              </button>
            </div>
          <?php endif; ?>
        </div>
        <div class="row justify-content-end mb-2">
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm filter position-select"></select>
          </div>
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm filter department-select"></select>
          </div>
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm filter zone-select"></select>
          </div>
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm filter area-select"></select>
          </div>
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm filter field-select"></select>
          </div>
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm filter group-select"></select>
          </div>
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm filter ability-select"></select>
          </div>
          <div class="col-xl-3 mb-2">
            <select class="form-control form-control-sm filter subject-select"></select>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-xl-12">
            <div>
              <table class="table table-bordered table-hover data">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>อีเมล</th>
                    <th>ตำแหน่ง</th>
                    <th>หน่วย/สาขา</th>
                    <th>ส่วน/เขต</th>
                    <th>ฝ่าย/ภาค</th>
                    <th>สายงาน</th>
                    <th>กลุ่มงาน</th>
                    <th>ทักษะ</th>
                    <th>รายวิชา</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="import-form" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header mx-auto">
        <h5 class="modal-title">นำข้อมูลเข้า</h5>
      </div>
      <div class="modal-body">
        <form action="/directory/import" method="POST" enctype="multipart/form-data" class="needs-validation import" novalidate>

          <div class="row mb-2 justify-content-center">
            <div class="col-sm-10">
              <input type="file" name="file" accept=".xls, .xlsx, .csv" required>
              <div class="invalid-feedback">
                กรุณากรอกข้อมูล!
              </div>
            </div>
          </div>

          <div class="row mb-2 justify-content-center">
            <div class="col-xl-4 mb-2">
              <button type="submit" class="btn btn-success btn-sm btn-block">
                <i class="fa fa-check mr-2"></i>ยืนยัน
              </button>
            </div>
            <div class="col-xl-4 mb-2">
              <button type="button" class="btn btn-danger btn-sm btn-block" data-dismiss="modal">
                <i class="fa fa-times mr-2"></i>ปิด
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="process-modal" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body">
        <h1 class="text-center"><span class="pr-5">Processing...</span><i class="fas fa-spinner fa-pulse"></i></h1>
      </div>
    </div>
  </div>
</div>

<?php include_once(__DIR__ . "/../layout/footer.php"); ?>
<script>
  $(document).on("click", ".export-btn", function() {
    let position = ($(".position-select").val() ? $(".position-select").val() : "");
    let department = ($(".department-select").val() ? $(".department-select").val() : "");
    let zone = ($(".zone-select").val() ? $(".zone-select").val() : "");
    let area = ($(".area-select").val() ? $(".area-select").val() : "");
    let field = ($(".field-select").val() ? $(".field-select").val() : "");
    let group = ($(".group-select").val() ? $(".group-select").val() : "");
    let ability = ($(".ability-select").val() ? $(".ability-select").val() : "");
    let subject = ($(".subject-select").val() ? $(".subject-select").val() : "");

    window.open("/directory/export/" + position + "/" + department + "/" + zone + "/" + area + "/" + field + "/" + group + "/" + ability + "/" + subject);
  });

  $("#import-form").on("hidden.bs.modal", function() {
    $(this).find("form")[0].reset();
  })

  $(document).on("change", "input[name='file']", function() {
    let fileSize = ($(this)[0].files[0].size) / (1024 * 1024);
    let fileExt = $(this).val().split(".").pop().toLowerCase();
    let fileAllow = ["xls", "xlsx", "csv"];
    let convFileSize = fileSize.toFixed(2);
    if (convFileSize > 10) {
      Swal.fire({
        icon: "error",
        title: "LIMIT 10MB!",
      })
      $(this).val("");
    }

    if ($.inArray(fileExt, fileAllow) == -1) {
      Swal.fire({
        icon: "error",
        title: "ONLY XLS XLSX CSV!",
      })
      $(this).val("");
    }
  });

  $(document).on("submit", ".import", function() {
    $("#import-form").modal("hide");
    $("#process-modal").modal("show");
  });

  filter_datatable();

  $(document).on("change", ".filter", function() {
    let position = ($(".position-select").val() ? $(".position-select").val() : "");
    let department = ($(".department-select").val() ? $(".department-select").val() : "");
    let zone = ($(".zone-select").val() ? $(".zone-select").val() : "");
    let area = ($(".area-select").val() ? $(".area-select").val() : "");
    let field = ($(".field-select").val() ? $(".field-select").val() : "");
    let group = ($(".group-select").val() ? $(".group-select").val() : "");
    let ability = ($(".ability-select").val() ? $(".ability-select").val() : "");
    let subject = ($(".subject-select").val() ? $(".subject-select").val() : "");

    console.log(position)

    if (position || department || zone || area || field || group || ability || subject) {
      $(".data").DataTable().destroy();
      filter_datatable(position, department, zone, area, field, group, ability, subject);
    } else {
      $(".data").DataTable().destroy();
      filter_datatable();
    }
  });

  function filter_datatable(position, department, zone, area, field, group, ability, subject) {
    let datatable = $(".data").DataTable({
      scrollX: true,
      serverSide: true,
      searching: true,
      order: [],
      ajax: {
        url: "/directory/data",
        type: "POST",
        data: {
          position: position,
          department: department,
          zone: zone,
          area: area,
          field: field,
          group: group,
          ability: ability,
          subject: subject,
        }
      },
      columnDefs: [{
        targets: [0],
        className: "text-center",
      }]
    });
  };

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