<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config
set('repository', 'https://github.com/mardomai/hajusrakendus.git');
set('keep_releases', 2);

// Hosts
host('tak22mai.itmajakas.ee')
    ->set('http_user', 'mardomai')
    ->set('remote_user', 'mardomai')
    ->set('deploy_path', '~/domeenid/hajusrakendus');

// Tasks
task('opcache:clear', function () {
    run('killall php84-cgi || true');
});

// Main deploy task
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:optimize:clear',
    'artisan:optimize',
    'deploy:publish'
]);

// Hooks
after('deploy:failed', 'deploy:unlock');
before('deploy:success', 'opcache:clear');
