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
            <h5 id="playertwo" data-userid=" <?php echo (isset($player2_id )? $player2_id : ''); ?>" class="card-title user_name">Player2: <span id="playertwoname">
                <?php echo (isset($player2 )? $player2 : '-'); ?>
            </span></h5>
            <div class="readystate" id="playertwoready"></div>
            <div id="button-box">
                <a class="btn btn-primary " href="#" id="search" role="button">Mitspieler suchen</a>
            </div>
            <div id="searching" class="hide">
                <h4>Mitspieler werden gesucht...</h4>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/search.js"></script>