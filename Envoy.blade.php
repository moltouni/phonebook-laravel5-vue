@include('vendor/autoload.php')

@setup

    (new Dotenv\Dotenv(__DIR__, '.env'))->load();

    $local = getenv('LOCAL_SERVER');
    $staging = getenv('STAGING_SERVER');

    if (!isset($server)) dd('Please specify --server=[local, staging]');

    $directory = null;
    if ($server == 'local') $directory = '/home/vagrant/phonebook/';
    else if ($server == 'staging') $directory = '/var/www/phonebook/';
    else dd('Please specify --server=[local, staging]');
    
@endsetup

@servers([ 'local' => $local, 'staging' => $staging ])

@story('deploy', ['on' => $server])
    @if ($server != 'local')
        gitPull
    @endif
    @if ($install)
        composerInstall
    @endif
    @if ($reset)
        migrateReset
        composerDumpAutoload
    @endif
    migrate
    @if ($reset)
        dbSeed
    @endif
@endstory

@task('test', ['on' => $server])
    cd {{ $directory }}
    ./vendor/bin/phpunit
@endtask

@task('gitPull')
    cd {{ $directory }}
    echo "CMD: sudo git pull";
    sudo git pull
@endtask

@task('composerInstall')
    cd {{ $directory }}
    echo "CMD: composer install";
    composer install
@endtask

@task('composerDumpAutoload')
    cd {{ $directory }}
    echo "CMD: composer dump-autoload";
    composer dump-autoload
@endtask

@task('migrateReset')
    cd {{ $directory }}
    echo "CMD: php artisan migrate:reset";
    php artisan migrate:reset
@endtask

@task('migrate')
    cd {{ $directory }}
    echo "CMD: php artisan migrate";
    php artisan migrate
@endtask

@task('dbSeed')
    cd {{ $directory }}
    echo "CMD: php artisan db:seed";
    php artisan db:seed
@endtask

@finished
@endfinished