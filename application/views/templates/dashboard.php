<div id="dashboard" class="hide">

    <div id="user-profile">
        <h3>Profil</h3>
        <p>Username: Heini</p>
        <p>Alter: 32</p>
        <p>Geschlecht: M</p>
    </div>

    <div id="user-friends">
        <h3>Freunde</h3>
        <p>Seppo</p>
        <p>Hansi</p>
        <p>Elvira</p>
        <p>Töfte</p>
    </div>

    <div id="user-messages">
               
    <?php
    $this->load->view('dashboard/message-view');
    ?>

    </div>

    <div id="user-stats">
        <h3>Statistik</h3>
        <p>Spiele gewonnen: 2</p>
        <p>Spiele verloren: 5</p>
    </div>

</div>