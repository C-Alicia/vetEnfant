<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250): string
    {
        // Récupérer le nom d'origine et son extension
        $nomImage = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($picture->getClientOriginalExtension());

        // Vérifier que le fichier est bien une image
        $pictureInfos = getimagesize($picture->getPathname());
        if ($pictureInfos === false) {
            throw new Exception('Format d\'image incorrect');
        }

        // Vérification du type MIME et création de l'image source
        switch ($pictureInfos['mime']) {
            case 'image/png':
                $pictureSource = imagecreatefrompng($picture->getPathname());
                break;
            case 'image/jpeg':
                $pictureSource = imagecreatefromjpeg($picture->getPathname());
                break;
            case 'image/webp':
                $pictureSource = imagecreatefromwebp($picture->getPathname());
                break;
            default:
                throw new Exception('Format d\'image non pris en charge');
        }

        if (!$pictureSource) {
            throw new Exception('Impossible de créer l\'image source');
        }

        // Récupération des dimensions de l'image originale
        $imageWidth = $pictureInfos[0];
        $imageHeight = $pictureInfos[1];

        // Déterminer la taille du carré à extraire
        if ($imageWidth > $imageHeight) {
            $squareSize = $imageHeight;
            $src_x = ($imageWidth - $squareSize) / 2;
            $src_y = 0;
        } elseif ($imageWidth < $imageHeight) {
            $squareSize = $imageWidth;
            $src_x = 0;
            $src_y = ($imageHeight - $squareSize) / 2;
        } else {
            $squareSize = $imageWidth;
            $src_x = 0;
            $src_y = 0;
        }

        // Création d'une nouvelle image redimensionnée
        $resizedPicture = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $resizedPicture,
            $pictureSource,
            0,
            0,
            (int)$src_x,
            (int)$src_y,
            $width,
            $height,
            $squareSize,
            $squareSize
        );

        // Définition du chemin de destination
        $path = $this->params->get('images_directory') . $folder;

        // Création des dossiers si nécessaire
        if (!is_dir($path . '/mini/')) {
            if (!mkdir($path . '/mini/', 0755, true) && !is_dir($path . '/mini/')) {
                throw new Exception('Échec de la création des dossiers');
            }
        }

        // Nouveau nom de fichier en webp
        $webpName = $nomImage . '.webp';

        // Enregistrement de l'image redimensionnée en webp
        imagewebp($resizedPicture, $path . '/mini/' . $width . 'x' . $height . '-' . $webpName);

        // Déplacement de l'image originale vers le répertoire en changeant l'extension en .webp
        $picture->move($path . '/', $webpName);

        return $webpName;
    }

    public function delete(string $fichier, ?string $folder = '', ?int $width = 250, ?int $height = 250): bool
    {
        if ($fichier !== 'default.webp') {
            $success = false;
            $path = $this->params->get('images_directory') . $folder;

            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $fichier;
            if (file_exists($mini)) {
                unlink($mini);
                $success = true;
            }

            $original = $path . '/' . $fichier;
            if (file_exists($original)) {
                unlink($original);
                $success = true;
            }

            return $success;
        }

        return false;
    }
}
