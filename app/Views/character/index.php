<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Liste des personnages</h3>
                <a href="<?= base_url('character/new'); ?>"><i class="fa-solid fa-user-plus"></i></a>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover" id="tableCharacters">
                    <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Nom du Personnage</th>
                        <th>Force</th>
                        <th>Constitution</th>
                        <th>Agilité</th>
                        <th>Expérience</th>
                        <th>Niveau</th>
                        <th>Date de Création</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
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
                url : baseUrl + "/js/datatable/datatable-2.1.4-fr-FR.json",
                sEmptyTable: "<a href='#'>No records to display</a>"
            },
            "ajax" : {
                "url" : baseUrl + "character/searchdatatable",
                "type" : "POST",
                "data": {'model' : 'CharacterModel', 'filter' : 'user', 'filter_value' : "<?= $myCharacters ? $myCharacters[0]['user_id'] : '' ; ?>" },
            },
            "columns" : [
                {
                    data : "avatar_url",
                    sortable : false,
                    render : function(data) {
                        if(data) {
                            return `<img src="${baseUrl}/${data}" alt="avatar" style="max-width: 20px; height= auto;">`;
                        } else {
                            return `<img src="${baseUrl}/assets/img/avatars/1.jpg" alt="Default Avatar" style="max-width: 20px; height: auto;">`;
                        }
                    }
                },
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
                        return `<a href="${baseUrl}character/${data}" title="Modifier le personnage"><i class="fa-solid fa-pencil"></i></a>`
                    }
                },
                {
                    data : "id",
                    sortable : false,
                    render : function(data, type, row) {
                        return `<a title="Supprimer un personnage" href="${baseUrl}character/desactivate/${row.id}"><i class="fa-solid fa-trash-can"></i></a>`;
                    }
                }
            ]
        })
    })
</script>
