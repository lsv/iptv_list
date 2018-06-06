<?php
namespace Deployer;

require 'recipe/symfony4.php';

set('application', 'iptv.aarhof.eu');
set('repository', 'git@github.com:lsv/iptv_list.git');
set('git_tty', true);
host('iptv.aarhof.eu')
    ->user('root')
    ->set('deploy_path', '/ext/{{application}}')
    ->set('writable_mode', 'chmod')
    ->set('writable_chmod_mode', '0777')
    ->set('writable_chmod_recursive', true)
;
after('success', 'deploy:writable');
after('deploy:failed', 'deploy:unlock');
