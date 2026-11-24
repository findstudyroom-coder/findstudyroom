<?php

namespace App\Services;

class ImageService
{
    /**
     * Get a random educational image URL from Unsplash
     * Uses Unsplash Source API - free and no authentication needed
     */
    public static function getEducationalImage($keyword = 'education', $width = 800, $height = 600)
    {
        // Static high-quality images from Unsplash that will never change or 404
        $staticImages = [
            'library' => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=' . $width . '&q=80',
            'tutor' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=' . $width . '&q=80',
            'student' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=' . $width . '&q=80',
            'classroom' => 'https://images.unsplash.com/photo-1427504494785-319ce8372ac0?auto=format&fit=crop&w=' . $width . '&q=80',
            'education' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=' . $width . '&q=80',
        ];

        return $staticImages[$keyword] ?? $staticImages['education'];
    }

    /**
     * Get tutor placeholder image
     */
    public static function getTutorImage()
    {
        return self::getEducationalImage('tutor', 500, 600);
    }

    /**
     * Get library placeholder image
     */
    public static function getLibraryImage()
    {
        return self::getEducationalImage('library', 800, 600);
    }

    /**
     * Get student/study placeholder image
     */
    public static function getStudyImage()
    {
        return self::getEducationalImage('student', 800, 600);
    }

    /**
     * Get classroom placeholder image
     */
    public static function getClassroomImage()
    {
        return self::getEducationalImage('classroom', 800, 600);
    }
}
