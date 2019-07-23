<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="display-3">Spielersuche</h3>
        </div>
        <div class="card-body">
            <div id="countdown"></div>
            <h5 id="playerone" class="card-title user_name">Player1: <span>
                <?php echo $this->session->user_name; ?>
            <span></h5> 
            <div class="readystate" id="playeroneready"></div>
            <h5 id="playertwo" class="card-title user_name">Player2: <span class="view-profile view-profile-search" id="playertwoname">-</span></h5>
            <div class="readystate" id="playertwoready"></div>
            <div id="button-box" class="flex-justify">
                <a class="btn btn-primary " href="#" id="search" role="button">Mitspieler suchen</a>
                <div class="dropdown show">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Freunde einladen</a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" id="search-friendlist">
                        <a class="dropdown-item" href="#">Keine Freunde</a>
                    </div>
                </div>
            </div>
            <div id="searching" class="hide">
                <h4>Mitspieler werden gesucht...</h4>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/search.js"></script>