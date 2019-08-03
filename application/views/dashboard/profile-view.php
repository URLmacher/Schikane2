<h3 class="dashboard__element__title"><?= $this->session->userdata('user_name') ?>'s Profil</h3>
<div class="own-profile">
    <div class="profile__titles">
       
        <h4 class="profile__titles__title">Geschlecht</h4>  
        <h4 class="profile__titles__title">Alter</h4>  
        <h4 class="profile__titles__title">Wohnort</h4>  

    </div>
    <div class="profile__content">
       
        <div class="profile__content__item" id="own-profile-sex">
            <?= $this->session->userdata('user_sex') ?>
        </div>  
        
        <div class="profile__content__item" id="own-profile-age">
            <?= $this->session->userdata('user_age') ?>
        </div>
        
        <div class="profile__content__item" id="own-profile-city"> 
            <?= $this->session->userdata('user_city') ?>
        </div>
       
    </div>
    <div class="own-profile__edit hide" id="own-profile__edit">
         <!-- SEX -->
         <div class="own-profile__edit__item ">
            <div class="radio-group">
                <input type="radio" class="own-profile__edit__radio" name="profile-sex-radio"   id="profile-sex-radio-1" value="männlich">
                <label class="own-profile__edit__label" for="profile-sex-radio-1" >Männlich</label>
                <input type="radio" class="own-profile__edit__radio" name="profile-sex-radio" id="profile-sex-radio-2" value="weiblich"> 
                <label class="own-profile__edit__label" for="profile-sex-radio-2">Weiblich</label>
                <input type="radio" class="own-profile__edit__radio" name="profile-sex-radio"  id="profile-sex-radio-3" value="sonstig">
                <label class="own-profile__edit__label" for="profile-sex-radio-3">Sonstiges</label>
            </div>
            <span id="profile-sex-error" class="error-profile"></span>
        </div>
        <!-- AGE -->    
        <div class="own-profile__edit__item form-group">
            <input type="number" class="own-profile__input form-control" id="profile-age-input">
            <span id="profile-age-error" class="error-profile"></span>
        </div>  
        <!-- LOCATION -->
        <div class="own-profile__edit__item form-group">
            <input type="text" id="profile-city-input" class="own-profile__input form-control">
            <span id="profile-city-error" class="error-profile"></span>
        </div>

    </div>

    <div class="own-profile__group">
        <p class="own-profile__text--info hide" id="profile-info-text">Diese Informationen sind auch für andere sichtbar</p>
    </div>

    <div id="profile-settings" class="hide">
        <div class="own-profile__group">
            <h4>Profil löschen:</h4>
            <button type="button" class="btn--bad" id="profile-delete-btn">Löschen</button>

            <div id="profile-delete-confirm" class="hide">
                <p>Sind Sie sicher?</p>
                <form class="form-group">
                    <label for="profil-delete-password">Bitte geben Sie Ihr Passwort ein</label>
                    <input type="password" class="form-control" id="profil-delete-password" placeholder="Password">
                    <p class="error-profile" id="profile-delete-error"></p>
                </form>
                <button type="button" class="btn--bad" id="profile-delete-confirm-btn">Löschen</button>
                <button type="button" class="btn--neutral" id="profile-delete-back-btn">Zurück</button>
            </div>
        </div>
    </div>
    <div class="own-profile__group " id="profile-view-btn-box">
        <a href="#" id="profile-edit-btn" class="btn">Edit</a>
        <a href="#" id="profile-settings-btn" class="btn">Settings</a>
    </div>
    <div class="own-profile__group hide" id="profile-edit-view-btn-box">
        <a href="#" id="profile-edit-save-btn" class="btn">Speichern</a>
        <a href="#" id="profile-edit-back-btn" class="btn">Zurück</a>
    </div>
    <div class="own-profile__group hide" id="profile-settings-view-btn-box">
        <a href="#" id="profile-settings-back-btn" class="btn">Zurück</a>
    </div>

</div>