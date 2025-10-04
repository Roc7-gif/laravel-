<?php

namespace App\Services;

use App\Models\AnonymizedMatricules;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MatriculeAnonymizationService
{
    private $nextAnonymizedId;

    public function __construct()
    {
        // Récupérer le dernier ID anonymisé ou commencer à 1
        $lastRecord = AnonymizedMatricules::orderBy('anonymized_id', 'desc')->first();
        $this->nextAnonymizedId = $lastRecord ? $lastRecord->anonymized_id + 1 : 1;
    }

    /**
     * Traiter le fichier Excel et anonymiser les matricules
     */
    public function processExcelFile($filePath, $matriculeColumn = 'A'): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        $matricules = [];
        $anonymizedData = [];

        // Parcourir les lignes (en supposant que la première ligne est l'en-tête)
        $highestRow = $worksheet->getHighestRow();

        for ($row = 2; $row <= $highestRow; $row++) {
            $matricule = $worksheet->getCell($matriculeColumn . $row)->getValue();

            if (!empty($matricule)) {
                $matricules[] = $matricule;
                $anonymizedId = $this->getOrCreateAnonymizedId($matricule);
                $anonymizedData[] = [
                    'matricule' => $matricule,
                    'anonymized_id' => $anonymizedId,
                    'row' => $row
                ];
            }
        }

        return [
            'matricules' => $matricules,
            'anonymized_data' => $anonymizedData,
            'total_processed' => count($matricules)
        ];
    }

    /**
     * Obtenir ou créer un ID anonymisé pour un matricule
     */
    private function getOrCreateAnonymizedId(string $matricule): int
    {
        // Vérifier si le matricule existe déjà
        $existing = AnonymizedMatricules::where('matricule', $matricule)->first();

        if ($existing) {
            return $existing->anonymized_id;
        }

        // Créer un nouvel enregistrement
        $anonymizedRecord = AnonymizedMatricules::create([
            'matricule' => $matricule,
            'anonymized_id' => $this->nextAnonymizedId
        ]);

        $newId = $this->nextAnonymizedId;
        $this->nextAnonymizedId++;

        return $newId;
    }

    /**
     * Générer un fichier Excel avec les données anonymisées
     */
    public function generateAnonymizedExcel($originalFilePath, $outputFilePath, $matriculeColumn = 'A'): string
    {
        $spreadsheet = IOFactory::load($originalFilePath);
        $worksheet = $spreadsheet->getActiveSheet();

        $highestRow = $worksheet->getHighestRow();

        for ($row = 2; $row <= $highestRow; $row++) {
            $matricule = $worksheet->getCell($matriculeColumn . $row)->getValue();

            if (!empty($matricule)) {
                $anonymizedId = $this->getOrCreateAnonymizedId($matricule);
                $worksheet->setCellValue($matriculeColumn . $row, $anonymizedId);
            }
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($outputFilePath);

        return $outputFilePath;
    }

    /**
     * Récupérer le matricule original à partir de l'ID anonymisé
     */
    public function getOriginalMatricule(int $anonymizedId): ?string
    {
        $record = AnonymizedMatricules::where('anonymized_id', $anonymizedId)->first();
        return $record ? $record->matricule : null;
    }

    /**
     * Récupérer l'ID anonymisé à partir du matricule original
     */
    public function getAnonymizedId(string $matricule): ?int
    {
        $record = AnonymizedMatricules::where('matricule', $matricule)->first();
        return $record ? $record->anonymized_id : null;
    }
}
