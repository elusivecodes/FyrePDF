<?php
declare(strict_types=1);

namespace Fyre\Utility;

use Fyre\Utility\Traits\MacroTrait;
use RuntimeException;

use function base64_encode;
use function escapeshellarg;
use function fclose;
use function file_exists;
use function file_get_contents;
use function shell_exec;
use function stream_get_meta_data;
use function tmpfile;
use function unlink;

/**
 * Pdf
 */
class Pdf
{
    use MacroTrait;

    protected static string $binaryPath = 'google-chrome';

    protected static int $timeout = 5000;

    /**
     * Generate a Pdf from a HTML string.
     *
     * @param string $html The HTML string.
     * @return Pdf The Pdf.
     */
    public static function fromHtml(string $html): static
    {
        $base64 = base64_encode($html);

        return new static('data:text/html;base64,'.$base64);
    }

    /**
     * Generate a Pdf from a URL or file path.
     *
     * @param string $url The URL or file path.
     * @return Pdf The Pdf.
     */
    public static function fromUrl(string $url): static
    {
        return new static($url);
    }

    /**
     * Get the Chrome binary path.
     *
     * @return string The Chrome binary path.
     */
    public static function getBinaryPath(): string
    {
        return static::$binaryPath;
    }

    /**
     * Get the timeout.
     *
     * @return int The timeout.
     */
    public static function getTimeout(): int
    {
        return static::$timeout;
    }

    /**
     * Set the Chrome binary path.
     *
     * @return string $binaryPath The Chrome binary path.
     */
    public static function setBinaryPath(string $binaryPath): void
    {
        static::$binaryPath = $binaryPath;
    }

    /**
     * Set the timeout.
     *
     * @return int $timeout The timeout.
     */
    public static function setTimeout(int $timeout): void
    {
        static::$timeout = $timeout;
    }

    /**
     * New Pdf constructor.
     *
     * @param string $source The source.
     */
    public function __construct(
        protected string $source
    ) {}

    /**
     * Save the pdf as a file.
     *
     * @param string $filePath The file path.
     */
    public function save(string $filePath): void
    {
        if (file_exists($filePath)) {
            throw new RuntimeException('File already exists: '.$filePath);
        }

        $command = static::$binaryPath.
            ' --headless'.
            ' --deterministic-mode'.
            ' --print-to-pdf='.escapeshellarg($filePath).
            ' --no-pdf-header-footer'.
            ' --timeout='.escapeshellarg((string) static::$timeout).
            ' '.escapeshellarg($this->source);

        shell_exec($command.' 2>&1');

        if (!file_exists($filePath)) {
            throw new RuntimeException('PDF file could not be generated: '.$filePath);
        }
    }

    /**
     * Get the binary data.
     *
     * @return string The binary data.
     */
    public function toBinary(): string
    {
        $tmpFile = tmpfile();
        $metaData = stream_get_meta_data($tmpFile);
        fclose($tmpFile);

        $this->save($metaData['uri']);

        $output = file_get_contents($metaData['uri']);

        @unlink($metaData['uri']);

        return $output;
    }
}
