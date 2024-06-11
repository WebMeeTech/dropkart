<?php

$settings['trusted_host_patterns'] = [
  '^'.getenv('CENTRALIS_APP_NAME').'\.nz-cms\.nz$',
  '^'.getenv('CENTRALIS_APP_NAME').'-dev\.nz-cms\.nz$',
  '^'.getenv('CENTRALIS_APP_NAME').'-uat\.nz-cms\.nz$',
  '^'.getenv('CENTRALIS_APP_NAME').'-prod\.nz-cms\.nz$',
  '^'.getenv('CENTRALIS_APP_NAME').'\.au3\.ignium\.nz$',
  '^'.getenv('CENTRALIS_APP_NAME').'\.prod\.ignium\.nz$',
  '^'.getenv('CENTRALIS_APP_NAME').'\.uat\.ignium\.nz$',
];

$databases['default']['default']['init_commands']['isolation'] = "SET SESSION tx_isolation='READ-COMMITTED'";
$databases['default']['default']['init_commands']['lock_wait_timeout'] = "SET SESSION innodb_lock_wait_timeout = 20";
$databases['default']['default']['init_commands']['wait_timeout'] = "SET SESSION wait_timeout = 600";

$settings['new_relic_rpm.settings']['api_key'] = getenv('NEW_RELIC');

putenv('ENVIRONMENT=development');

// Environment specific settings files.
if (file_exists(__DIR__ . '/' . getenv('ENVIRONMENT') . '.settings.php')) {
    include __DIR__ . '/' . getenv('ENVIRONMENT') . '.settings.php';
}

// Environment specific services files.
if (file_exists(__DIR__ . '/' . getenv('ENVIRONMENT') . '.services.yml')) {
    $settings['container_yamls'][] = __DIR__ . '/' . getenv('ENVIRONMENT') . '.services.yml';
}