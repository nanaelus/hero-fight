<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Liste des personnages</h3>
                <a href="<?= base_url('admin/character/new'); ?>"><i class="fa-solid fa-user-plus"></i></a>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover" id="tableCharacters">
                    <thead>
                    <tr>
                        <th>ID perso</th>
                        <th>Nom utilisateur</th> <!-- Mettre le nom d'utilisateur?? -->
                        <th>Nom du Personnage</th>
                        <th>Force</th>
                        <th>Constitution</th>
                        <th>Agilité</th>
                        <th>Expérience</th>
                        <th>Niveau</th>
                        <th>Date de Création</th>
                        <th>Modifier</th>
                        <th>Actif</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let baseUrl = "<?= base_url(); ?>";
        let dataTable = $('#tableCharacters').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "pageLength": 10,
            "language" : {
                url : baseUrl + "/js/datatable/datatable-2.1.4-fr-FR.json"
            },
            "ajax" : {
                "url" : baseUrl + "admin/character/searchdatatable",
                "type" : "POST",
                "data": {'model' : 'CharacterModel'},
            },
            "columns" : [
                {"data" : "id"},
                {"data" : "username"},
                {"data" : "name"},
                {"data" : "strength"},
                {"data" : "constitution"},
                {"data" : "agility"},
                {"data" : "experience"},
                {"data" : "level"},
                {"data" : "created_at"},
                {
                    data : "id",
                    sortable: false,
                    render : function(data) {
                        return `<a href="${baseUrl}admin/character/${data}" title="Modifier le personnage"><i class="fa-solid fa-pencil"></i></a>`
                    }
                },
                {
                    data : "id",
                    sortable : false,
                    render : function(data, type, row) {
                        return (row.deleted_at === null?
                        `<a title="Désactiver un personnage" href="${baseUrl}admin/character/desactivate/${row.id}"><i class="fa-solid fa-xl fa-toggle-off text-success"></i></a>` : `<a title="Activer un personnage" href="${baseUrl}admin/character/activate/${row.id}"><i class="fa-solid fa-toggle-on fa-xl text-danger"></i></a>`);
                    }
                }
            ]
        })
    })
</script>