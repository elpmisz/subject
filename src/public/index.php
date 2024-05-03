<?php
require_once(__DIR__ . "/vendor/autoload.php");

$ROUTER = new AltoRouter();

##################### SERVICE #####################
###################################################
$ROUTER->map("GET", "/directory", function () {
  require(__DIR__ . "/src/Views/directory/index.php");
});
$ROUTER->map("POST", "/directory/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/directory/action.php");
});
$ROUTER->map("GET", "/directory/view/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/directory/view.php");
});
$ROUTER->map("GET", "/directory/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/directory/edit.php");
});
$ROUTER->map("GET", "/directory/export/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/directory/export.php");
});


##################### SETTING #####################
###################################################
$ROUTER->map("GET", "/system", function () {
  require(__DIR__ . "/src/Views/system/index.php");
});
$ROUTER->map("POST", "/system/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/system/action.php");
});

#####################
$ROUTER->map("GET", "/user", function () {
  require(__DIR__ . "/src/Views/user/index.php");
});
$ROUTER->map("GET", "/user/create", function () {
  require(__DIR__ . "/src/Views/user/create.php");
});
$ROUTER->map("GET", "/user/profile", function () {
  require(__DIR__ . "/src/Views/user/profile.php");
});
$ROUTER->map("GET", "/user/change", function () {
  require(__DIR__ . "/src/Views/user/change.php");
});
$ROUTER->map("GET", "/user/edit/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/user/edit.php");
});
$ROUTER->map("POST", "/user/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/user/action.php");
});

#####################
$ROUTER->map("GET", "/ability", function () {
  require(__DIR__ . "/src/Views/ability/index.php");
});
$ROUTER->map("GET", "/ability/create", function ($params) {
  require(__DIR__ . "/src/Views/ability/create.php");
});
$ROUTER->map("GET", "/ability/export", function ($params) {
  require(__DIR__ . "/src/Views/ability/export.php");
});
$ROUTER->map("GET", "/ability/view/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/ability/view.php");
});
$ROUTER->map("POST", "/ability/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/ability/action.php");
});

#####################
$ROUTER->map("GET", "/subject", function () {
  require(__DIR__ . "/src/Views/subject/index.php");
});
$ROUTER->map("GET", "/subject/create", function ($params) {
  require(__DIR__ . "/src/Views/subject/create.php");
});
$ROUTER->map("GET", "/subject/export", function ($params) {
  require(__DIR__ . "/src/Views/subject/export.php");
});
$ROUTER->map("GET", "/subject/view/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/subject/view.php");
});
$ROUTER->map("POST", "/subject/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/subject/action.php");
});

#####################
$ROUTER->map("GET", "/position", function () {
  require(__DIR__ . "/src/Views/position/index.php");
});
$ROUTER->map("GET", "/position/create", function ($params) {
  require(__DIR__ . "/src/Views/position/create.php");
});
$ROUTER->map("GET", "/position/export", function ($params) {
  require(__DIR__ . "/src/Views/position/export.php");
});
$ROUTER->map("GET", "/position/view/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/position/view.php");
});
$ROUTER->map("POST", "/position/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/position/action.php");
});

#####################
$ROUTER->map("GET", "/department", function () {
  require(__DIR__ . "/src/Views/department/index.php");
});
$ROUTER->map("GET", "/department/create", function ($params) {
  require(__DIR__ . "/src/Views/department/create.php");
});
$ROUTER->map("GET", "/department/export", function ($params) {
  require(__DIR__ . "/src/Views/department/export.php");
});
$ROUTER->map("GET", "/department/view/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/department/view.php");
});
$ROUTER->map("POST", "/department/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/department/action.php");
});

#####################
$ROUTER->map("GET", "/zone", function () {
  require(__DIR__ . "/src/Views/zone/index.php");
});
$ROUTER->map("GET", "/zone/create", function ($params) {
  require(__DIR__ . "/src/Views/zone/create.php");
});
$ROUTER->map("GET", "/zone/export", function ($params) {
  require(__DIR__ . "/src/Views/zone/export.php");
});
$ROUTER->map("GET", "/zone/view/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/zone/view.php");
});
$ROUTER->map("POST", "/zone/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/zone/action.php");
});

#####################
$ROUTER->map("GET", "/area", function () {
  require(__DIR__ . "/src/Views/area/index.php");
});
$ROUTER->map("GET", "/area/create", function ($params) {
  require(__DIR__ . "/src/Views/area/create.php");
});
$ROUTER->map("GET", "/area/export", function ($params) {
  require(__DIR__ . "/src/Views/area/export.php");
});
$ROUTER->map("GET", "/area/view/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/area/view.php");
});
$ROUTER->map("POST", "/area/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/area/action.php");
});

#####################
$ROUTER->map("GET", "/field", function () {
  require(__DIR__ . "/src/Views/field/index.php");
});
$ROUTER->map("GET", "/field/create", function ($params) {
  require(__DIR__ . "/src/Views/field/create.php");
});
$ROUTER->map("GET", "/field/export", function ($params) {
  require(__DIR__ . "/src/Views/field/export.php");
});
$ROUTER->map("GET", "/field/view/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/field/view.php");
});
$ROUTER->map("POST", "/field/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/field/action.php");
});

#####################
$ROUTER->map("GET", "/group", function () {
  require(__DIR__ . "/src/Views/group/index.php");
});
$ROUTER->map("GET", "/group/create", function ($params) {
  require(__DIR__ . "/src/Views/group/create.php");
});
$ROUTER->map("GET", "/group/export", function ($params) {
  require(__DIR__ . "/src/Views/group/export.php");
});
$ROUTER->map("GET", "/group/view/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/group/view.php");
});
$ROUTER->map("POST", "/group/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/group/action.php");
});


##################### AUTH #####################
###################################################
$ROUTER->map("GET", "/", function () {
  require(__DIR__ . "/src/Views/home/login.php");
});
$ROUTER->map("GET", "/home", function () {
  require(__DIR__ . "/src/Views/home/index.php");
});
$ROUTER->map("GET", "/error", function () {
  require(__DIR__ . "/src/Views/home/error.php");
});
$ROUTER->map("POST", "/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/home/action.php");
});
$ROUTER->map("GET", "/[**:params]", function ($params) {
  require(__DIR__ . "/src/Views/home/action.php");
});


$MATCH = $ROUTER->match();

if (is_array($MATCH) && is_callable($MATCH["target"])) {
  call_user_func_array($MATCH["target"], $MATCH["params"]);
} else {
  header("HTTP/1.1 404 Not Found");
  require_once(__DIR__ . "/src/Views/home/error.php");
}
