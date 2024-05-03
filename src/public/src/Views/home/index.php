<?php
$menu = "home";
$page = "home-index";
include_once(__DIR__ . "/../layout/header.php");

use App\Classes\User;
use App\Classes\Dashboard;

$USER = new User();
$DASHBOARD = new Dashboard();
$card = $DASHBOARD->card();
?>
<div class="row">
  <div class="col-xl-12">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="text-center">รายงาน</h4>
      </div>
      <div class="card-body">

        <div class="row mb-2">
          <div class="col mb-2">
            <div class="card bg-color1 text-white shadow">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['email']) ? $card['email'] : 0) ?></h3>
                <h5 class="text-right">EMAIL</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card bg-color2 text-white shadow">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['position']) ? $card['position'] : 0) ?></h3>
                <h5 class="text-right">POSITION</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card bg-color3 text-white shadow">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['department']) ? $card['department'] : 0) ?></h3>
                <h5 class="text-right">DEPARTMENT</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card bg-color4 text-white shadow">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['zone']) ? $card['zone'] : 0) ?></h3>
                <h5 class="text-right">ZONE</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card bg-color5 text-white shadow">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['area']) ? $card['area'] : 0) ?></h3>
                <h5 class="text-right">AREA</h5>
              </div>
            </div>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col mb-2">
            <div class="card bg-color6 text-white shadow">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['field']) ? $card['field'] : 0) ?></h3>
                <h5 class="text-right">FIELD</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card bg-color7 text-white shadow">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['group']) ? $card['group'] : 0) ?></h3>
                <h5 class="text-right">GROUP</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card bg-color8 text-white shadow">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['user']) ? $card['user'] : 0) ?></h3>
                <h5 class="text-right">USER</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card bg-color9 text-white shadow">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['ability']) ? $card['ability'] : 0) ?></h3>
                <h5 class="text-right">ABILITY</h5>
              </div>
            </div>
          </div>
          <div class="col mb-2">
            <div class="card bg-color10 text-white shadow">
              <div class="card-body">
                <h3 class="text-right"><?php echo (isset($card['subject']) ? $card['subject'] : 0) ?></h3>
                <h5 class="text-right">SUBJECT</h5>
              </div>
            </div>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-xl-6 mb-2">
            <div class="card shadow">
              <div class="card-header">
                <h5>ทักษะ</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-sm table-borderless">
                    <?php
                    $ability = $DASHBOARD->ability_read();
                    foreach ($ability as $key => $value) : $key++; ?>
                      <tr>
                        <td class="text-center" width="10%"><?php echo $key ?></td>
                        <td width="90%"><?php echo $value['text'] ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6 mb-2">
            <div class="card shadow">
              <div class="card-header">
                <h5>รายวิชา</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-sm table-borderless">
                    <?php
                    $subject = $DASHBOARD->subject_read();
                    foreach ($subject as $key => $value) : $key++; ?>
                      <tr>
                        <td class="text-center" width="10%"><?php echo $key ?></td>
                        <td width="90%"><?php echo $value['text'] ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<?php include_once(__DIR__ . "/../layout/footer.php"); ?>