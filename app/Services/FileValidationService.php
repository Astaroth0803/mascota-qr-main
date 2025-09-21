<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileValidationService
{
    /**
     * Tipos MIME permitidos para imágenes
     */
    private const ALLOWED_IMAGE_MIMES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp'
    ];

    /**
     * Tipos MIME permitidos para documentos
     */
    private const ALLOWED_DOCUMENT_MIMES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    /**
     * Extensiones permitidas para imágenes
     */
    private const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    /**
     * Extensiones permitidas para documentos
     */
    private const ALLOWED_DOCUMENT_EXTENSIONS = ['pdf', 'doc', 'docx'];

    /**
     * Tamaño máximo de archivos en bytes
     */
    private const MAX_IMAGE_SIZE = 5 * 1024 * 1024; // 5MB
    private const MAX_DOCUMENT_SIZE = 10 * 1024 * 1024; // 10MB

    /**
     * Valida una imagen de perfil
     */
    public function validateProfileImage(UploadedFile $file): array
    {
        $errors = [];

        // Verificar tamaño
        if ($file->getSize() > self::MAX_IMAGE_SIZE) {
            $errors[] = 'La imagen no puede ser mayor a 5MB.';
        }

        // Verificar tipo MIME real (no solo extensión)
        $realMimeType = $this->getRealMimeType($file);
        if (!in_array($realMimeType, self::ALLOWED_IMAGE_MIMES)) {
            $errors[] = 'El tipo de archivo no es válido. Solo se permiten imágenes JPG, PNG, GIF y WebP.';
        }

        // Verificar extensión
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_IMAGE_EXTENSIONS)) {
            $errors[] = 'La extensión del archivo no es válida.';
        }

        // Verificar que el contenido sea realmente una imagen
        if (!$this->isValidImageContent($file)) {
            $errors[] = 'El archivo no es una imagen válida.';
        }

        return $errors;
    }

    /**
     * Valida un documento médico
     */
    public function validateMedicalDocument(UploadedFile $file): array
    {
        $errors = [];

        // Verificar tamaño
        if ($file->getSize() > self::MAX_DOCUMENT_SIZE) {
            $errors[] = 'El documento no puede ser mayor a 10MB.';
        }

        // Verificar tipo MIME real
        $realMimeType = $this->getRealMimeType($file);
        if (!in_array($realMimeType, self::ALLOWED_DOCUMENT_MIMES)) {
            $errors[] = 'El tipo de archivo no es válido. Solo se permiten PDF, DOC y DOCX.';
        }

        // Verificar extensión
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_DOCUMENT_EXTENSIONS)) {
            $errors[] = 'La extensión del archivo no es válida.';
        }

        return $errors;
    }

    /**
     * Obtiene el tipo MIME real del archivo (no solo el reportado)
     */
    private function getRealMimeType(UploadedFile $file): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file->getPathname());
        finfo_close($finfo);

        return $mimeType;
    }

    /**
     * Verifica que el contenido del archivo sea realmente una imagen
     */
    private function isValidImageContent(UploadedFile $file): bool
    {
        $imageInfo = getimagesize($file->getPathname());
        return $imageInfo !== false;
    }

    /**
     * Genera un nombre de archivo seguro
     */
    public function generateSecureFileName(UploadedFile $file, string $prefix = ''): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $randomString = Str::random(32);
        $timestamp = now()->format('Y_m_d_H_i_s');
        
        return $prefix . $timestamp . '_' . $randomString . '.' . $extension;
    }

    /**
     * Escanea el archivo en busca de malware básico
     */
    public function scanForMalware(UploadedFile $file): bool
    {
        // Verificar patrones sospechosos en el contenido
        $content = file_get_contents($file->getPathname());
        
        // Patrones comunes de malware en archivos
        $malwarePatterns = [
            '/<script[^>]*>.*?<\/script>/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload=/i',
            '/onerror=/i',
            '/eval\(/i',
            '/base64_decode\(/i'
        ];

        foreach ($malwarePatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return false; // Archivo sospechoso detectado
            }
        }

        return true; // Archivo limpio
    }

    /**
     * Valida y procesa un archivo de manera segura
     */
    public function processFileSecurely(UploadedFile $file, string $type = 'image'): array
    {
        $errors = [];

        // Validar según el tipo
        if ($type === 'image') {
            $errors = $this->validateProfileImage($file);
        } elseif ($type === 'document') {
            $errors = $this->validateMedicalDocument($file);
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Escanear en busca de malware
        if (!$this->scanForMalware($file)) {
            return ['success' => false, 'errors' => ['El archivo contiene contenido sospechoso.']];
        }

        // Generar nombre seguro
        $secureFileName = $this->generateSecureFileName($file, $type . '_');

        return [
            'success' => true,
            'secure_filename' => $secureFileName,
            'original_name' => $file->getClientOriginalName()
        ];
    }
}
