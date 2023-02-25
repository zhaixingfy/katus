<?php
error_reporting(1);
header('Access-Control-Allow-Origin: *');

// usleep(300e3);

$conn = new mysqli('localhost', 'root', 'root');
$req = $_REQUEST;

function query($sql) {
  global $conn;
  $handler = $conn->query($sql) or die(err(1, 'sql error: '.$sql));
  return $handler;
}

function queryRow($sql) {
  return query($sql)->fetch_assoc();
}

function queryList($sql) {
  $handler = query($sql);
  $result = [];

  while ($row = $handler->fetch_assoc()) {
    $result[] = $row;
  }

  return $result;
}

function res($data) {
  echo json_encode($data);
  exit;
}

function err($errCode, $errMsg) {
  res([
    'errCode' => $errCode,
    'errMsg' => $errMsg,
  ]);
}

$curDB = $req['curDB'];
if ($curDB) query('USE '.$curDB);
$curTable = $req['curTable'];

switch ($req['a']) {
  case 'ls-def':
    $engines = queryList("SHOW ENGINES");
    $collation = queryList("SHOW COLLATION");
    $charset = queryList("SHOW CHARACTER SET");
    $vars = queryList("SHOW VARIABLES");
    $globalStatus = queryList("SHOW GLOBAL STATUS");
    
    res(array(
      'now' => time(),
      'version' => queryRow("select version() as version")['version'],
      'serverLang' => 'php',
      'dbInfo' => array(
        'affected_rows' => $conn->affected_rows,
        'client_info' => $conn->client_info,
        'client_version' => $conn->client_version,
        'connect_errno' => $conn->connect_errno,
        'connect_error' => $conn->connect_error,
        'errno' => $conn->errno,
        'error' => $conn->error,
        'error_list' => $conn->error_list,
        'field_count' => $conn->field_count,
        'host_info' => $conn->host_info,
        'info' => $conn->info,
        'insert_id' => $conn->insert_id,
        'server_info' => $conn->server_info,
        'server_version' => $conn->server_version,
        'stat' => $conn->stat,
        'sqlstate' => $conn->sqlstate,
        'protocol_version' => $conn->protocol_version,
        'thread_id' => $conn->thread_id,
        'warning_count' => $conn->warning_count,
      ),
      // 'server' => $_SERVER,
      'engines' => $engines,
      'collation' => $collation,
      'charset' => $charset,
      'vars' => $vars,
      'globalStatus' => $globalStatus,
      'php_ini' => ini_get_all(),
      // 'isSupportShell' => IS_SUPPORT_SHELL,
    ));
    break;
  case 'ls-db':
    res(array_map(function($row) {
      return $row['Database'];
    }, queryList('SHOW DATABASES')));
    break;
  case 'ls-table':
    if (!$curDB) err(1, 'ls-table err: no curDB');
    
    res([
      'sc' => queryRow('SHOW CREATE DATABASE '.$curDB)['Create Database'],
      'list' => array_map(function($row) use ($curDB) {
        return $row['Tables_in_'.$curDB];
      }, queryList('SHOW TABLES')),
    ]);
    break;
  case 'ls-field':
    res([
      'sc' => queryRow('SHOW CREATE TABLE '.$curTable),
      'list' => queryList('desc '.$curTable)
    ]);
    break;
  case 'ls-table-data':
    $pageSize = intval($req['pageSize']);
    $pageSize = $pageSize >= 10 && $pageSize <= 5000 ? $pageSize : 100;

    $total = intval(queryRow("SELECT COUNT(*) AS total FROM $curTable")['total']);
    $totalPage = ceil($total / $pageSize);
    
    $pageCur = intval($req['pageCur']);
    $start = ($pageCur - 1) * $pageSize;
    $list = (
      $pageCur >= 1 && $pageCur <= $totalPage ?
        queryList("SELECT * FROM $curTable LIMIT $start, $pageSize") : []
    );

    res([
      'sc' => queryRow('SHOW CREATE TABLE '.$curTable),
      'listField' => queryList('desc '.$curTable),
      'page' => [
        'cur' => $pageCur,
        'size' => $pageSize,
        'total' => $total,
      ],
      'list' => $list,
    ]);
    break;
  default:
    err(1, 'unknown action: '.$req['a']);
    break;
}