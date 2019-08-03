<h3 class="dashboard__element__title"><?= $this->session->userdata('user_name') ?>'s Statistik</h3>
    <div class="profile-stats">
        <h4 class="profile-stats__title">Spiele gewonnen:</h4>
        <div class="profile-stats__bar--won" id="games-won-bar">
            <span class="profile-stats__bar__text" id="games-won"><?= $this->session->userdata('games_won') ?></span>
        </div>
        <h4 class="profile-stats__title">Spiele verloren:</h4>
        <div class="profile-stats__bar--lost" id="games-lost-bar">
            <span class="profile-stats__bar__text" id="games-lost"><?= $this->session->userdata('games_lost') ?></span>
        </div>
    </div>
</div>