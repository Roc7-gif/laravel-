<?php

namespace App\Http\Controllers;

use App\Services\MatriculeAnonymizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnonymizedMatricules extends Controller
{
    private $anonymizationService;

    public function __construct(MatriculeAnonymizationService $anonymizationService)
    {
        $this->anonymizationService = $anonymizationService;
    }

    public function index()
    {
        return view('matricule.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
            'matricule_column' => 'required|string|size:1|regex:/[A-Z]/'
        ]);

        try {
            $file = $request->file('excel_file');

            // 1️⃣ Créer un nom de fichier safe
            $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());

            // 2️⃣ Créer un dossier safe temporaire
            $safeDir = storage_path('app/temp_safe/');
            if (!is_dir($safeDir)) mkdir($safeDir, 0755, true);

            // 3️⃣ Déplacer le fichier uploadé vers le dossier safe
            $safePath = $safeDir . $fileName;
            $file->move($safeDir, $fileName); // ⚠ ne pas utiliser storeAs ici

            // 4️⃣ Vérifier l’existence du fichier
            if (!file_exists($safePath)) {
                throw new \Exception("Le fichier Excel n'existe pas : " . $safePath);
            }

            // 5️⃣ Traiter le fichier
            $result = $this->anonymizationService->processExcelFile(
                $safePath,
                $request->matricule_column
            );

            // 6️⃣ Générer le fichier anonymisé
            $outputFileName = 'anonymized_' . $fileName;
            $outputPath = storage_path('app/public/' . $outputFileName);

            $this->anonymizationService->generateAnonymizedExcel(
                $safePath,
                $outputPath,
                $request->matricule_column
            );

            // 7️⃣ Nettoyer le fichier temporaire
            unlink($safePath);

            return response()->json([
                'success' => true,
                'message' => 'Fichier traité avec succès',
                'download_url' => asset('storage/' . $outputFileName)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement: ' . $e->getMessage()
            ], 500);
        }
    }




    public function search(Request $request)
    {
        $request->validate([
            'anonymized_id' => 'nullable|integer',
            'matricule' => 'nullable|string'
        ]);

        if ($request->anonymized_id) {
            $matricule = $this->anonymizationService->getOriginalMatricule($request->anonymized_id);
            return response()->json([
                'anonymized_id' => $request->anonymized_id,
                'original_matricule' => $matricule
            ]);
        }

        if ($request->matricule) {
            $anonymizedId = $this->anonymizationService->getAnonymizedId($request->matricule);
            return response()->json([
                'matricule' => $request->matricule,
                'anonymized_id' => $anonymizedId
            ]);
        }

        return response()->json(['error' => 'Paramètre de recherche manquant'], 400);
    }
}
