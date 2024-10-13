<?php
declare(strict_types=1);

namespace Tests;

use finfo;
use Fyre\Utility\Pdf;
use PHPUnit\Framework\TestCase;
use RuntimeException;

use function mime_content_type;
use function mkdir;
use function rmdir;
use function touch;
use function unlink;

use const FILEINFO_MIME_TYPE;

final class PdfTest extends TestCase
{
    public function testGetBinaryPath(): void
    {
        $this->assertSame(
            'google-chrome',
            Pdf::getBinaryPath()
        );
    }

    public function testGetTimeout(): void
    {
        $this->assertSame(
            5000,
            Pdf::getTimeout()
        );
    }

    public function testPdfSaveHtml(): void
    {
        Pdf::fromHtml('<h1>Test</h1>')
            ->save('tmp/test.pdf');

        $this->assertSame(
            'application/pdf',
            mime_content_type('tmp/test.pdf')
        );
    }

    public function testPdfSaveUrl(): void
    {
        Pdf::fromUrl('tests/Mock/test.html')
            ->save('tmp/test.pdf');

        $this->assertSame(
            'application/pdf',
            mime_content_type('tmp/test.pdf')
        );
    }

    public function testPdfSaveUrlExists(): void
    {
        $this->expectException(RuntimeException::class);

        touch('tmp/test.pdf');

        Pdf::fromUrl('tests/Mock/test.html')
            ->save('tmp/test.pdf');
    }

    public function testPdfToBinary(): void
    {
        $pdf = Pdf::fromHtml('<h1>Test</h1>')
            ->toBinary();

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($pdf);

        $this->assertSame(
            'application/pdf',
            $mimeType
        );
    }

    protected function setUp(): void
    {
        Pdf::setBinaryPath('google-chrome');
        Pdf::setTimeout(5000);

        mkdir('tmp');
    }

    protected function tearDown(): void
    {
        @unlink('tmp/test.pdf');
        rmdir('tmp');
    }
}
