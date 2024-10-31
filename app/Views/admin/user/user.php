<div class="row">
    <div class="col">
        <form action="<?= isset($utilisateur) ? "/admin/user/update" : "/admin/user/create" ?>" method="POST"  enctype="multipart/form-data">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <?= isset($utilisateur) ? "Editer " . $utilisateur['username'] : "Créer un utilisateur" ?>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Onglets?-->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-pane" type="button" role="tab" aria-controls="profile" aria-selected="true">Profile</button>
                        </li>
                        <?php if(isset($utilisateur)) { ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="character-tab" data-bs-toggle="tab" data-bs-target="#character-pane" type="button" role="tab" aria-controls="character" aria-selected="false">Personnages</button>
                        </li>
                        <?php } ?>
                    </ul>

                    <!-- tab panes??-->
                    <div class="tab-content p-3">
                        <div class="tab-pane fade show active" id="profile-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Pseudo</label>
                                        <input type="text" class="form-control" id="username" placeholder="username" value="<?= isset($utilisateur) ? $utilisateur['username'] : ""; ?>" name="username">
                                    </div>
                                    <div class="mb-3">
                                        <label for="mail" class="form-label">E-mail</label>
                                        <input type="text" class="form-control" id="mail" placeholder="mail" value="<?= isset($utilisateur) ? $utilisateur['email'] : "" ?>" name="email" <?= isset($utilisateur) ? "readonly" : "" ?> >
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mot de passe</label>
                                        <input type="password" class="form-control" id="password" placeholder="password" value="" name="password">
                                    </div>
                                    <div class="mb-3">
                                        <label for="id_permission" class="form-label">Rôle</label>
                                        <select class="form-select" id="id_permission" name="id_permission">
                                            <option disabled <?= !isset($utilisateur) ? "selected":""; ?> >Selectionner un role</option>
                                            <?php foreach($permissions as $p): ?>
                                                <option value="<?= $p['id']; ?>" <?= ( isset($utilisateur) && $p['id'] == $utilisateur['id_permission']) ? "selected" : "" ?> >
                                                    <?= $p['name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Avatar</label>
                                        <input class="form-control" type="file" name="profile_image" id="image" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="character-pane" role="tabpanel" aria-labelledby="character-tab" tabindex="0">
                            <div class="row">
                            <table class="table table-hover" style="width: 100%" id="tableCharacters">
                                <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>Nom du Personnage</th>
                                    <th>Niveau</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fin des onglets??-->
                <div class="card-footer text-end">
                    <?php if (isset($utilisateur)): ?>
                        <input type="hidden" name="id" value="<?= $utilisateur['id']; ?>">
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary">
                        <?= isset($utilisateur) ? "Sauvegarder" : "Enregistrer" ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        var baseUrl = "<?= base_url(); ?>";
        var dataTable = $('#tableCharacters').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "pageLength": 10,
            "language" : {
                url: baseUrl + "js/datatable/datatable-2.1.4-fr-FR.json",
                "emptyTable": "Aucun personnage trouvé pour cet utilisateur" // Message personnalisé
            },
            "ajax" : {
                "url" : baseUrl + "admin/character/searchdatatable",
                "type" : "POST",
                "data" :
                    { 'model' : 'CharacterModel', 'filter' : 'character', 'filter_value' : "<?= isset($utilisateur['id']) ? $utilisateur['id'] : ""; ?>"},
            },
            "columns": [
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
                {"data" : "level"},
                {"data" : "created_at"},
            ]
        });
    });
</script>