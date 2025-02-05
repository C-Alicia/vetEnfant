<?php

namespace App\Tests\Service;

use App\Service\PictureService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureServiceTest extends TestCase
{
    /** @var PictureService */
    private $pictureService;

    /** @var ParameterBagInterface&\PHPUnit\Framework\MockObject\MockObject */
    private $parameterBagMock;

    protected function setUp(): void
    {
        // Création d'un mock pour ParameterBagInterface
        $this->parameterBagMock = $this->createMock(ParameterBagInterface::class);
        $this->parameterBagMock
            ->method('get')
            ->willReturn(sys_get_temp_dir()); // Utilisation d'un dossier temporaire

        // Initialisation du service avec le mock
        $this->pictureService = new PictureService($this->parameterBagMock);
    }

    public function testAddValidImage(): void
    {
        // Vérification de l'existence du fichier dans le répertoire fixtures
        $fixturePath = __DIR__ . '/../fixtures/ArticleBabyShirt.jpg';
        $this->assertFileExists($fixturePath, 'Le fichier ArticleBabyShirt.jpg doit exister dans le dossier fixtures.');

        // Préparation d'une image fictive
        $tempFile = tempnam(sys_get_temp_dir(), 'test_');
        copy($fixturePath, $tempFile);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'ArticleBabyShirt.jpg',
            'image/jpeg',
            null,
            true // Mode test
        );

        // Appel de la méthode
        $fileName = $this->pictureService->add($uploadedFile);

        // Vérifications
        $this->assertNotEmpty($fileName, 'Le nom du fichier ne doit pas être vide.');
        $this->assertStringEndsWith('.webp', $fileName, 'Le fichier doit être converti en .webp.');
        $this->assertFileExists(sys_get_temp_dir() . '/mini/250x250-' . $fileName, 'Le fichier redimensionné doit exister.');
    }

    public function testAddInvalidImage(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Format d\\image incorrect');

        // Création d'un fichier non image
        $tempFile = tempnam(sys_get_temp_dir(), 'test_');
        file_put_contents($tempFile, 'Texte non valide pour une image');

        $uploadedFile = new UploadedFile(
            $tempFile,
            'test.txt',
            'text/plain',
            null,
            true
        );

        // Appel de la méthode (doit lancer une exception)
        $this->pictureService->add($uploadedFile);
    }

    public function testDeleteImage(): void
    {
        // Préparation des fichiers
        $tempDir = sys_get_temp_dir();
        $fileName = 'test.webp';

        $miniFile = $tempDir . '/mini/250x250-' . $fileName;
        $originalFile = $tempDir . '/' . $fileName;

        // Création des fichiers fictifs si nécessaires
        if (!file_exists($tempDir . '/mini')) {
            mkdir($tempDir . '/mini', 0755, true);
        }

        touch($miniFile);
        touch($originalFile);

        // Appel de la méthode
        $result = $this->pictureService->delete($fileName);

        // Vérifications
        $this->assertTrue($result, 'La méthode doit retourner true en cas de succès.');
        $this->assertFileDoesNotExist($miniFile, 'Le fichier redimensionné doit être supprimé.');
        $this->assertFileDoesNotExist($originalFile, 'Le fichier original doit être supprimé.');
    }
}
