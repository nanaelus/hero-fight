<div class="row">
    <div class="col">
        <form method="POST" action="<?= isset($character) ? base_url('admin/character/updatecharacter') : base_url('admin/character/createcharacter'); ?>" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header">
                <h3><?= isset($character) ? "Modification" : "Création"; ?> d'un Personnage <?= isset($character) ? "(" . $character['name'] . ", niveau : " . $character['level'] . ", expérience : " . $character['experience'] .")" : ""; ?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                <input type="hidden" name="user_id" value="<?= isset($character) ? $character['user_id'] : $user->id; ?>">
                <label for="name" class="form-label">Nom du Personnage</label>
                <input type="text" name="name" class="form-control" <?= isset($character) ? "Value='" . $character['name'] . "'" : "placeholder='Entrez votre Pseudo' required"; ?> id="name">
                </div>
                <p class="mt-3" id="points"></p>
                <input id="d-points" type="hidden" name="divided_points" value="<?= isset($character) ? $character['divided_points'] : 10; ?>">
                <div class="row">
                    <label for="constitution" class="form-label mt-3">Constitution : </label>
                    <div class="col">
                        <i class="fa-solid fa-circle-minus" id="c-minus"></i>
                        <input type="text" readonly name="constitution" id="constitution" value="<?= isset($character) ? $character['constitution'] : 0 ;?>" style="width: 30px">
                        <i class="fa-solid fa-circle-plus" id="c-plus"></i>
                    </div>
                </div>
                <div class="row">
                    <label for="strength" class="form-label mt-3">Force : </label>
                    <div class="col">
                        <i class="fa-solid fa-circle-minus" id="s-minus"></i>
                        <input type="text" readonly name="strength" id="strength" value="<?= isset($character) ? $character['strength'] : 0 ;?>" style="width: 30px">
                        <i class="fa-solid fa-circle-plus" id="s-plus"></i>
                    </div>
                </div>
                <div class="row">
                    <label for="agility" class="form-label mt-3">Agilité : </label>
                    <div class="col">
                        <i class="fa-solid fa-circle-minus" id="a-minus"></i>
                        <input type="text" readonly name="agility" id="agility" value="<?= isset($character) ? $character['agility'] : 0 ;?>" style="width: 30px">
                        <i class="fa-solid fa-circle-plus" id="a-plus"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <label for="image" class="form-label">Avatar</label>
                    <?php if($character) {
                        if($avatar) { ?>
                            <div class="row mb-3">
                                <img src="<?= base_url($avatar[0]['file_path']); ?>" alt="avatar" style="max-width: 200px; height= auto;">
                            </div>
                        <?php } else { ?>
                            <div class="row mb-3">
                                <img src="<?= base_url('/assets/img/avatars/1.jpg'); ?>" alt="Default Avatar" style="max-width: 200px; height= auto;">
                            </div>
                    <?php }
                    };?>
                    <input class="form-control" type="file" name="profile_image" id="image">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><?= isset($character) ? "Modifier" : "Créer"; ?></button>
                <input type="hidden" name="id" value="<?= isset($character) ? $character['id'] : '' ; ?>"
            </div>
        </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        let TotalPoints = <?= isset($character) ? $character['divided_points'] : 10; ?>;
        let constitution = <?= isset($character) ? $character['constitution'] : 0; ?>;
        let strength = <?= isset($character) ? $character['strength'] : 0; ?>;
        let agility = <?= isset($character) ? $character['agility'] : 0; ?>;
        $('#points').text("Points restant : " + TotalPoints);
        $('#c-minus').click(function () {
            if(constitution > 0) {
                constitution -= 1;
                TotalPoints +=1;
                $('#constitution').val(constitution);
                $('#points').text("Points restant : " + TotalPoints);
                $('#d-points').val(TotalPoints);
            }
        });
        $('#c-plus').click(function () {
            if(<?= isset($character) ? "" : "constitution < 10 && "  ;?>TotalPoints>0) {
                constitution += 1;
                TotalPoints -= 1;
                $('#constitution').val(constitution);
                $('#points').text("Points restant : " + TotalPoints);
                $('#d-points').val(TotalPoints);
            }
        });
        $('#s-minus').click(function () {
            if(strength > 0) {
                strength -= 1;
                TotalPoints +=1;
                $('#strength').val(strength);
                $('#points').text("Points restant : " + TotalPoints);
                $('#d-points').val(TotalPoints);
            }
        });
        $('#s-plus').click(function () {
            if(<?= isset($character) ? "" : "strength < 10 && "  ;?>TotalPoints>0) {
                strength += 1;
                TotalPoints -= 1;
                $('#strength').val(strength);
                $('#points').text("Points restant : " + TotalPoints);
                $('#d-points').val(TotalPoints);
            }
        });
        $('#a-minus').click(function () {
            if(agility > 0) {
                agility -= 1;
                TotalPoints +=1;
                $('#agility').val(agility);
                $('#points').text("Points restant : " + TotalPoints);
                $('#d-points').val(TotalPoints);
            }
        });
        $('#a-plus').click(function () {
            if(<?= isset($character) ? "" : "agility < 10 && "  ;?>TotalPoints>0) {
                agility += 1;
                TotalPoints -= 1;
                $('#agility').val(agility);
                $('#points').text("Points restant : " + TotalPoints);
                $('#d-points').val(TotalPoints);
            }
        });
    });
</script>