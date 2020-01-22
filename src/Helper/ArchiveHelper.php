<?php
declare(strict_types=1);

namespace ReviewParser\Helper;

use \ZipArchive;

class ArchiveHelper
{
    private const DEFAULT_ARCHIVE_DIR = 'archive/';

    /**
     * @var string
     */
    private $archiveAlias;

    /**
     * @var array
     */
    private $compressDirs;

    /**
     * ArchiveHelper constructor.
     * @param string $archiveAlias
     * @param array $compressDirs
     */
    public function __construct(string $archiveAlias, array $compressDirs)
    {
        $this->archiveAlias = $archiveAlias;
        $this->compressDirs = $compressDirs;
    }

    public function movePreviousToArchive(): void
    {
        $archiveDirectory = self::DEFAULT_ARCHIVE_DIR;
        $archiveFile = $archiveDirectory . $this->archiveAlias . '_' . date('Ymd_His') . '.zip';

        $this->archiveDirectories($archiveFile, $this->compressDirs);
        $this->removeDirectoriesContent($this->compressDirs);
    }

    /**
     * @param string $archiveFile
     * @param array $compressDirs
     */
    protected function archiveDirectories(string $archiveFile, array $compressDirs): void
    {
        $zip = new ZipArchive();
        $res = $zip->open($archiveFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($res === true) {
            foreach ($compressDirs as $type => $compressDir) {
                $compressDirectoryItterator = glob($compressDir . '/*');
                $zip->addEmptyDir($type);
                foreach ($compressDirectoryItterator as $file) {
                    if (!strpos($file, '.gitempty')) {
                        $zip->addFile($file, $type . '/' . basename($file));
                    }
                }
            }
            $zip->close();
        } else {
            throw new \RuntimeException('Failed to create to zip. Error: ' . $res);
        }
    }

    /**
     * @param array $compressDirs
     */
    protected function removeDirectoriesContent(array $compressDirs): void
    {
        foreach ($compressDirs as $index => $compressDir) {
            $compressDirectoryItterator = glob($compressDir . '/*');
            foreach ($compressDirectoryItterator as $file) {
                if (!strpos($file, '.gitempty')) {
                    unlink($file);
                }
            }
        }
    }
}