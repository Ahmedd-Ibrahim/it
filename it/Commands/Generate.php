<?php

namespace Phpanonymous\It\Commands;
use Config;
use Illuminate\Console\Command;
use Phpanonymous\It\Commands\DotenvEditor;

class Generate extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'it:generate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Publish All files related to (it)';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */

	public static function changeEnv($key, $value) {
		$env = new DotenvEditor();
		if (!$env->keyExists($key)) {

			$env->addData([
					$key => $value,
				]);
		} else {

			$env->changeEnv([
					$key => $value,
					//'DB_USERNAME'   => 'diaafares',
				]);
		}

		// $path = base_path('.env');
		// if (file_exists($path)) {
		// 	$current = file_get_contents($path);
		// 	$current = str_replace($key.'=', $key.'=', $current);
		// 	$current = str_replace($key.'=', $key.'='.$value, $current);
		// 	file_put_contents($path, $current);
		// }
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {

		self::changeEnv('DB_DATABASE', 'fake');
		self::changeEnv('DB_USERNAME', 'fake');
		self::changeEnv('DB_PASSWORD', 'fake');
		self::changeEnv('DB_HOST', 'localhost');
		\Config::set('filesystems.default', 'it');

		if (!class_exists('ZipArchive')) {
			$this->error("you should be enable the ZipArchive extension On Your Apache To continue ");
			return '';
		}
		$this->warn("attention please: If an event with you (No such file or directory) OR (Connection refused) You should make sure that you have network access validity to ip 127.0.0.1 and default port and access to your mysql check your Apache if you are use MAMP or Xampp Or Wamp Or Lamp ");

		$this->line("Hello Developer thank you for choosing Our Package  \r\n & Welcome to (IT) Super package \r\n Please Answer this questions to auto create your database !!");
		if (PHP_OS == 'Darwin') {
			$mamp_pro = $this->confirm("you are using mamp pro ?");
			if ($mamp_pro) {
				if ($this->confirm("you want auto insert this path (/Applications/MAMP/tmp/mysql/mysql.sock) in DB_SOCKET in .Env file ")) {
					self::changeEnv('DB_SOCKET', '/Applications/MAMP/tmp/mysql/mysql.sock');

					\Config::set('database.connections.mysql.unix_socket', '/Applications/MAMP/tmp/mysql/mysql.sock');
				}
			}
		}

		$DB_DATABASE = $this->ask('What is your DATABASE Name ?');
		$DB_USERNAME = $this->ask('What is your DATABASE Username ?');
		$DB_PASSWORD = $this->ask('What is your DATABASE Password ?');

		if (!empty($DB_DATABASE)) {
			self::changeEnv('DB_DATABASE', $DB_DATABASE);

		}

		if (!empty($DB_USERNAME)) {
			self::changeEnv('DB_USERNAME', $DB_USERNAME);
		}

		if (!empty($DB_PASSWORD)) {
			self::changeEnv('DB_PASSWORD', $DB_PASSWORD);
		}

		if (!empty($DB_DATABASE)) {
			$auto_create_DB = $this->confirm("do you want me to create a database in your engine or you have already created database with name ".$DB_DATABASE."? ");
			if ($auto_create_DB) {
				$pdo = $this->getPDOConnection('', env('DB_PORT'), $DB_USERNAME, $DB_PASSWORD);
				shell_exec('php artisan config:clear');
				shell_exec('php artisan cache:clear');
				$pdo->exec(sprintf(
						'CREATE DATABASE IF NOT EXISTS %s CHARACTER SET %s COLLATE %s;',
						$DB_DATABASE,
						config('database.connections.mysql.charset'),
						config('database.connections.mysql.collation')
					));

				$this->info("DATABAES ".$DB_DATABASE." Created and is ready ");

			}

		}

		self::changeEnv('DB_HOST', '127.0.0.1');

		$this->line("we are build your admin panel and downloading default packages this new version is super fast please wait ...");
		$phpversion = explode('.', phpversion())[1];

		if ($phpversion == '2' && check_package("mockery/mockery") === null) {
			$this->info("Downloading mockery Package....");
			shell_exec('composer require mockery/mockery "1.3.2"');
		}

		if (check_package("langnonymous/lang") === null) {
			$this->info("Downloading Langnonymous Package....");
			shell_exec('composer require Langnonymous/Lang:dev-master');
		}

		if (check_package("spatie/laravel-honeypot") === null) {
			$this->info("Downloading spatie/laravel-honeypot Package....");
			if ($phpversion == '2') {
				shell_exec('composer require spatie/laravel-honeypot "^2.2"');
			} else {
				shell_exec('composer require spatie/laravel-honeypot');
			}
		}

		if (check_package("laravel/ui") === null) {
			$this->info("Downloading laravel/ui Package....");
			if ($phpversion == '2') {
				shell_exec('composer require laravel/ui "^2.0"');
			} else {
				shell_exec('composer require laravel/ui');
			}
		}
		if (check_package("intervention/image") === null) {
			$this->info("Downloading intervention Image Package....");
			shell_exec('composer require intervention/image');
		}

		if (check_package("laravelcollective/html") === null) {
			$this->info("Downloading laravelcollective Package....");
			shell_exec('php artisan it:install laravelcollective');
		}

		if (check_package("maatwebsite/excel") === null) {
			$this->info("Downloading tcpdf....");
			shell_exec('composer require maatwebsite/excel');
		}

		if (check_package("tecnickcom/tcpdf") === null) {
			$this->info("Downloading tcpdf....");
			shell_exec('composer require tecnickcom/tcpdf');
		}

		if (check_package("mpdf/mpdf") === null) {
			$this->info("Downloading mpdf....");
			shell_exec('composer require mpdf/mpdf');
		}

		if (check_package("dompdf/dompdf") === null) {
			$this->info("Downloading dompdf....");
			shell_exec('composer require dompdf/dompdf');
		}

		if (check_package("unisharp/laravel-filemanager") === null) {
			$this->info("Downloading dompdf....");
			shell_exec('composer require unisharp/laravel-filemanager');
		}

		if (check_package("phpoffice/phpspreadsheet") === null) {
			$this->info("Downloading Datatable Yajra Package....");
			shell_exec('composer require phpoffice/phpspreadsheet');
		}

		if (check_package("yajra/laravel-datatables-oracle") === null) {
			$this->info("Downloading Datatable Yajra Package....");
			shell_exec('php artisan it:install yajra');
		}

		$zip = new \ZipArchive;
		$res = $zip->open(__DIR__ .'/../environment/public.zip');
		if ($res === true) {
			$zip->extractTo(base_path('public'));
			$zip->close();
		}

		$this->line("All File Extracted And Published");

		$this->info("Link Storage Automatically....");
		shell_exec('php artisan storage:link');

		$this->info("Auto Publishable Files And Folders....");
		//shell_exec('php artisan vendor:publish --tag=0 --force');
		\Artisan::call('vendor:publish --tag=0 --force');
		$this->info("Publish Files And Folders is Done");

		$this->info("Auto Dump And Compile autoload....");
		shell_exec('composer dump-autoload');
		shell_exec('php artisan config:clear');

		// config('database.connections.mysql.database', $DB_DATABASE);
		// config('database.connections.mysql.username', $DB_USERNAME);
		// config('database.connections.mysql.password', $DB_PASSWORD);

		//if (\Artisan::call('migrate') == 0) {
		// $this->info("Auto Migrate Tables....");
		// $this->info("Migrate Tables Done");
		// \Artisan::call('db:seed');
		// $this->info("Auto Seed And Inject The Admin Login Data....");
		// $this->info("Seed is Done");
		// $this->info("your admin panel now is ready ");
		//} else {
		// $this->info("please run this command php artisan vendor:publish --force and select 0 value to publish all config files");

		// $this->info("please run this command composer dump-autoload to refresh seeder path");

		//}
		$this->info("your admin panel now is ready ");
		$this->info("don't forget to rate us on github visit link: https://github.com/arabnewscms/it ");
		$this->info("auth types \r\n1 - php artisan ui:auth --views");
		$this->info("2 - php artisan ui:auth");
		$this->info("3 - php artisan ui bootstrap --auth");
		$this->info("4 - php artisan ui vue --auth");
		$this->info("5 - php artisan ui react --auth");
		$this->info("please run this command php artisan migrate also");
		$this->info("please run this command php artisan db:seed to fetch admin data (email: test@test.com) - (password: 123456) also");

		$this->info("Login your Admin Panel with (email: test@test.com) - (password: 123456)");

		$this->info("Enjoy <3");
		$this->info("regards and i can assist you now");
		if (date('m') == 1) {
			$this->info("Happy New Year ".date('Y'));
		}
	}

	private function getPDOConnection($host, $port, $username, $password) {
		$host = empty($host)?'127.0.0.1':$host;
		$port = empty($port)?'3306':$port;
		return new \PDO('mysql:port='.$port.';host='.$host, $username, $password);
	}

}
