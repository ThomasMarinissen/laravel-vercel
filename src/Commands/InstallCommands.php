<?php

namespace ThomasMarinissen\LaravelVercel\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Storage;

/**
 * Class Commands
 */
class InstallCommands extends Command
{
    /**
     * The command's signature.
     *
     * @var string
     */
    public $signature = 'vercel:install';

    /**
     * The command's description.
     *
     * @var string
     */
    public $description = 'Install Vercel';

    /**
     * @var null|Filesystem
     */
    protected $disk = null;

    /**
     * Handle the installation command
     */
    public function handle(): void
    {
        // get the available versions
        $versions = $this->laravel['config']['vercel.runtimes'];

        // get the PHP runtime to install
        $version = $this->choice('What PHP runtime would you like to use?', $versions, '8.0');
        $runtime = $versions[$version];

        // load the stub vercel.json stub
        $vercelJsonStub = $this->loadStub('vercel.json');

        // set the correct vercel build and key
        $vercelJsonStub = str_replace('{{ runtime }}', $runtime, $vercelJsonStub);
        $vercelJsonStub = str_replace('{{ app_key }}', $this->generateKey(), $vercelJsonStub);

        // store the vercel.json file
        $this->disk()->put('vercel.json', $vercelJsonStub);

        // make sure that the other stubs are available
        $this->addStubs();
    }

    /**
     * Add the stubs
     *
     * @throws FileNotFoundException
     */
    protected function addStubs(): void
    {
        // get access to the disk
        $disk = $this->disk();

        // make sure that the api directory is available
        if (!$disk->exists('api')) {
            $disk->makeDirectory('api');
        }

        // add the files
        $disk->put('api/index.php', $this->loadStub('api/index.php'));
        $disk->put('.vercelignore', $this->loadStub('.vercelignore'));
    }

    /**
     * Get the disk
     *
     * @return Filesystem               The file system class
     */
    protected function disk(): Filesystem
    {
        // if the disk has been requested before, return it
        if (!is_null($this->disk)) {
            return $this->disk;
        }

        // create the disk, set it and return it
        return $this->disk = Storage::build([
            'driver' => 'local',
            'root' => $this->laravel->basePath(),
        ]);
    }

    /**
     * Get the path to a stub
     *
     * @param string            $path The stub to get the path for
     * @return string           The full stub path
     */
    protected function stub(string $path): string
    {
        return $this->laravel['config']['vercel.stubs_path'] . $path;
    }

    /**
     * Load the content of a stub
     *
     * @param   string                  $path The stub path
     * @return  string                  The stub content
     */
    protected function loadStub(string $path): string
    {
        return file_get_contents($this->stub($path));
    }

    /**
     * Generate an app key
     *
     * @return string               The generated key
     */
    protected function generateKey(): string
    {
        return 'base64:'.base64_encode(
            Encrypter::generateKey($this->laravel['config']['app.cipher'])
        );
    }
}
