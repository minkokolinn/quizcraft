<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Process;

class DailyDatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup DB and email the SQL file directly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get database connection details from the config
        $db = config('database.connections.mysql');
        
        // Prepare the mysqldump command
        $command = [
            'mysqldump',
            '-u' . $db['username'],
            '-p' . $db['password'],
            '-h' . $db['host'],
            $db['database']
        ];

        // Execute the mysqldump command
        $process = new Process($command);
        $process->setTimeout(300); // Adjust timeout as necessary
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Backup failed.');
            return;
        }

        // Get the SQL dump output as a string
        $sqlDump = $process->getOutput();

        // Send the backup file via email
        Mail::to('hmtmmmmkklmthn@gmail.com')->send(new \App\Mail\DatabaseBackupMail($sqlDump));
        
        $this->info('Backup completed and emailed.');
    }
}
