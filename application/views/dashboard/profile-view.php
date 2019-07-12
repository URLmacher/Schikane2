<h3>Profil</h3>
<div class="card">
    <div class="card-body">
        <p class="card-text" id="profile-info-text">Diese Informationen sind auch für andere sichtbar</p>
    </div>
    <ul class="list-group list-group-flush" id="profile-content">
        <li class="list-group-item">Username: <span class="profile-data">
            <?= $this->session->userdata('user_name') ?></span>
        </li>
        <li class="list-group-item">Geschlecht: <span class="profile-data"  id="own-profile-sex">
            <?= $this->session->userdata('user_sex') ?></span>
            <span id="profile-sex-input-span" class="profile-edit-form hide">
                <div class="form-check">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="profile-sex-radio"   id="profile-sex-radio-1" value="männlich" >
                    Männlich
                    </label>
                </div>
                <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="profile-sex-radio" id="profile-sex-radio-2" value="weiblich">
                    Weiblich
                    </label>
                </div>
                <div class="form-check disabled">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="profile-sex-radio"  id="profile-sex-radio-3" value="sonstig" >
                    Sonstig
                    </label>
                </div>
            </span>
            <span id="profile-sex-error" class="error-profile"></span>
        </li>
        <li class="list-group-item">Alter: <span class="profile-data"  id="own-profile-age">
            <?= $this->session->userdata('user_age') ?></span>
            <span id="profile-age-input-span" class="profile-edit-form hide"><input type="number" id="profile-age-input"></span>
            <span id="profile-age-error" class="error-profile"></span>
        </li>
        <li class="list-group-item">Woher: <span class="profile-data" id="own-profile-city">
            <?= $this->session->userdata('user_city') ?></span>
            <span id="profile-city-input-span" class="profile-edit-form hide"><input type="text" id="profile-city-input"></span>
            <span id="profile-city-error" class="error-profile"></span>
        </li>
    </ul>
    <div id="profile-settings" class="hide">
        <div class="card-body">
            <h4>Profil löschen:</h4>
            <button type="button" class="btn btn-danger" id="profile-delete-btn">Löschen</button>

            <div id="profile-delete-confirm" class="hide">
                <p>Sind Sie sicher?</p>
                <button type="button" class="btn btn-danger" id="profile-delete-confirm-btn">Löschen</button>
                <button type="button" class="btn btn-primary" id="profile-delete-back-btn">Zurück</button>
            </div>
        </div>
    </div>
    <div class="card-body " id="profile-view-btn-box">
        <a href="#" id="profile-edit-btn" class="card-link">Edit</a>
        <a href="#" id="profile-settings-btn" class="card-link">Settings</a>
    </div>
    <div class="card-body hide" id="profile-edit-view-btn-box">
        <a href="#" id="profile-edit-save-btn" class="card-link">Speichern</a>
        <a href="#" id="profile-edit-back-btn" class="card-link">Zurück</a>
    </div>
    <div class="card-body hide" id="profile-settings-view-btn-box">
        <a href="#" id="profile-settings-back-btn" class="card-link">Zurück</a>
    </div>
</div>