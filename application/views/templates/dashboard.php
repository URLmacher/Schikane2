<div id="dashboard" class="hide">

    <div id="user-profile">
       <?php
        $this->load->view('dashboard/profile-view');
        ?>
    </div>

    <div id="user-friends">
        <?php
        $this->load->view('dashboard/friends-view');
        ?>
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