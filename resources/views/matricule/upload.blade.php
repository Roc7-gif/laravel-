@extends('base')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Anonymisation des Matricules</div>
                <div class="card-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Fichier Excel</label>
                            <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                        </div>

                        <div class="mb-3">
                            <label for="matricule_column" class="form-label">Colonne des matricules (lettre)</label>
                            <input type="text" class="form-control" id="matricule_column" name="matricule_column"
                                value="A" maxlength="1" pattern="[A-Z]" required>
                            <div class="form-text">Indiquez la lettre de la colonne contenant les matricules (A, B, C, etc.)</div>
                        </div>

                        <button type="submit" class="btn btn-primary">Traiter le fichier</button>
                    </form>

                    <div id="result" class="mt-4" style="display: none;">
                        <div class="alert alert-success">
                            <h5>Résultat du traitement</h5>
                            <p id="resultMessage"></p>
                            <a id="downloadLink" href="#" class="btn btn-success">Télécharger le fichier anonymisé</a>
                        </div>
                    </div>

                    <div id="error" class="alert alert-danger mt-4" style="display: none;"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('{{ route("matricule.process") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data)
                    document.getElementById('resultMessage').textContent =
                        `Fichier traité avec succès.`;
                    document.getElementById('downloadLink').href = data.download_url;
                    document.getElementById('result').style.display = 'block';
                    document.getElementById('error').style.display = 'none';
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                showError('Erreur lors du traitement: ' + error);
            });
    });

    function searchById() {
        const id = document.getElementById('searchId').value;
        if (!id) return;

        fetch(`/matricule/search?anonymized_id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('searchResult').innerHTML =
                    `<div class="alert alert-info">
                    ID ${data.anonymized_id} → Matricule: ${data.original_matricule || 'Non trouvé'}
                </div>`;
            });
    }
    // total
    function searchByMatricule() {
        const matricule = document.getElementById('searchMatricule').value;
        if (!matricule) return;

        fetch(`/matricule/search?matricule=${encodeURIComponent(matricule)}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('searchResult').innerHTML =
                    `<div class="alert alert-info">
                    Matricule ${data.matricule} → ID: ${data.anonymized_id || 'Non trouvé'}
                </div>`;
            });
    }

    function showError(message) {
        document.getElementById('error').textContent = message;
        document.getElementById('error').style.display = 'block';
        document.getElementById('result').style.display = 'none';
    }
</script>
@endsection