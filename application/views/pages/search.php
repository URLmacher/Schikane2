<div class="search-kastel__wrapper">
    <div class="kastel2 search-kastel" id="search-wrapper">

        <h1 class="kastel2__title">Spielersuche</h1>

        <div class='kastel2__content'>
            <div id="countdown" class="search__countdown"></div>

            <div class="search__player-display">
                <h5 id="playerone" class="card-title user_name">Player1: <span class="search__player-display__span">
                    <?php echo $this->session->user_name; ?>
                <span></h5> 
                <div class="readystate" id="playeroneready"></div>

                <h5 id="playertwo" class="card-title user_name">Player2: <span class="search__player-display__span" id="playertwoname">-</span></h5>
                <div class="readystate" id="playertwoready"></div>
            </div>

            <div id="button-box" class="kastel2__btn-box search__btn-box">
                <button class="btn" id="search" role="button">Mitspieler suchen</button>
                <button class="btn" id="friend-dropdown" role="button">Freunde einladen &#x25bc;</button>
            </div>
        </div>
    </div>
</div>
<div class="search-friendlist hide" id="search-friendlist">
    <table class="table search-friendlist__table" id="search-table">
        <thead>
            <tr>
                <th class="table__header">Freund</th>
                <th class="table__header table__data--small">Online</th>
                <th class="table__header table__data--small">Einladen</th>
            </tr>
        </thead>
        <tbody id="search-friendlist__table__body">
            <tr>
                <td>Keine Freunde</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<div id="searching" class="hide">
    <h4>Mitspieler werden gesucht...</h4>
    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
</div>
<script src="<?= base_url(); ?>assets/js/search.js"></script>