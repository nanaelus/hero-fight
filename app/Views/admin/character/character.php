<div class="row">
    <div class="col">
        <form method="POST" action="<?= base_url('admin/character/createcharacter'); ?>">
        <div class="card">
            <div class="card-header">
                <h3> Création d'un Personnage</h3>
            </div>
            <div class="card-body">
                <div class="row">
                <input type="hidden" name="user_id" value="<?= $user->id; ?>">
                <label for="name" class="form-label">Nom du Personnage</label>
                <input type="text" name="name" class="form-control" required>
                </div>
                <p class="mt-3">Vous avez 10 points de caractéristiques à répartir</p>
                <div class="row">
                    <label for="constitution" class="form-label mt-3">Constitution : </label>
                    <div class="col">
                        <input type="number" min=0 max=10 name="constitution" id="constitution" placeholder="0" class="form-control-sm">
                    </div>
                </div>
                <div class="row mt-3">
                    <label for="strength" class="form-label">Force : </label>
                    <div class="col">
                        <input type="number" min=0 max=10 name="strength" id="strength" placeholder="0" class="form-control-sm">
                    </div>
                </div>
                <div class="row mt-3">
                    <label for="agility" class="form-label">Agilité : </label>
                    <div class="col">
                        <input type="number" min=0 max=10 name="agility" id="agility" placeholder="0" class="form-control-sm">
                    </div>
                </div>
                <div class="mt-3">
                    <label for="image" class="form-label">Avatar</label>
                    <input class="form-control" type="file" name="profile_image" id="image" />
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </div>
        </form>
    </div>
</div>